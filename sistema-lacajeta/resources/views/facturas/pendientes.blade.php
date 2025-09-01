@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto font-sans">
    <h2 class="text-xl font-semibold text-purple-700 mb-6">📌 Facturas Pendientes</h2>

    {{-- Botón para volver al historial --}}
    <div class="mb-6 text-right">
        <a href="{{ route('facturas.index') }}"
           class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow">
            ← Volver al historial
        </a>
    </div>

    {{-- Lista de facturas pendientes --}}
    @forelse ($facturas as $factura)
        <div class="bg-white shadow rounded-xl p-4 mb-4 flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800 text-lg">Factura #{{ $factura->id }}</p>
                <p class="text-sm text-gray-500">Deudor: {{ $factura->deudor->nombre ?? 'Sin deudor' }}</p>
                <p class="text-sm text-gray-500">Total: ₡{{ number_format($factura->total, 2) }}</p>
                <p class="text-xs mt-1 font-semibold text-red-700">Estado: Pendiente</p>
            </div>
            <div class="flex gap-2">
                {{-- Ver factura --}}
                <a href="{{ route('facturas.show', $factura->id) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
                    Ver
                </a>

                {{-- Eliminar factura --}}
                <form method="POST" action="{{ route('facturas.destroy', $factura->id) }}"
                      onsubmit="return confirm('¿Eliminar esta factura?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-500 text-center">No hay facturas pendientes en este momento.</p>
    @endforelse
</div>
@endsection
