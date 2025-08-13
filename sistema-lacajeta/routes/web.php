<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DeudorController;
use App\Http\Controllers\FacturaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('proveedores', ProveedorController::class);
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/proveedores/undo-delete', [ProveedorController::class, 'undoDelete'])->name('proveedores.undoDelete');
    Route::resource('productos', ProductoController::class)->middleware('auth');
    Route::post('/productos/undo-delete', [ProductoController::class, 'undoDelete'])->name('productos.undoDelete');
    Route::middleware('auth')->group(function () {
        Route::resource('deudores', DeudorController::class)->parameters([
            'deudores' => 'deudor'
        ]);
        Route::get('/facturas/pendientes', [FacturaController::class, 'pendientes'])->name('facturas.pendientes');
    });
    Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
});



require __DIR__ . '/auth.php';
