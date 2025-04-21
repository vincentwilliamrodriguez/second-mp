<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Livewire\Products\Show;
use App\Livewire\ProductsChild;
use App\Livewire\ProductsIndex;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect('/login');
})->name('home');

Route::get('/dashboard', function () {
    return auth()->user()->hasRole('support')
        ? redirect()->route('tickets.index')
        : redirect()->route('products.index');
})->name('dashboard');

Route::get('/tickets', function () {
    return view('tickets.index');
})->name('tickets.index');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::resource('products', ProductController::class)->only(['create', 'store'])
        ->middleware('permission:create-products');

    Route::resource('products', ProductController::class)->only(['edit', 'update'])
        ->middleware('permission:update-products');

    Route::resource('products', ProductController::class)->only(['destroy'])
        ->middleware('permission:delete-products');

    Route::prefix('products')->group(function () {
        Route::get('/', ProductsIndex::class)
            ->middleware('permission:read-products')->name('products.index');

        Route::get('/{product}', ProductsChild::class)
            ->middleware('permission:read-products')->name('products.show');
    });

    Route::resource('orders', OrderController::class)->only(['store'])
        ->middleware('permission:create-orders');

    Route::resource('orders', OrderController::class)->only(['index'])
        ->middleware('permission:read-orders');

    Route::resource('orders', OrderController::class)->only(['update'])
        ->middleware('permission:update-orders');

    Route::resource('orders', OrderController::class)->only(['destroy'])
        ->middleware('permission:delete-orders');

    Route::post('/orders/place-all', [OrderController::class, 'placeAll'])
        ->middleware('permission:update-orders')
        ->name('orders.place-all');

    Route::post('/orders/{order}/quantity', [OrderController::class, 'updateQuantity'])
        ->middleware('permission:update-orders')
        ->name('orders.update-quantity');

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::get('/tickets', [TicketController::class, 'index'])
        ->middleware(['auth', 'role:support|admin'])
        ->name('tickets.index');

    Route::middleware(['auth', 'role:customer|seller'])->group(function () {
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    });

    Route::middleware(['auth', 'role:support|admin'])->group(function () {
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    });
});
