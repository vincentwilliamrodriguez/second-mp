<?php

namespace App\Livewire;

use App\CartTrait;
use Livewire\Component;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Cart extends Component
{
    use CartTrait;
    use WithPagination, WithoutUrlPagination;


    public function render()
    {
        $cartItems = $this->getSortedCart();

        return view('livewire.cart', compact('cartItems'));
    }
    // public function updateQuantity($orderId, $quantity)
    // {
    //     $cartItems = session('cart.' . auth()->id(), []);

    //     foreach ($cartItems as $key => $item) {
    //         if ($item['id'] == $orderId) {
    //             $cartItems[$key]['quantity'] = $quantity;
    //             break;
    //         }
    //     }

    //     session(['cart.' . auth()->id() => $cartItems]);

    //     $this->dispatch('cart-updated');
    // }

    // public function removeFromCart($orderId)
    // {
    //     $cartItems = session('cart.' . auth()->id(), []);

    //     foreach ($cartItems as $key => $item) {
    //         if ($item['id'] == $orderId) {
    //             unset($cartItems[$key]);
    //             break;
    //         }
    //     }

    //     session(['cart.' . auth()->id() => $cartItems]);

    //     $this->dispatch('cart-updated');
    // }
}
