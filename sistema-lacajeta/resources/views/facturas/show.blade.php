@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto font-sans">
    <h2 class="text-xl font-semibold text-purple-700 mb-6">
        📄 Factura #{{ $factura->id }}
    </h2>

    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <p><strong>Fecha:</strong> {{ $factura->fecha }}</p>
        <p><strong>Deudor:</strong> {{ $factura->deudor->nombre ?? 'Sin deudor' }}</p>
        <p><strong>Estado:</strong>
            <span class="{{ $factura->estado == 'Pagada' ? 'text-green-700' : 'text-red-700' }}">
                {{ $factura->estado }}
            </span>
        </p>
        <p><strong>Forma de pago:</strong> {{ $factura->forma_pago ?? 'N/A' }}</p>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="font-semibold mb-3">🛒 Productos</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-sm">
                    <th class="border px-2 py-1 text-left">Producto</th>
                    <th class="border px-2 py-1">Cantidad</th>
                    <th class="border px-2 py-1">Precio</th>
                    <th class="border px-2 py-1">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura->detalles as $d)
                    <tr>
                        <td class="border px-2 py-1">{{ $d->producto->nombre ?? 'Eliminado' }}</td>
                        <td class="border px-2 py-1 text-center">{{ $d->cantidad }}</td>
                        <td class="border px-2 py-1 text-right">₡{{ number_format($d->precio_unitario,2) }}</td>
                        <td class="border px-2 py-1 text-right">₡{{ number_format($d->subtotal,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-100 font-bold">
                    <td colspan="3" class="border px-2 py-1 text-right">TOTAL</td>
                    <td class="border px-2 py-1 text-right">₡{{ number_format($factura->total,2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="{{ route('facturas.index') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded">← Volver</a>

        <form method="POST" action="{{ route('facturas.destroy',$factura->id) }}"
              onsubmit="return confirm('¿Eliminar esta factura?')">
            @csrf @method('DELETE')
            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">
                Eliminar
            </button>
        </form>
    </div>
</div>
@endsection
