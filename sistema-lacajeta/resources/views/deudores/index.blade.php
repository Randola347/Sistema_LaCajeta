@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto font-sans relative">
        <h2 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
            🧾 Gestión de Deudores
        </h2>

        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded-md shadow text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @forelse ($deudores as $deudor)
            <div class="bg-white shadow-md rounded-xl p-4 mb-4 flex justify-between items-start">
                <div>
                    <p class="font-bold text-gray-800 text-lg">{{ $deudor->nombre }}</p>
                    <p class="text-sm text-gray-500">{{ $deudor->descripcion }}</p>
                </div>

                <div class="flex items-center space-x-8">
                    <a href="{{ route('deudores.edit', $deudor) }}" class="text-blue-600 hover:text-blue-800 text-xl" title="Editar">✏️</a>
                    <form action="{{ route('deudores.destroy', $deudor) }}" method="POST" onsubmit="return confirm('¿Eliminar este deudor?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800 text-2xl" title="Eliminar">🗑️</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center">No hay deudores registrados aún.</p>
        @endforelse
    </div>

    @push('scripts')
        <style>
            .boton-flotante {
                position: fixed;
                bottom: 1.5rem;
                right: 1.5rem;
                background-color: #16a34a;
                color: white;
                font-size: 1.75rem;
                width: 3.5rem;
                height: 3.5rem;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                z-index: 9999;
            }
            .boton-flotante:hover {
                background-color: #15803d;
            }
        </style>
    @endpush

    <a href="{{ route('deudores.create') }}" class="boton-flotante" title="Agregar deudor">＋</a>
@endsection
