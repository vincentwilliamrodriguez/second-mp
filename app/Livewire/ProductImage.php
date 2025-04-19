<?php

namespace App\Livewire;

use Livewire\Component;

class ProductImage extends Component
{
    public $product;
    public $classes;
    public $placeholderSize = 'size-28';

    public function render()
    {
        return view('livewire.product-image');
    }
}
