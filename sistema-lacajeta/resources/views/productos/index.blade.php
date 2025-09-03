@extends('layouts.app')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto font-sans relative">
        <h2 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
            📦 Gestión de Productos
        </h2>

        <!-- Mensajes -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded-md shadow text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('deleted'))
            <div class="mb-4 bg-red-100 text-red-800 px-4 py-2 rounded-md shadow text-sm flex justify-between items-center">
                <span>🗑️ Se ha eliminado el producto <strong>{{ session('deleted') }}</strong>.</span>
                <form method="POST" action="{{ route('productos.undoDelete') }}">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:underline text-sm">Deshacer</button>
                </form>
            </div>
        @endif

        <!-- Buscador -->
        <div class="mb-4">
            <input type="text" id="search-producto" placeholder="Buscar producto..."
                   class="border rounded px-3 py-2 w-full sm:w-80">
        </div>

        <!-- Lista de productos -->
        <div id="productos-list">
            @forelse ($productos as $producto)
                <div class="bg-white shadow-md rounded-xl p-4 mb-4 flex justify-between items-start">
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ $producto->nombre }}</p>
                        <p class="text-sm text-gray-500">{{ $producto->descripcion }}</p>
                        <p class="text-sm text-gray-500">💲 Precio: ₡{{ number_format($producto->precio, 2) }}</p>
                        <p class="text-sm text-gray-500">📦 Inventario: {{ $producto->inventario }}</p>
                        <p class="text-sm text-gray-500">👤 Proveedor: {{ $producto->proveedor->nombre }}</p>
                    </div>

                    <div class="flex items-center space-x-8">
                        <a href="{{ route('productos.edit', $producto) }}" class="text-blue-600 hover:text-blue-800 text-xl"
                           title="Editar producto">✏️</a>

                        <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                              onsubmit="return confirm('¿Deseas eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800 text-2xl" title="Eliminar producto">🗑️</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">No hay productos registrados aún.</p>
            @endforelse
        </div>
    </div>

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
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                z-index: 9999;
            }

            .boton-flotante:hover {
                background-color: #15803d; /* green-700 */
            }
        </style>
    @endpush

    <!-- Botón flotante igual al de proveedores -->
    <a href="{{ route('productos.create') }}" class="boton-flotante" title="Agregar producto">＋</a>

    {{-- Script buscador en tiempo real --}}
    <script>
        document.getElementById('search-producto').addEventListener('input', async e => {
            const q = e.target.value;
            const r = await fetch(`{{ route('productos.index') }}?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await r.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newList = doc.getElementById('productos-list');
            if (newList) document.getElementById('productos-list').innerHTML = newList.innerHTML;
        });
    </script>
@endsection
