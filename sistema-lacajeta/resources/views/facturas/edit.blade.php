@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-xl font-semibold text-purple-700 mb-4">✏️ Editar Factura #{{ $factura->id }}</h2>

    <form method="POST" action="{{ route('facturas.update', $factura->id) }}">
        @csrf @method('PUT')

        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <p><b>Fecha:</b> <input type="text" value="{{ $factura->fecha }}" readonly class="border rounded px-3 py-1 w-40"></p>

            <label class="block mt-3">Deudor:</label>
            <select name="deudor_id" class="border rounded px-3 py-2 w-full">
                <option value="">Sin deudor</option>
                @foreach($deudores as $d)
                <option value="{{ $d->id }}" @selected($factura->deudor_id == $d->id)>{{ $d->nombre }}</option>
                @endforeach
            </select>

            <label class="block mt-3">Estado:</label>
            <select name="estado" class="border rounded px-3 py-2 w-full">
                <option value="Pagada" @selected($factura->estado == 'Pagada')>Pagada</option>
                <option value="Pendiente" @selected($factura->estado == 'Pendiente')>Pendiente</option>
            </select>

            <label class="block mt-3">Forma de pago:</label>
            <select name="forma_pago" class="border rounded px-3 py-2 w-full">
                <option value="">N/A</option>
                <option value="efectivo" @selected($factura->forma_pago == 'efectivo')>Efectivo</option>
                <option value="tarjeta" @selected($factura->forma_pago == 'tarjeta')>Tarjeta</option>
                <option value="sinpe" @selected($factura->forma_pago == 'sinpe')>SINPE</option>
            </select>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold mb-3">Productos</h3>
            @foreach($factura->detalles as $d)
            <div id="item-{{ $d->id }}" class="flex justify-between items-center border-b py-2" data-id="{{ $d->id }}">
                <div>
                    <p class="font-medium">{{ $d->producto->nombre ?? 'Eliminado' }}</p>
                    <p class="text-sm text-gray-600">₡{{ number_format($d->precio_unitario,2) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="qty-minus bg-gray-200 px-3 py-1 rounded">−</button>
                    <input type="number" value="{{ $d->cantidad }}" min="0"
                           class="qty-input border rounded px-2 py-1 w-16 text-center"
                           data-id="{{ $d->id }}">
                    <button type="button" class="qty-plus bg-gray-200 px-3 py-1 rounded">＋</button>
                </div>
                <div class="font-semibold" id="subtotal-{{ $d->id }}">
                    ₡{{ number_format($d->subtotal,2) }}
                </div>
                <button type="button" class="btn-remove text-red-600" data-id="{{ $d->id }}">🗑️</button>
            </div>
            @endforeach
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">💾 Guardar cambios</button>
        </div>
    </form>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const fmt = n => '₡' + Number(n).toLocaleString(undefined,{minimumFractionDigits:2});

async function updateQty(detalleId, cantidad) {
    const r = await fetch(`{{ url('/facturas/'.$factura->id.'/ajax-update-detalle') }}/${detalleId}`, {
        method: 'PATCH',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':token},
        body: JSON.stringify({cantidad})
    });
    return r.json();
}
async function removeDetalle(detalleId) {
    const r = await fetch(`{{ url('/facturas/'.$factura->id.'/ajax-remove-detalle') }}/${detalleId}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN':token}
    });
    return r.json();
}

document.addEventListener('click', async e => {
    if (e.target.classList.contains('qty-minus') || e.target.classList.contains('qty-plus')) {
        const input = e.target.closest('[data-id]').querySelector('.qty-input');
        let v = parseInt(input.value || '0',10);
        v = e.target.classList.contains('qty-minus') ? Math.max(0,v-1) : v+1;
        input.value = v;
        const d = await updateQty(input.dataset.id, v);
        if (!d.exists) document.getElementById(`item-${input.dataset.id}`).remove();
        else document.getElementById(`subtotal-${input.dataset.id}`).textContent = fmt(d.lineTotal);
    }
    if (e.target.classList.contains('btn-remove')) {
        const id = e.target.dataset.id;
        if (!confirm('¿Eliminar producto?')) return;
        const d = await removeDetalle(id);
        if (d.ok) document.getElementById(`item-${id}`).remove();
    }
});
</script>
@endsection
