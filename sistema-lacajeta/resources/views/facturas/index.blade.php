@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto font-sans relative">
        <h2 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
            🧾 Historial de Facturas
        </h2>

        <!-- Botón para ver facturas pendientes -->
        <div class="mb-6 text-right">
            <a href="{{ route('facturas.pendientes') }}"
               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-blue px-4 py-2 rounded-md shadow font-semibold text-sm">
                📌 Ver Pendientes
            </a>
        </div>

        @forelse ($facturas as $factura)
            <div class="bg-white shadow-md rounded-xl p-4 mb-4 flex justify-between items-start">
                <div>
                    <p class="font-bold text-gray-800 text-lg">Factura #{{ $factura->id }}</p>
                    <p class="text-sm text-gray-500">Deudor: {{ $factura->deudor->nombre ?? 'Sin deudor' }}</p>
                    <p class="text-sm text-gray-500">Total: ₡{{ number_format($factura->total, 2) }}</p>
                    <span class="text-xs font-semibold inline-block mt-1 {{ $factura->estado == 'Pagada' ? 'text-green-700' : 'text-red-700' }}">
                        Estado: {{ $factura->estado }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center">No hay facturas registradas aún.</p>
        @endforelse
    </div>
@endsection
