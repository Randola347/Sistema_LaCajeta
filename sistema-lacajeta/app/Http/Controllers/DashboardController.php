<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'day'); // por defecto día
        $start = match ($period) {
            'day'   => Carbon::today(),
            'week'  => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            default => Carbon::today(),
        };

        // facturas del periodo
        $facturas = Factura::whereDate('created_at', '>=', $start)->get();
        $ventasTotal = $facturas->sum('total');
        $cantidadFacturas = $facturas->count();

        // productos más vendidos
        $productos = DetalleFactura::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_vendidos')
        )
            ->whereHas('factura', fn($q) => $q->whereDate('created_at', '>=', $start))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendidos')
            ->with('producto')
            ->take(5)
            ->get();

        return view('dashboard', compact('period', 'ventasTotal', 'cantidadFacturas', 'productos'));
    }
}
