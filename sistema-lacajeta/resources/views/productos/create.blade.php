@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 font-sans">
    <h2 class="text-lg font-semibold text-green-700 mb-4">➕ Agregar Producto</h2>

    <form method="POST" action="{{ route('productos.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" class="w-full px-3 py-2 border rounded-md focus:ring-green-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" class="w-full px-3 py-2 border rounded-md focus:ring-green-500"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Precio</label>
            <input type="number" name="precio" step="0.01" class="w-full px-3 py-2 border rounded-md focus:ring-green-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Inventario</label>
            <input type="number" name="inventario" class="w-full px-3 py-2 border rounded-md focus:ring-green-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Proveedor</label>
            <select name="proveedor_id" class="w-full px-3 py-2 border rounded-md focus:ring-green-500" required>
                <option value="">Seleccione un proveedor</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('productos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md shadow hover:bg-gray-200">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-blue rounded-md shadow hover:bg-green-700">Guardar</button>
        </div>
    </form>
</div>
@endsection
