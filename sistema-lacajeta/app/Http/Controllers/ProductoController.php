<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $productos = Producto::with('proveedor')
            ->when($q, function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('descripcion', 'like', "%{$q}%");
            })
            ->orderBy('nombre')
            ->get();

        // Si es petición AJAX (buscador en tiempo real), devolver solo la lista
        if ($request->ajax()) {
            return view('productos.partials.list', compact('productos'))->render();
        }

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('productos.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'inventario' => 'required|integer',
            'proveedor_id' => 'required|exists:proveedores,id',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $proveedores = Proveedor::all();
        return view('productos.edit', compact('producto', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'inventario' => 'required|integer',
            'proveedor_id' => 'required|exists:proveedores,id',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::with('proveedor')->findOrFail($id);

        session([
            'deleted_producto_data' => [
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'inventario' => $producto->inventario,
                'proveedor_nombre' => $producto->proveedor->nombre,
            ]
        ]);

        $producto->delete();

        return redirect()->route('productos.index')->with('deleted', $producto->nombre);
    }

    public function undoDelete(Request $request)
    {
        $data = session('deleted_producto_data');

        if (!$data) {
            return redirect()->route('productos.index')->with('error', 'No hay datos para restaurar.');
        }

        $proveedor = Proveedor::where('nombre', $data['proveedor_nombre'])->first();

        if (!$proveedor) {
            return redirect()->route('productos.index')->with('error', 'Proveedor original no encontrado.');
        }

        Producto::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'inventario' => $data['inventario'],
            'proveedor_id' => $proveedor->id,
        ]);

        session()->forget('deleted_producto_data');

        return redirect()->route('productos.index')->with('success', "Producto restaurado correctamente.");
    }
}
