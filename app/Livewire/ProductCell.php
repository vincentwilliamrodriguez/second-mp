<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductCell extends Component
{
    public Product $product;

    public function render()
    {
        return view('livewire.product-cell');
    }
}
