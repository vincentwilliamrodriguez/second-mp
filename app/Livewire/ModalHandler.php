<?php

namespace App\Livewire;

use Livewire\Component;

class ModalHandler extends Component
{
    public $name;
    public $method;
    public $data = [];
    public $buttonHtml;
    
    public function mount($buttonHtml = null)
    {
        $this->buttonHtml = $buttonHtml;
    }

    public function render()
    {
        return view('livewire.modal-handler');
    }
}
