@forelse($productos as $p)
    <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-3">
        <div>
            <p class="font-semibold text-gray-900 text-base">{{ $p->nombre }}</p>
            @if ($p->descripcion)
                <p class="text-sm text-gray-500 line-clamp-2">{{ $p->descripcion }}</p>
            @endif
            <p class="text-sm text-gray-700 mt-1">
                Precio: <span class="font-semibold">₡{{ number_format($p->precio, 2) }}</span>
            </p>
        </div>

        {{-- Formulario dinámico: carrito o factura --}}
        <form class="form-add-producto mt-auto" data-id="{{ $p->id }}">
            @csrf
            <input type="hidden" name="producto_id" value="{{ $p->id }}">

            <div class="flex items-center gap-2">
                <button type="button"
                    class="qty-minus bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg w-12 h-12 text-2xl">−</button>
                <input type="number" name="cantidad" min="1" value="1"
                    class="w-20 border rounded-lg px-3 py-2 text-center text-lg">
                <button type="button"
                    class="qty-plus bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg w-12 h-12 text-2xl">＋</button>

                <button type="submit"
                    class="ml-auto bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold">
                    Agregar
                </button>
            </div>
        </form>

    </div>
@empty
    <p class="text-gray-500 col-span-full">No hay productos.</p>
@endforelse

{{-- paginación --}}
<div class="mt-6 col-span-full">
    {{ $productos->withQueryString()->links() }}
</div>
