<?php

namespace App\Livewire;

use Livewire\Component;

class CartButton extends Component {
    public $cartItems = [];
    public $cartCount = 0;

    public function mount() {
        if (auth()->check()) {
            $this->cartItems = session('cart.' . auth()->id(), []);
            $this->cartCount = count($this->cartItems);
        }
    }

    public function render() {
        return view('livewire.cart-button');
    }
}
