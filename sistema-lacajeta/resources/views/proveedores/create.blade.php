@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 font-sans">
    <h2 class="text-lg font-semibold text-purple-700 mb-4">➕ Agregar Proveedor</h2>

    <form method="POST" action="{{ route('proveedores.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre" required
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-6">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <input type="text" name="descripcion" id="descripcion"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('proveedores.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md shadow hover:bg-gray-200">
                Cancelar
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-blue rounded-md shadow hover:bg-green-700">
                Guardar proveedor
            </button>
        </div>
    </form>
</div>
@endsection
