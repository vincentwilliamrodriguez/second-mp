<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;

class OrdersChildConfirm extends Component
{
    public function render()
    {
        return view('livewire.orders-child-confirm');
    }

    public $order;

    public $state;
    public $method;

    protected $listeners = ['open', 'close'];


    public function open($method, $orderId = null) {
        $this->state = null;

        if ($orderId) {
            $this->order = Order::findOrFail($orderId);
        }


        switch ($method) {
            case 'Accept All':
            case 'Cancel All':
                $this->authorize('update', $this->order);
                break;
        }

        $this->state = $method;
    }

    public function close() {
        $this->reset('state');
        $this->resetErrorBag();
    }

    public function updateAll() {
        $this->authorize('update', $this->order);
        $value = ($this->state === 'Accept All') ? 'accepted' : 'cancelled';

        foreach ($this->order->orderItemsWrapper() as $item) {
            if ($item->status === 'pending') {
                if ($value === 'accepted') {
                    $this->dispatch('acceptItem', $item->id);
                } else {
                    $this->dispatch('cancelItem', $item->id);
                }
            }
        }

        session()->flash('message', ($value === 'accepted') ? 'Accepted all pending items successfully.' : 'Cancelled all pending items successfully.');
        // $this->redirectRoute('orders.index');
        $this->modal('orders-child-confirm')->close();
    }


}
