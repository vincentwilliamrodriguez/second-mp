<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
    return view('support.index');
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



    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('permission:read-tickets')->group(function () {
        Route::resource('tickets', TicketController::class);
    });
});
