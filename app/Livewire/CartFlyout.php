<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CartFlyout extends Component
{
    public $cartItems;

    public function render()
    {
        return view('livewire.cart-flyout');
    }

    public function retrieveProduct($cartItem) {
        return Product::where('id', $cartItem['product_id'])->first();
    }
}
