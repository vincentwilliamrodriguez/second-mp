<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::redirect('dashboard', 'products');

Route::get('/support', function () {
    return view('tickets.index');
});

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
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::resource('products', ProductController::class)->only(['create', 'store'])
        ->middleware('permission:create-products');

    Route::resource('products', ProductController::class)->only(['index', 'show'])
        ->middleware('permission:read-products');

    Route::resource('products', ProductController::class)->only(['edit', 'update'])
        ->middleware('permission:update-products');

    Route::resource('products', ProductController::class)->only(['destroy'])
        ->middleware('permission:delete-products');



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



    // Route::middleware('permission:read-supports')->group(function () {
    //     Route::resource('support', SupportController::class);
    // });
});
