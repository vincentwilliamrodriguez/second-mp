<?php

namespace App\Livewire;

use Livewire\Component;

class Testing extends Component
{
    public $product;
    public $quantity = 1;

    public function render()
    {
        return view('livewire.testing');
    }
}
