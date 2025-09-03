<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Producto;
use App\Models\Deudor;
use App\Models\Factura;
use App\Models\DetalleFactura;

class VentasController extends Controller
{
    /** Catálogo con buscador */
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $productos = Producto::when($q, fn($query) =>
        $query->where('nombre', 'like', "%{$q}%")
            ->orWhere('descripcion', 'like', "%{$q}%"))
            ->orderBy('nombre')
            ->paginate(12);

        $cart = session('cart', ['items' => []]);
        $total = $this->cartTotal($cart);

        return view('ventas.index', compact('productos', 'q', 'cart', 'total'));
    }

    /** Agregar al carrito (normal o AJAX) */
    public function add(Request $request)
    {
        $data = $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad'    => ['required', 'numeric', 'min:1'],
        ]);

        $p = Producto::findOrFail($data['producto_id']);
        $cart = session('cart', ['items' => []]);
        $pid = (string)$p->id;

        if (!isset($cart['items'][$pid])) {
            $cart['items'][$pid] = [
                'producto_id' => $p->id,
                'nombre'      => $p->nombre,
                'precio'      => (float)$p->precio,
                'cantidad'    => 0,
            ];
        }
        $cart['items'][$pid]['cantidad'] += (float)$data['cantidad'];
        session(['cart' => $cart]);

        // 🔹 AJAX → JSON
        if ($request->ajax()) {
            return response()->json([
                'ok'    => true,
                'count' => collect($cart['items'])->sum('cantidad'),
                'total' => $this->cartTotal($cart),
                'line'  => $cart['items'][$pid],
            ]);
        }

        // 🔹 Normal → redirección
        return back()->with('success', 'Producto agregado al carrito.');
    }


    /** Vista del carrito */
    public function cart()
    {
        $cart = session('cart', ['items' => []]);
        $total = $this->cartTotal($cart);
        $deudores = Deudor::orderBy('nombre')->get();
        $facturaId = session('last_factura_id');

        return view('ventas.cart', compact('cart', 'total', 'deudores', 'facturaId'));
    }

    /** Actualizar cantidad en AJAX */
    public function qty(Request $request, $id)
    {
        $request->validate(['cantidad' => 'required|numeric|min:0']);
        $cart = session('cart', ['items' => []]);
        $pid = (string)$id;

        if (isset($cart['items'][$pid])) {
            $val = (float)$request->cantidad;
            if ($val <= 0) {
                unset($cart['items'][$pid]);
                $exists = false;
                $lineTotal = 0;
            } else {
                $cart['items'][$pid]['cantidad'] = $val;
                $exists = true;
                $lineTotal = $cart['items'][$pid]['precio'] * $val;
            }
            session(['cart' => $cart]);

            return response()->json([
                'ok' => true,
                'exists' => $exists,
                'lineTotal' => $lineTotal,
                'total' => $this->cartTotal($cart),
                'count' => collect($cart['items'])->sum('cantidad'),
            ]);
        }
        return response()->json(['ok' => false]);
    }

    /** Eliminar línea (AJAX o normal) */
    public function remove(Request $request, $id)
    {
        $cart = session('cart', ['items' => []]);
        unset($cart['items'][(string)$id]);
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'total' => $this->cartTotal($cart),
                'count' => collect($cart['items'])->sum('cantidad')
            ]);
        }
        return back()->with('success', 'Producto eliminado.');
    }

    /** Vaciar carrito */
    public function clear(Request $request)
    {
        session()->forget('cart');
        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Carrito vaciado.');
    }

    /** Checkout → crea factura */
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'modo'              => ['required', 'in:sin_deudor,deudor_existente,deudor_nuevo'],
            'deudor_id'         => ['nullable', 'exists:deudores,id'],
            'nuevo_nombre'      => ['nullable', 'string', 'max:255'],
            'nuevo_descripcion' => ['nullable', 'string'],
            'forma_pago'        => ['nullable', 'in:efectivo,tarjeta,sinpe'],
            'fecha'             => ['nullable', 'date'],
        ]);

        $cart = session('cart', ['items' => []]);
        if (empty($cart['items'])) {
            return back()->withErrors(['carrito' => 'No hay productos en el carrito.']);
        }

        $deudorId = null;
        $formaPago = null;
        $estado = 'Pendiente';

        if ($data['modo'] === 'deudor_existente') {
            $deudorId = $data['deudor_id'];
        } elseif ($data['modo'] === 'deudor_nuevo') {
            $deudor = Deudor::create([
                'nombre' => $data['nuevo_nombre'],
                'descripcion' => $data['nuevo_descripcion']
            ]);
            $deudorId = $deudor->id;
        } else {
            $estado = 'Pagada';
            $formaPago = $data['forma_pago'];
        }

        $fecha = $data['fecha'] ?? Carbon::now()->format('Y-m-d');

        $factura = DB::transaction(function () use ($deudorId, $formaPago, $estado, $fecha, $cart) {
            $factura = Factura::create([
                'deudor_id' => $deudorId,
                'fecha' => $fecha,
                'estado' => $estado,
                'forma_pago' => $formaPago,
                'total' => $this->cartTotal($cart),
            ]);

            foreach ($cart['items'] as $line) {
                DetalleFactura::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $line['producto_id'],
                    'cantidad' => $line['cantidad'],
                    'precio_unitario' => $line['precio'],
                    'subtotal' => $line['precio'] * $line['cantidad'],
                ]);
                $producto = Producto::find($line['producto_id']);
                if ($producto) {
                    $producto->decrement('inventario', $line['cantidad']);
                }
            }
            return $factura;
        });

        session()->forget('cart');
        session(['last_factura_id' => $factura->id]);

        return redirect()->route('facturas.show', $factura->id)
            ->with('success', "Factura #{$factura->id} creada correctamente.");
    }

    /** Helpers */
    private function cartTotal(array $cart): float
    {
        $t = 0;
        foreach ($cart['items'] ?? [] as $l) {
            $t += $l['precio'] * $l['cantidad'];
        }
        return $t;
    }

    public function ajaxAdd(Request $request)
    {
        $data = $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad'    => ['required', 'numeric', 'min:1'],
        ]);

        $p = Producto::findOrFail($data['producto_id']);
        $cart = session('cart', ['items' => []]);
        $pid = (string)$p->id;

        if (!isset($cart['items'][$pid])) {
            $cart['items'][$pid] = [
                'producto_id' => $p->id,
                'nombre'      => $p->nombre,
                'precio'      => (float)$p->precio,
                'cantidad'    => 0,
            ];
        }
        $cart['items'][$pid]['cantidad'] += (float)$data['cantidad'];

        session(['cart' => $cart]);

        return response()->json([
            'ok'    => true,
            'count' => collect($cart['items'])->sum('cantidad'),
            'total' => $this->cartTotal($cart),
            'line'  => $cart['items'][$pid],
        ]);
    }
}
