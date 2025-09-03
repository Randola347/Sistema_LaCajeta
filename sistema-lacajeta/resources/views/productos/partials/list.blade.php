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
