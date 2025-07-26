<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;

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
});



require __DIR__.'/auth.php';
