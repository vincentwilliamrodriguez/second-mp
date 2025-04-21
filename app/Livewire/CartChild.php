<?php

namespace App\Livewire;

use App\CartTrait;
use Livewire\Component;

class CartChild extends Component
{
    use CartTrait;

    public $item;

    public $state;
    public $method;

    protected $listeners = ['open', 'close'];


    public function render()
    {
        return view('livewire.cart-child');
    }

    public function open($method, $itemId = null) {
        $this->state = null;

        if ($itemId) {
            $this->item = $this->retrieveItemById($itemId);
        }

        switch ($method) {
            case 'Delete':
                // $this->authorize('delete', $this->item);
                break;

            case 'Clear':
                // $this->authorize('delete', $this->item);
                break;
        }

        $this->state = $method;
    }

    public function close() {
        $this->reset('state');
        $this->resetErrorBag();
    }

    public function deleteItem() {
        // $this->authorize('delete', $this->product);
        $this->deleteItemFromCart($this->item['id']);

        session()->flash('message', 'Item removed from cart successfully.');
        $this->redirectRoute('cart');
    }

    public function clearCart() {
        // $this->authorize('delete', $this->product);
        $this->clearAllCartItems();

        session()->flash('message', 'Cart cleared successfully.');
        $this->redirectRoute('cart');
    }
}
