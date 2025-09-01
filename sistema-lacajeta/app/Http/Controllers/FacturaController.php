<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

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
            ->where('estado','Pendiente')
            ->orderByDesc('created_at')
            ->get();

        return view('facturas.pendientes', compact('facturas'));
    }

    /** Ver factura */
    public function show($id)
    {
        $factura = Factura::with(['deudor','detalles.producto'])->findOrFail($id);
        return view('facturas.show', compact('factura'));
    }

    /** Eliminar factura */
    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success',"Factura #{$id} eliminada.");
    }
}
