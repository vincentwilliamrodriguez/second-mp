<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\OrdersIndex;
use App\Livewire\Products\Show;
use App\Livewire\ProductsChild;
use App\Livewire\ProductsIndex;
use App\Livewire\TicketsIndex;
use App\Livewire\TicketsCreate;
use App\Livewire\UsersIndex;
use App\Livewire\UserCreate;
use App\Livewire\UserEdit;
use App\Livewire\UserShow;
use App\Models\Product;
use App\Models\User;
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
})->middleware('auth')->name('tickets.index');

// Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');


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

    // These are the old route definitions for the Products, Orders, Users, and Tickets page that used ProductController

    // Route::resource('products', ProductController::class)->only(['create', 'store'])
        // ->middleware('permission:create-products');

    // Route::resource('products', ProductController::class)->only(['index', 'show'])
    //     ->middleware('permission:read-products');

    // Route::resource('products', ProductController::class)->only(['edit', 'update'])
    //     ->middleware('permission:update-products');

    // Route::resource('products', ProductController::class)->only(['destroy'])
        // ->middleware('permission:delete-products');


    // Route::resource('orders', OrderController::class)->only(['store'])
    //     ->middleware('permission:create-orders');

    // Route::resource('orders', OrderController::class)->only(['index'])
    //     ->middleware('permission:read-orders');

    // Route::resource('orders', OrderController::class)->only(['update'])
    //     ->middleware('permission:update-orders');

    // Route::resource('orders', OrderController::class)->only(['destroy'])
    //     ->middleware('permission:delete-orders');

    // Route::post('/orders/place-all', [OrderController::class, 'placeAll'])
    //     ->middleware('permission:update-orders')
    //     ->name('orders.place-all');

    // Route::post('/orders/{order}/quantity', [OrderController::class, 'updateQuantity'])
    //     ->middleware('permission:update-orders')
    //     ->name('orders.update-quantity');


    // Route::resource('tickets', TicketController::class)->only(['create', 'store'])
    //     ->middleware('permission:create-tickets');

    // Route::resource('tickets', TicketController::class)->only(['index'])
    //     ->middleware('permission:read-tickets');

    // Route::resource('tickets', TicketController::class)->only(['edit', 'update'])
    //     ->middleware('permission:update-tickets');

    // Route::resource('tickets', TicketController::class)->only(['destroy'])
    //     ->middleware('permission:delete-tickets');



    // These are the new route definition for the Products, Orders, Users, and Tickets page using Livewire

    Route::get('products', ProductsIndex::class)
        ->middleware('permission:read-products')
        ->name('products.index');

    Route::get('orders', OrdersIndex::class)
        ->middleware('permission:read-orders')
        ->name('orders.index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });


    // These are newly added routes for the Shopping Cart and Checkout pages

    Route::middleware('permission:create-orders')->group(function () {
        Route::get('cart', Cart::class)->name('cart');
        Route::get('checkout', Checkout::class)->name('checkout');
    });


    Route::resource('tickets', TicketController::class)->only(['destroy'])
        ->middleware('permission:delete-tickets');

    // These are newly added routes for the Shopping Cart and Checkout pages

    Route::middleware('permission:create-orders')->group(function () {
        Route::get('cart', Cart::class)->name('cart');
        Route::get('checkout', Checkout::class)->name('checkout');
    });
});
