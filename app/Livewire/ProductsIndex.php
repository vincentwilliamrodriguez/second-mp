<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        $user = auth()->user();
        $products = $user->hasRole('seller')
                        ? $user->products()->orderBy('updated_at', 'DESC')->paginate(8)
                        : Product::orderBy('updated_at', 'DESC')->paginate(8);

        return view('livewire.products-index', compact('products'));
    }
}
