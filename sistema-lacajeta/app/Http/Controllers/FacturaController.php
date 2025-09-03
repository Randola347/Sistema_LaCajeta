<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class FacturaController extends Controller
{
    /** Listar todas las facturas */
    public function index()
    {
        $facturas = Factura::with('deudor')->orderByDesc('created_at')->get();
        return view('facturas.index', compact('facturas'));
    }

    /** Listar pendientes */
    public function pendientes()
    {
        $facturas = Factura::with('deudor')
            ->where('estado', 'Pendiente')
            ->orderByDesc('created_at')
            ->get();

        return view('facturas.pendientes', compact('facturas'));
    }

    /** Ver factura */
    public function show(Request $request, $id)
    {
        $factura = Factura::with(['deudor', 'detalles.producto'])->findOrFail($id);

        // Detecta si viene desde deudores o desde facturas
        $from = $request->query('from', 'facturas');

        return view('facturas.show', compact('factura', 'from'));
    }


    /** Eliminar factura */
    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', "Factura #{$id} eliminada.");
    }
    public function imprimir($id)
    {
        $factura = Factura::with('detalles.producto')->findOrFail($id);

        try {
            // Asegúrate de que "XP-58" sea el nombre correcto de tu impresora en Windows
            $connector = new WindowsPrintConnector("XP-58");
            $printer = new Printer($connector);

            // Encabezado
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("MINISUPER LA CAJETA\n");
            $printer->text("Factura #" . $factura->id . "\n");
            $printer->text("Fecha: " . $factura->fecha . "\n");
            $printer->feed();

            // Productos
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($factura->detalles as $d) {
                $linea = $d->producto->nombre . " x" . $d->cantidad;
                $precio = "" . number_format($d->subtotal, 2);
                $printer->text(str_pad($linea, 24) . $precio . "\n");
            }

            // Total
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setEmphasis(true); // letras más "fuertes"
            $printer->text("TOTAL: " . number_format($factura->total, 2) . "\n");
            $printer->setEmphasis(false);

            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("¡Gracias por su compra!\n");
            $printer->feed(3);

            $printer->cut();
            $printer->close();

            return back()->with('success', 'Factura enviada a la impresora.');
        } catch (\Exception $e) {
            return back()->with('error', "Error al imprimir: " . $e->getMessage());
        }
    }
}
