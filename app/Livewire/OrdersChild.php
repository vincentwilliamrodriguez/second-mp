<?php

namespace App\Livewire;

use App\Jobs\MarkOrderItemAsShipped;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Number;
use Livewire\Component;
use Illuminate\Support\Carbon;

class OrdersChild extends Component
{
    public Order $order;
    public $orderItems = [];
    public $state = null;
    public $confirmationType = null;
    public $selectedItems = [];
    public $totalPendingPrice = 0;
    public $totalPrice = 0;

    protected $listeners = ['open', 'close', 'acceptItem', 'cancelItem'];


    // Table data
    public $columns;
    public $widths;
    public $columnsToProperty;
    public $customClasses;
    public $noDataText;
    public $cells;
    public $cellData;


    public function close()
    {
        $this->reset('state', 'confirmationType', 'selectedItems', 'totalPendingPrice', 'totalPrice');
        $this->resetErrorBag();
    }

    public function open($method, $orderId = null)
    {
        $this->state = null;

        if ($orderId) {
            $this->order = Order::with(['orderItems.product', 'customer'])->findOrFail($orderId);
            $this->orderItems = $this->order->orderItems->toArray();
            $this->calculatePrices();
        }

        switch ($method) {
            case 'Show':
                $this->authorize('view', $this->order);
                break;

            case 'Edit':
                $this->authorize('update', $this->order);
                $this->updateTableData($this->order->orderItems);
                break;

            case 'Delete':
                $this->authorize('delete', $this->order);
                break;
        }

        $this->state = $method;
    }
    public function render()
    {
        return view('livewire.orders-child');
    }

    public function calculatePrices()
    {
        $this->totalPendingPrice = 0;
        $this->totalPrice = 0;

        foreach ($this->orderItems as $item) {
            $itemTotal = $item['order_quantity'] * $item['product_price'];
            $this->totalPrice += $itemTotal;

            if ($item['status'] === 'pending') {
                $this->totalPendingPrice += $itemTotal;
            }
        }
    }

    public function updateItemStatus($itemId, $newStatus)
    {
        $orderItem = OrderItem::findOrFail($itemId);
        $this->authorize('update', $orderItem);

        // Only allow appropriate status changes
        $allowedStatusChanges = [
            'pending' => ['accepted', 'cancelled'],
            'accepted' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $allowedStatusChanges[$orderItem->status])) {
            $this->addError('status', 'Invalid status transition');
            return;
        }

        // Update status and date fields
        $orderItem->status = $newStatus;

        switch ($newStatus) {
            case 'accepted':
                $orderItem->date_accepted = Carbon::now();
                break;
            case 'shipped':
                $orderItem->date_shipped = Carbon::now();
                break;
            case 'delivered':
                $orderItem->date_delivered = Carbon::now();
                break;
            case 'cancelled':
                // Find first null date and set status there
                if ($orderItem->date_accepted === null) {
                    $orderItem->date_placed = null;
                } elseif ($orderItem->date_shipped === null) {
                    $orderItem->date_accepted = null;
                } elseif ($orderItem->date_delivered === null) {
                    $orderItem->date_shipped = null;
                }
                break;
        }

        $orderItem->save();

        // Refresh the order items
        $this->order = Order::with(['orderItems.product', 'customer'])->findOrFail($this->order->id);
        $this->orderItems = $this->order->orderItems->toArray();
        $this->calculatePrices();

