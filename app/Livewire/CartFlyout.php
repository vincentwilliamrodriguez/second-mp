<?php

namespace App\Livewire;

use App\CartTrait;
use App\Models\Product;
use Livewire\Component;

class CartFlyout extends Component
{
    use CartTrait;

    public $cartItems;
    public $listeners = ['open'];

    public function render()
    {
        $this->cartItems = $this->getUnsortedCart();
        return view('livewire.cart-flyout');
    }

    public function open($method) {
        $this->cartItems = $this->getUnsortedCart();
    }
}
