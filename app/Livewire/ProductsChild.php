<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductsChild extends Component
{
    public $quantity = 1;
    public $maxDescriptionLength = 250;
    public Product $product;
    

    public function render()
    {
        return view('livewire.products-child');
    }
}
