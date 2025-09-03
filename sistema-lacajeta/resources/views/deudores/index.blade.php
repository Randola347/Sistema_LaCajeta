@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto font-sans relative">
    <h2 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
        🧾 Gestión de Deudores
    </h2>

    @if (session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded-md shadow text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @forelse ($deudores as $deudor)
        @php
            $pendiente = $deudor->facturas->where('estado','Pendiente')->sum('total');
        @endphp
        <div class="bg-white shadow-md rounded-xl p-4 mb-6">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-bold text-gray-800 text-lg">{{ $deudor->nombre }}</p>
                    <p class="text-sm text-gray-500">{{ $deudor->descripcion }}</p>
                    <p class="text-sm text-red-600 font-semibold">
                        💰 Total pendiente: ₡{{ number_format($pendiente,2) }}
                    </p>
                </div>

                <div class="flex items-center space-x-6">
                    <a href="{{ route('deudores.edit', $deudor) }}" class="text-blue-600 hover:text-blue-800 text-xl" title="Editar">✏️</a>
                    <button type="button"
                        onclick="openDeleteModal({{ $deudor->id }}, '{{ $deudor->nombre }}')"
                        class="text-red-600 hover:text-red-800 text-2xl" title="Eliminar">🗑️</button>
                </div>
            </div>

            {{-- Facturas de este deudor --}}
            @if ($deudor->facturas->count() > 0)
                <table class="w-full text-sm text-left mt-3 border">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Fecha</th>
                            <th class="px-3 py-2">Total</th>
                            <th class="px-3 py-2">Estado</th>
                            <th class="px-3 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deudor->facturas as $factura)
                            <tr class="border-t">
                                <td class="px-3 py-2">
                                    <a href="{{ route('facturas.show',['factura'=>$factura->id,'from'=>'deudores']) }}"
                                       class="text-purple-600 hover:underline font-semibold">
                                        #{{ $factura->id }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">{{ $factura->fecha }}</td>
                                <td class="px-3 py-2">₡{{ number_format($factura->total,2) }}</td>
                                <td class="px-3 py-2">
                                    <span class="{{ $factura->estado == 'Pagada' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $factura->estado }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    @if ($factura->estado == 'Pendiente')
                                        <form action="{{ route('facturas.pagar',$factura->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                Marcar pagada
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">✔ Pagada</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-sm mt-2">No hay facturas registradas para este deudor.</p>
            @endif
        </div>
    @empty
        <p class="text-gray-500 text-center">No hay deudores registrados aún.</p>
    @endforelse
</div>

{{-- Botón flotante para crear deudor (verde en esquina) --}}
@push('scripts')
<style>
    .boton-flotante {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        background-color: #16a34a; /* green-600 */
        color: white;
        font-size: 1.75rem;
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        z-index: 9999;
        transition: background-color 0.2s;
    }
    .boton-flotante:hover {
        background-color: #15803d; /* green-700 */
    }
</style>
@endpush

<a href="{{ route('deudores.create') }}" class="boton-flotante" title="Agregar deudor">＋</a>

{{-- Modal eliminar --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirmar eliminación</h3>
        <p id="deleteMessage" class="text-gray-600 mb-4"></p>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</button>
            <form id="deleteForm" method="POST" action="">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, nombre){
    document.getElementById('deleteForm').action = `/deudores/${id}`;
    document.getElementById('deleteMessage').textContent = `¿Seguro que deseas eliminar al deudor "${nombre}"?`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal(){
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
