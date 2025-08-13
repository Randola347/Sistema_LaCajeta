<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Deudor;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function pendientes(Request $request)
    {
        $deudores = Deudor::all();

        $facturas = Factura::with('deudor')
            ->where('estado', 'Pendiente')
            ->when($request->deudor_id, function ($query) use ($request) {
                $query->where('deudor_id', $request->deudor_id);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('facturas.pendientes', compact('facturas', 'deudores'));
    }

    public function index()
    {
        $facturas = Factura::with('deudor')->orderByDesc('created_at')->get();
        return view('facturas.index', compact('facturas'));
    }
}
