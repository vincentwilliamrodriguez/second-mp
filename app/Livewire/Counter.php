<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class Counter extends Component
{
    public $min = 1;
    public $max = 10;

    #[Modelable]
    public $count = 1;


    public function render()
    {
        return view('livewire.counter');
    }
}
