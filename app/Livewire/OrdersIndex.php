<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination, WithoutUrlPagination;



    public function render() {
        $orders = $this->fetchOrders()->paginate(15);

        return view('livewire.orders-index', compact('orders'));
    }

    public function fetchOrders() {
        return Order::query();
    }
}
