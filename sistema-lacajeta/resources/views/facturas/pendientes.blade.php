@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto font-sans relative">
    <h2 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
        🧾 Facturas Pendientes
    </h2>

    <!-- Filtro por deudor -->
    <div class="mb-6 flex items-center justify-between">
        <form method="GET" action="{{ route('facturas.pendientes') }}">
            <label for="deudor" class="text-sm font-semibold text-gray-600">Filtrar por deudor:</label>
            <select name="deudor_id" id="deudor" onchange="this.form.submit()"
                class="ml-2 border rounded px-3 py-1 text-sm">
                <option value="">-- Todos --</option>
                @foreach($deudores as $deudor)
                    <option value="{{ $deudor->id }}" {{ request('deudor_id') == $deudor->id ? 'selected' : '' }}>
                        {{ $deudor->nombre }}
                    </option>
                @endforeach
            </select>
        </form>

        <!-- Botón para abrir modal -->
        <button onclick="document.getElementById('modal-deudor').classList.remove('hidden')"
            class="bg-green-600 text-blue px-3 py-2 text-sm rounded-md shadow hover:bg-green-700">
            ＋ Agregar deudor
        </button>
    </div>

    <!-- Lista de facturas pendientes -->
    @forelse ($facturas as $factura)
        <div class="bg-white rounded-lg shadow p-4 mb-4 flex justify-between items-center">
            <div>
                <p class="font-semibold text-gray-800">
                    Factura #{{ $factura->id }}
                </p>
                <p class="text-sm text-gray-500">
                    Deudor: {{ $factura->deudor->nombre ?? 'Sin asignar' }}
                </p>
                <p class="text-sm text-red-600 font-bold">Total: ₡{{ number_format($factura->total, 2) }}</p>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('facturas.edit', $factura) }}" class="text-blue-600 hover:text-blue-800 text-lg">✏️</a>
                <form method="POST" action="{{ route('facturas.destroy', $factura) }}"
                    onsubmit="return confirm('¿Deseas eliminar esta factura?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:text-red-800 text-xl">🗑️</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-500 text-center">No hay facturas pendientes por mostrar.</p>
    @endforelse
</div>

<!-- Modal para agregar deudor -->
<div id="modal-deudor" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Agregar nuevo deudor</h3>
        <form method="POST" action="{{ route('deudores.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" required
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-300">
            </div>
            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                <textarea name="descripcion" id="descripcion" rows="2"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-green-300"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-deudor').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-blue rounded hover:bg-green-700">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
