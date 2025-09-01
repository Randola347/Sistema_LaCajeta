@extends('layouts.app')

@section('content')
    @php
        $cart = session('cart', ['items' => []]);
        $cartCount = collect($cart['items'])->sum('cantidad');
    @endphp

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto font-sans pb-24">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-purple-700">🛒 Ventas — Catálogo</h2>

            {{-- link al carrito en desktop --}}
            <a href="{{ $cartCount > 0 ? route('ventas.cart') : '#' }}"
                id="cart-link-desktop"
                class="hidden sm:inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded relative {{ $cartCount > 0 ? '' : 'opacity-50 cursor-not-allowed' }}">
                Ver carrito →
                <span id="cart-badge-desktop"
                      class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 {{ $cartCount > 0 ? '' : 'hidden' }}">
                    {{ (int) $cartCount }}
                </span>
            </a>
        </div>

        {{-- buscador --}}
        <form method="GET" action="{{ route('ventas.index') }}" class="mb-4 flex gap-2">
            <input type="text" id="search-producto" placeholder="Buscar producto..."
                   class="border rounded px-3 py-2 w-64">
            <div id="resultados" class="mt-2"></div>
        </form>

        {{-- grid de productos --}}
        <div id="productos-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($productos as $p)
                <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-3">
                    <div>
                        <p class="font-semibold text-gray-900 text-base">{{ $p->nombre }}</p>
                        @if ($p->descripcion)
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $p->descripcion }}</p>
                        @endif
                        <p class="text-sm text-gray-700 mt-1">Precio:
                            <span class="font-semibold">₡{{ number_format($p->precio, 2) }}</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('ventas.add') }}" class="form-add mt-auto">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $p->id }}">

                        <div class="flex items-center gap-2">
                            <button type="button" class="qty-minus bg-gray-100 hover:bg-gray-200 w-10 h-10 rounded">−</button>
                            <input type="number" name="cantidad" min="1" value="1" step="1"
                                   class="w-20 border rounded text-center qty-input">
                            <button type="button" class="qty-plus bg-gray-100 hover:bg-gray-200 w-10 h-10 rounded">＋</button>

                            <button type="submit"
                                    class="ml-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                Agregar
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <p class="text-gray-500 col-span-full">No hay productos.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $productos->withQueryString()->links() }}
        </div>
    </div>

    {{-- Footer solo en móvil con botón de carrito --}}
    <div class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow-md z-50">
        <div class="flex justify-center items-center p-3">
            <a href="{{ $cartCount > 0 ? route('ventas.cart') : '#' }}"
               id="cart-link-footer"
               class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-full font-semibold text-base shadow-lg {{ $cartCount > 0 ? '' : 'opacity-50 cursor-not-allowed' }}">
                {{-- ícono carrito --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 fill-current">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10
                             0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zM7.16
                             14.26l.03.01L19 14c.55 0 1-.45 1-1 0-.09-.01-.18-.04-.26l-2-7A1
                             1 0 0 0 17 5H6.21l-.2-1.01A1 1 0 0 0 5.03 3H3a1 1 0 1 0 0
                             2h1.22l2.03 10.14A2.996 2.996 0 0 0 7 18h12a1 1 0 1 0
                             0-2H7a1 1 0 0 1-.84-1.74z" />
                </svg>
                Carrito
                <span id="cart-badge-footer"
                      class="ml-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 {{ $cartCount > 0 ? '' : 'hidden' }}">
                    {{ (int) $cartCount }}
                </span>
            </a>
        </div>
    </div>

    <script>
        // Stepper
        document.addEventListener('click', e => {
            if (e.target.classList.contains('qty-minus') || e.target.classList.contains('qty-plus')) {
                const wrap = e.target.closest('div');
                const input = wrap.querySelector('.qty-input');
                let v = parseInt(input.value || '1', 10);
                if (e.target.classList.contains('qty-minus')) input.value = Math.max(1, v - 1);
                else input.value = v + 1;
            }
        });

        // 🔹 Función global para actualizar badges y habilitar/deshabilitar enlaces
        function updateBadges(count) {
            const bd = document.getElementById('cart-badge-desktop');
            const bm = document.getElementById('cart-badge-mobile');
            const bf = document.getElementById('cart-badge-footer');
            const linkDesktop = document.getElementById('cart-link-desktop');
            const linkFooter = document.getElementById('cart-link-footer');

            [bd, bm, bf].forEach(el => {
                if (el) {
                    if (count > 0) {
                        el.classList.remove('hidden');
                        el.textContent = count;
                    } else el.classList.add('hidden');
                }
            });

            [linkDesktop, linkFooter].forEach(el => {
                if (el) {
                    if (count > 0) {
                        el.href = "{{ route('ventas.cart') }}";
                        el.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        el.href = "#";
                        el.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            });
        }

        // AJAX add al carrito
        document.querySelectorAll('.form-add').forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const fd = new FormData(form);
                const r = await fetch("{{ route('ventas.ajaxAdd') }}", {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: fd
                });
                const d = await r.json();
                if (d.ok) updateBadges(d.count); // ✅ actualiza desktop + móvil + footer
            });
        });

        // búsqueda en tiempo real
        document.getElementById('search-producto').addEventListener('input', async e => {
            const q = e.target.value;
            const r = await fetch(`{{ route('ventas.index') }}?q=${encodeURIComponent(q)}`, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            const html = await r.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('productos-grid');
            if (newGrid) document.getElementById('productos-grid').innerHTML = newGrid.innerHTML;
        });
    </script>
@endsection
