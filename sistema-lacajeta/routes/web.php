<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DeudorController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /** Perfil */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    /** Proveedores */
    Route::resource('proveedores', ProveedorController::class);
    Route::post('/proveedores/undo-delete', [ProveedorController::class, 'undoDelete'])->name('proveedores.undoDelete');

    /** Productos */
    Route::resource('productos', ProductoController::class);
    Route::post('/productos/undo-delete', [ProductoController::class, 'undoDelete'])->name('productos.undoDelete');

    /** Deudores */
    Route::resource('deudores', DeudorController::class)
        ->parameters(['deudores' => 'deudor']);


    /** Facturas */
    Route::get('/facturas/pendientes', [FacturaController::class, 'pendientes'])->name('facturas.pendientes');
    Route::resource('facturas', FacturaController::class)->except(['create', 'store']);
    // usamos index, edit, update, destroy, show

    // AJAX facturas
    Route::patch('/facturas/{factura}/ajax-update-detalle/{detalle}', [FacturaController::class, 'ajaxUpdateDetalle'])
        ->name('facturas.ajaxUpdateDetalle');
    Route::delete('/facturas/{factura}/ajax-remove-detalle/{detalle}', [FacturaController::class, 'ajaxRemoveDetalle'])
        ->name('facturas.ajaxRemoveDetalle');
    Route::post('/facturas/{factura}/ajax-add-producto', [FacturaController::class, 'ajaxAddProducto'])
        ->name('facturas.ajaxAddProducto');

    /** Ventas */
    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
    Route::post('/ventas/add', [VentasController::class, 'add'])->name('ventas.add');
    Route::get('/ventas/cart', [VentasController::class, 'cart'])->name('ventas.cart');
    Route::post('/ventas/update', [VentasController::class, 'update'])->name('ventas.update');
    Route::delete('/ventas/remove/{id}', [VentasController::class, 'remove'])->name('ventas.remove');
    Route::delete('/ventas/clear', [VentasController::class, 'clear'])->name('ventas.clear');
    Route::post('/ventas/checkout', [VentasController::class, 'checkout'])->name('ventas.checkout');

    // Ventas AJAX (mejor tener uno claro en lugar de repetidos)
    Route::patch('/ventas/qty/{producto}', [VentasController::class, 'qty'])->name('ventas.qty');
    Route::post('/ventas/ajax-add', [VentasController::class, 'ajaxAdd'])->name('ventas.ajaxAdd');
    // Actualizar productos de una factura desde el carrito (modo edición)
    Route::put('/facturas/{factura}/productos', [FacturaController::class, 'updateProductos'])
        ->name('facturas.updateProductos');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
    Route::patch('/facturas/{id}/pagar', [DeudorController::class, 'marcarPagada'])->name('facturas.pagar');
    Route::get('/facturas/{id}/imprimir', [FacturaController::class, 'imprimir'])
        ->name('facturas.imprimir');
});

require __DIR__ . '/auth.php';
