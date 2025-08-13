@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-xl mx-auto font-sans">
        <h2 class="text-xl font-semibold text-purple-700 mb-6">✏️ Editar Deudor</h2>

        <form method="POST" action="{{ route('deudores.update', $deudor) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="w-full border rounded px-3 py-2" value="{{ $deudor->nombre }}" required>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block font-medium text-sm text-gray-700">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="w-full border rounded px-3 py-2">{{ $deudor->descripcion }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('deudores.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-blue rounded hover:bg-blue-700">Actualizar</button>
            </div>
        </form>
    </div>
@endsection
