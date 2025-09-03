@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto font-sans">
    <h2 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
        📊 Resumen de Ventas
    </h2>

    {{-- Filtros --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
            <select name="period" onchange="this.form.submit()" class="border rounded px-3 py-2">
                <option value="day"   {{ $period=='day' ? 'selected' : '' }}>Hoy</option>
                <option value="week"  {{ $period=='week' ? 'selected' : '' }}>Esta semana</option>
                <option value="month" {{ $period=='month' ? 'selected' : '' }}>Este mes</option>
            </select>
        </form>
    </div>

    {{-- Totales --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-gray-500">Facturas emitidas</p>
            <p class="text-2xl font-bold text-purple-700">{{ $cantidadFacturas }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-gray-500">Ventas totales</p>
            <p class="text-2xl font-bold text-green-600">₡{{ number_format($ventasTotal, 2) }}</p>
        </div>
    </div>

    {{-- Productos más vendidos --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top productos más vendidos</h3>
        <ul class="divide-y divide-gray-200">
            @forelse($productos as $prod)
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">{{ $prod->producto->nombre ?? 'Producto' }}</p>
                        <p class="text-sm text-gray-500">Stock: {{ $prod->producto->inventario ?? 0 }}</p>
                    </div>
                    <span class="font-semibold text-purple-700">{{ $prod->total_vendidos }} vendidos</span>
                </li>
            @empty
                <p class="text-gray-500 text-center">No hay ventas registradas en este periodo.</p>
            @endforelse
        </ul>
    </div>
</div>
@endsection
