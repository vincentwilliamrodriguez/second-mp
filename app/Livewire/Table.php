<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component {
    use WithPagination;

    public $items;
    public $columns;
    public $widths;

    public function mount($items) {
        $this->items = $items->items();
    }

    public function render() {
        return view('livewire.table');
    }
}
