<?php

namespace App\Livewire;

use Livewire\Component;

class WalletButton extends Component {
    public $balance = 0;

    public function mount() {
        $this->balance = auth()->user()?->balance ?? 0;
    }

    public function render() {
        return view('livewire.wallet-button');
    }

    public function addFunds() {
        $user = auth()->user();
        $user->balance += 1000;
        $user->save();
        
        $this->balance = $user->balance;
    }
}
