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
    public $slots = [];
    public $cells;

    public $columnsWithSorting = [];
    public $sort = '';
    public $sortOrder = '';

    public function mount($items, $columns, $widths = []) {
        $this->items = $items;
        $this->columns = $columns;
        $this->widths = $widths;
    }

    public function render() {
        return view('livewire.table');
    }
}
