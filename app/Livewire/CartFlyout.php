<?php

namespace App\Livewire;

use App\CartTrait;
use App\Models\Product;
use Livewire\Component;

class CartFlyout extends Component
{
    use CartTrait;

    public $cartItems;

    public function render()
    {
        return view('livewire.cart-flyout');
    }
}
