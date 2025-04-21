<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;


class Table extends Component {
    use WithPagination;

    public $items;
    public $columns;
    public $columnsToProperty = [];
    public $widths;
    public $cells;
    public $cellData = [];

    public $customClasses = [
        'container' => '',
        'table' => '',
        'thead' => '',
        'th' => '',
        'tbody' => '',
        'tr' => '',
        'td' => '',
        'tdNoData' => '',
    ];

    public $columnsWithRowspan = [];
    public $columnsWithSorting = [];
    public $sortBy = '';
    public $sortOrder = '';

    public $noDataText = 'No data available';
    

    public function mount($items, $columns, $widths = [], $columnsToProperty) {
        $this->items = $items;
        $this->columns = $columns;
        $this->widths = $widths;

        foreach ($columns as $column) {
            if (array_key_exists($column, $columnsToProperty)) {
                $this->columnsToProperty[$column] = $columnsToProperty[$column];
            } else {
                $this->columnsToProperty[$column] = Str::snake($column);
            }
        }
    }

    public function render() {
        return view('livewire.table');
    }
}