        session()->flash('message', "Order item status updated to $newStatus");
    }

    public function acceptAllPending()
    {
        $this->authorize('update', $this->order);

        $pendingItems = OrderItem::where('order_id', $this->order->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingItems as $item) {
            $item->status = 'accepted';
            $item->date_accepted = Carbon::now();
            $item->save();
        }

        // Refresh the order items
        $this->order = Order::with(['orderItems.product', 'customer'])->findOrFail($this->order->id);
        $this->orderItems = $this->order->orderItems->toArray();
        $this->calculatePrices();

        session()->flash('message', 'All pending items have been accepted');
    }

    public function confirmCancelAllPending()
    {
        $this->authorize('update', $this->order);

        $pendingItems = OrderItem::where('order_id', $this->order->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingItems as $item) {
            $item->status = 'cancelled';
            $item->date_placed = null;
            $item->save();
        }

        // Refresh the order items
        $this->order = Order::with(['orderItems.product', 'customer'])->findOrFail($this->order->id);
        $this->orderItems = $this->order->orderItems->toArray();
        $this->calculatePrices();

        $this->confirmationType = null;
        session()->flash('message', 'All pending items have been cancelled');
    }

    public function cancelOrder()
    {
        $this->confirmationType = 'CancelOrder';
    }

    public function confirmCancelOrder()
    {
        $this->authorize('update', $this->order);

        $orderItems = OrderItem::where('order_id', $this->order->id)->get();

        foreach ($orderItems as $item) {
            $item->status = 'cancelled';
            // Set the first null date field
            if ($item->date_accepted === null) {
                $item->date_placed = null;
            } elseif ($item->date_shipped === null) {
                $item->date_accepted = null;
            } elseif ($item->date_delivered === null) {
                $item->date_shipped = null;
            }
            $item->save();
        }

        // Refresh the order items
        $this->order = Order::with(['orderItems.product', 'customer'])->findOrFail($this->order->id);
        $this->orderItems = $this->order->orderItems->toArray();
        $this->calculatePrices();

        $this->confirmationType = null;
        session()->flash('message', 'Order has been cancelled');

        // Return to index page after full cancellation
        return redirect()->route('orders.index');
    }

    public function deleteOrder()
    {
        $this->authorize('delete', $this->order);
        $this->order->delete();

        session()->flash('message', 'Order deleted successfully.');

        return redirect()->route('orders.index');
    }

    public function cancelConfirmation()
    {
        $this->confirmationType = null;
    }

    public function acceptItem($itemId) {
        $item = OrderItem::findOrFail($itemId);
        $this->authorize('update', $item);

        $item->update(['status' => 'accepted', 'date_accepted' => now()]);

        MarkOrderItemAsShipped::dispatch($item->id)->delay(now()->addMinute());

        $this->updateTableData($this->order->orderItems);
        $this->dispatch('refreshOrdersTable');
    }

    public function cancelItem($itemId) {
        $item = OrderItem::findOrFail($itemId);
        $this->authorize('update', $item);

        $item->update(['status' => 'cancelled']);

        $revertedProductQuantity = $item->product->quantity + $item->order_quantity;
        $item->product->update(['quantity' => $revertedProductQuantity]);

        $this->updateTableData($this->order->orderItems);
        $this->dispatch('refreshOrdersTable');
    }


    public function canSellerViewItem($orderItem) {
        return !auth()->user()->hasRole('seller') ||
                $orderItem->product->seller->id === auth()->id();
    }

    public function updateTableData($orderItems) {

        $this->columns = ['Product', 'Status', 'Price per Piece', 'Quantity', 'Total', 'Actions'];

        $this->widths = [
            'Product' => '300px',
            'Status' => '100px',
            'Quantity' => '70px',
            'Total' => '120px',
            'Actions' => '150px',
        ];

        $this->columnsToProperty = [
            'Product' => '',
            'Status' => '',
            'Price per Piece' => 'product_price',
            'Quantity' => 'order_quantity',
            'Total' => '',
            'Actions' => '',
        ];

        $this->customClasses = [
            'container' => '',
            'table' => '',
            'thead' => '',
            'th' => '',
            'tbody' => '',
            'tr' => 'hover:bg-transparent',
            'td' => 'first:bg-transparent',
            'tdNoData' => '',
        ];


        $this->noDataText = 'No items to show.';

        $this->cells = [];
        $this->cellData = [];

        foreach ($orderItems as $rowIndex => $item) {
            $this->cells[] = [];
            $product = Product::findOrFail($item->product_id);

            foreach ($this->columns as $colIndex => $column) {
                switch ($column) {
                    case 'Product':
                        $this->cells[$rowIndex][] = view('components.order-item-cell', compact('product'))->render();
                        break;

                    case 'Status':
                        $this->cells[$rowIndex][] = view('components.order-item-status-badge', compact('item'))->render();
                        break;

                    case 'Price per Piece':
                        $this->cells[$rowIndex][] = "<div class='flex justify-center'>" . Number::currency($item['product_price'], 'PHP') . "</div>";
                        break;

                    case 'Total':
                        $this->cells[$rowIndex][] = "<div class='flex justify-center'>" . Number::currency($item['product_price'] * $item['order_quantity'], 'PHP') . "</div>";
                        break;


                    case 'Actions':

                        $this->cells[$rowIndex][] = ($item->status === 'pending')
                                                        ? view('components.order-item-actions', compact('item'))->render()
                                                        : '';
                        break;

                    default:
                        $datum = $item->{$this->columnsToProperty[$column]};
                        $this->cells[$rowIndex][] = $datum ? "<div class='flex justify-center'>" . $datum . "</div>" : '';
                        break;
                }
            }
        }
    }
}
