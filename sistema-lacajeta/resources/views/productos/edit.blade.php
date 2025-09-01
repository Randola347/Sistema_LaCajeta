@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 font-sans">
    <h2 class="text-lg font-semibold text-blue-700 mb-4">✏️ Editar Producto</h2>

    <form method="POST" action="{{ route('productos.update', $producto) }}">
        @csrf
        @method('PUT')

        <!-- Nombre -->
        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre"
                   value="{{ old('nombre', $producto->nombre) }}" required
                   class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion"
                      class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        <!-- Precio -->
        <div class="mb-4">
            <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio"
                   value="{{ old('precio', $producto->precio) }}" required
                   class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Inventario -->
        <div class="mb-4">
            <label for="inventario" class="block text-sm font-medium text-gray-700">Inventario</label>
            <input type="number" name="inventario" id="inventario"
                   value="{{ old('inventario', $producto->inventario) }}" required
                   class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Proveedor -->
        <div class="mb-4">
            <label for="proveedor_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
            <select name="proveedor_id" id="proveedor_id"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Seleccione un proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}"
                            @selected(old('proveedor_id', $producto->proveedor_id) == $proveedor->id)>
                        {{ $proveedor->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('productos.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md shadow hover:bg-gray-200">
                Cancelar
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-blue rounded-md shadow hover:bg-blue-700">
                Actualizar Producto
            </button>
        </div>
    </form>
</div>
@endsection
