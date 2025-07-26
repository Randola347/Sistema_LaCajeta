@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 font-sans">
    <h2 class="text-lg font-semibold text-purple-700 mb-4">✏️ Editar Proveedor</h2>

    <form method="POST" action="{{ route('proveedores.update', $proveedor) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre" required value="{{ $proveedor->nombre }}"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" value="{{ $proveedor->descripcion }}"
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('proveedores.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md shadow hover:bg-gray-200">
                Cancelar
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-blue rounded-md shadow hover:bg-blue-700">
                Actualizar proveedor
            </button>
        </div>
    </form>
</div>
@endsection
