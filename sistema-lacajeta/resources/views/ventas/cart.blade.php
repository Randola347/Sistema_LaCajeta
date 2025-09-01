@extends('layouts.app')

@section('content')
@php
    $items = $cart['items'] ?? [];
@endphp

<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto font-sans">
    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-purple-700">🧺 Carrito</h2>

        <div class="flex gap-2">
            <a href="{{ route('ventas.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded">← Volver a ventas</a>
            <form method="POST" action="{{ route('ventas.clear') }}">
                @csrf @method('DELETE')
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">
                    Vaciar
                </button>
            </form>
        </div>
    </div>

    {{-- Mensajes --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @error('carrito')
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4">{{ $message }}</div>
    @enderror

    {{-- Items --}}
    @forelse($items as $line)
        <div class="bg-white rounded-xl shadow p-4 mb-3 flex flex-col gap-3" id="item-{{ $line['producto_id'] }}" data-id="{{ $line['producto_id'] }}">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-900">{{ $line['nombre'] }}</p>
                    <p class="text-sm text-gray-600">₡{{ number_format($line['precio'],2) }}</p>
                </div>
                <button class="btn-remove text-red-600 hover:text-red-800" data-id="{{ $line['producto_id'] }}">🗑️</button>
            </div>

            <div class="flex justify-between items-center">
                {{-- Stepper --}}
                <div class="flex items-center gap-2">
                    <button type="button" class="qty-minus bg-gray-100 hover:bg-gray-200 w-10 h-10 rounded">−</button>
                    <input type="number" value="{{ $line['cantidad'] }}" class="qty-input w-20 border rounded text-center">
                    <button type="button" class="qty-plus bg-gray-100 hover:bg-gray-200 w-10 h-10 rounded">＋</button>
                </div>
                <div>
                    @php $sub = $line['precio'] * $line['cantidad']; @endphp
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p id="subtotal-{{ $line['producto_id'] }}" class="font-semibold">₡{{ number_format($sub,2) }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">Tu carrito está vacío.</div>
    @endforelse

    {{-- Barra inferior --}}
    <div class="fixed left-0 right-0 bottom-0 sm:static bg-white sm:bg-transparent border-t sm:border-0">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center gap-2">
            <div class="text-lg font-semibold text-gray-900">
                Total: <span id="total-amount">₡{{ number_format($total,2) }}</span>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button onclick="document.getElementById('modal-checkout').classList.remove('hidden')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Crear factura
                </button>

                @if (!empty($facturaId))
                    <a href="{{ route('facturas.show',$facturaId) }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                       Ver factura #{{ $facturaId }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal checkout --}}
<div id="modal-checkout" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Crear factura</h3>
        <form method="POST" action="{{ route('ventas.checkout') }}">
            @csrf
            <input type="hidden" name="fecha" value="{{ now()->format('Y-m-d') }}">

            <div class="space-y-3">
                <div>
                    <label class="font-semibold text-gray-700">Modo</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <label class="border rounded p-2 flex items-center gap-2">
                            <input type="radio" name="modo" value="sin_deudor" checked> <span>Sin deudor</span>
                        </label>
                        <label class="border rounded p-2 flex items-center gap-2">
                            <input type="radio" name="modo" value="deudor_existente"> <span>Existente</span>
                        </label>
                        <label class="border rounded p-2 flex items-center gap-2">
                            <input type="radio" name="modo" value="deudor_nuevo"> <span>Nuevo</span>
                        </label>
                    </div>
                </div>

                {{-- Selector de deudor --}}
                <div id="boxExistente" class="hidden">
                    <label class="block text-sm">Seleccionar deudor</label>
                    <select name="deudor_id" class="w-full border rounded px-2 py-1">
                        @foreach ($deudores as $d)
                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Crear deudor nuevo --}}
                <div id="boxNuevo" class="hidden">
                    <input type="text" name="nuevo_nombre" placeholder="Nombre" class="w-full border rounded px-2 py-1 mb-2">
                    <input type="text" name="nuevo_descripcion" placeholder="Descripción" class="w-full border rounded px-2 py-1">
                </div>

                {{-- Forma de pago --}}
                <div id="boxPago">
                    <label class="block text-sm">Forma de pago</label>
                    <select name="forma_pago" class="w-full border rounded px-2 py-1">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="sinpe">SINPE</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-checkout').classList.add('hidden')"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Crear
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JS para stepper y AJAX --}}
<script>
document.addEventListener('click', async e => {
    // Stepper
    if (e.target.classList.contains('qty-minus') || e.target.classList.contains('qty-plus')) {
        const wrap = e.target.closest('[data-id]');
        const pid = wrap.dataset.id;
        const input = wrap.querySelector('.qty-input');
        let v = parseInt(input.value || '0',10);
        v = e.target.classList.contains('qty-minus') ? Math.max(0,v-1) : v+1;
        input.value = v;

        const r = await fetch(`{{ url('/ventas/qty') }}/${pid}`, {
            method:'PATCH',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'},
            body: JSON.stringify({cantidad:v})
        });
        const d = await r.json();
        if(d.ok){
            if(!d.exists){ wrap.remove(); }
            else document.getElementById(`subtotal-${pid}`).textContent = '₡'+d.lineTotal.toFixed(2);
            document.getElementById('total-amount').textContent = '₡'+d.total.toFixed(2);
            updateBadges(d.count);
        }
    }

    // Eliminar producto
    if(e.target.classList.contains('btn-remove')){
        const pid = e.target.dataset.id;
        const r = await fetch(`{{ url('/ventas/remove') }}/${pid}`, {
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
        });
        const d = await r.json();
        if(d.ok){
            document.getElementById(`item-${pid}`)?.remove();
            document.getElementById('total-amount').textContent = '₡'+d.total.toFixed(2);
            updateBadges(d.count);
        }
    }
});

// 🔹 función para actualizar badges (desktop + móvil)
function updateBadges(count){
    const bd = document.getElementById('cart-badge-desktop');
    const bm = document.getElementById('cart-badge-mobile');
    if(bd){
        if(count>0){ bd.classList.remove('hidden'); bd.textContent=count; }
        else bd.classList.add('hidden');
    }
    if(bm){
        if(count>0){ bm.classList.remove('hidden'); bm.textContent=count; }
        else bm.classList.add('hidden');
    }
}
</script>
@endsection
