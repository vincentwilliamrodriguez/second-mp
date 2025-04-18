<?php

namespace App\Livewire;

use App\Models\Product;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductsChild extends Component {
    use WithFileUploads;

    public Product $product;

    public $state;
    public $item = [];

    public $orderQuantity = 1;
    public $maxDescriptionLength = 250;

    public $categories;
    public $categoryValues;

    protected $listeners = ['openShow', 'openCreate', 'openEdit', 'resetForm'];


    public function render() {
        return view('livewire.products-child');
    }

    public function resetForm() {
        $this->reset('state', 'item', 'orderQuantity');
        $this->resetErrorBag();
    }

    public function openShow($productId) {
        $this->state = null;
        $this->product = Product::findOrFail($productId);
        $this->state = 'Show';
    }

    public function openCreate() {
        $this->state = null;
        $this->state = 'Create';
    }

    public function openEdit($productId) {
        $this->state = null;
        $this->product = Product::findOrFail($productId);
        $this->item = $this->product->only(['name', 'description', 'quantity', 'price', 'category', 'picture']);
        $this->state = 'Edit';
    }
}
