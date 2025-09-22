<?php

use Dompdf\Adapter\PDFLib;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PedidoPDFController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/manage-productos', \App\Livewire\Productos\ListProductos::class)->name('productos.index');
    Route::get('/edit-producto{record}', \App\Livewire\Productos\EditProducto::class)->name('producto.edit');
    Route::get('/create-producto', \App\Livewire\Productos\CreateProducto::class)->name('producto.create');

    //Route::get('/manage-pedidos', \App\Livewire\Pedidos\ListPedidos::class)->name('pedidos.index');
    //Route::get('productos/create', \App\Livewire\Productos\CreateProducto::class)->name('productos.create');
    //Route::get('productos/{producto}/edit', \App\Livewire\Productos\EditProducto::class)->name('productos.edit');
    Route::get('/pos', \App\Livewire\POS::class)->name('pos');

    Route::get('/pedido/{id}/pdf', [PedidoPDFController::class, 'stream'])->name('pedidos.pdf.stream');
    Route::get('/pedido/{id}/pdf/download', [PedidoPDFController::class, 'download'])->name('pedidos.pdf.download');

});




require __DIR__.'/auth.php';
