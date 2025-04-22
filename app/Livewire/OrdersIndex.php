<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Dom\HTMLElement;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Filter and sorting
    public $search;
    public $statusFilter;

    public $sortBy;
    public $sortOrder;


    // Filter and sorting config
    public $sortValues = [
        'display_name' => [
            'Order Name',
            'case-sensitive',
        ],
        'created_at' => [
            'Date Placed',
            'clock',
        ],
        'updated_at' => [
            'Date Updated',
            'arrow-path',
        ],
    ];

    public $statusFilterValues = [
        'pending' => [
            'Pending',
            'question-mark-circle'
        ],
        'in_progress' => [
            'In Progress',
            'clock'
        ],
        'completed' => [
            'Completed',
            'check-badge'
        ],
    ];


    // Table data
    public $columns;
    public $widths;
    public $columnsToProperty;
    public $customClasses;
    public $columnsWithSorting;
    public $noDataText;
    public $cells;
    public $cellData;


    public function render() {
        $orders = $this->fetchOrders();
        $this->updateTableData($orders);

        return view('livewire.orders-index', compact('orders'));
    }

    public function fetchOrders() {
        $user = auth()->user();

        // Main filter based on role
        $mainFilter = match ($user->getRoleNames()[0]) {
            'customer' => function ($query) use ($user) {
                $query->whereHas('orderItems', function ($subQuery) use ($user) {
                    $subQuery->where('customer_id', $user->id);
                });
            },
            'seller' => function ($query) use ($user) {
                $query->whereHas('orderItems.product', function ($subQuery) use ($user) {
                    $subQuery->where('seller_id', $user->id);
                });
            },
            'admin' => fn ($query) => $query,
        };

        $ordersQuery = Order::query()->when($mainFilter, $mainFilter);


        // Search
        if ($this->search) {
            $ordersQuery = $ordersQuery->where(function ($query) {
                $query
                    ->where('display_name', 'like', '%' . $this->search . '%')

                    ->orWhereHas('orderItems.product', function ($product) {
                        $product->where('name', 'like', '%' . $this->search . '%')
                            ->orWhereHas('seller', function ($seller) {
                                $seller->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('username', 'like', '%' . $this->search . '%');
                            });
                    })

                    ->orWhereHas('customer', function ($customer) {
                        $customer->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('username', 'like', '%' . $this->search . '%');
                    });
            });
        }


        // Sorting
        if ($this->sortBy && $this->sortOrder) {
            $ordersQuery = $ordersQuery->orderBy($this->sortBy, $this->sortOrder);
        } else {
            $ordersQuery = $ordersQuery->orderBy('updated_at', 'DESC');
        }



        $orders = $ordersQuery->get();

        // Filter based on status
        if ($this->statusFilter) {
            $orders = $orders->filter(fn ($order) => $order->overall_status === $this->statusFilter);
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;

        $orders = new LengthAwarePaginator(
            $orders->forPage($currentPage, $perPage)->values(),
            $orders->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $orders;
    }


    public function updateTableData($orders) {

        $this->columns = ['Order', 'Status', 'Date Placed', 'Date Updated', 'Actions'];

        if (auth()->user()->hasAnyRole(['customer', 'admin'])) {
            array_splice($this->columns, 1, 0, 'Seller/s' );
        }

        if (auth()->user()->hasAnyRole(['seller', 'admin'])) {
            array_splice($this->columns, 1, 0, 'Customer' );
        }


        $this->widths = [
            'Order' => '170px',
            'Seller/s' => '100px',
            'Customer' => '100px',
            'Actions' => '150px',
        ];

        $this->columnsToProperty = [
            'Order' => 'display_name',
            'Seller/s' => '',
            'Customer' => '',
            'Status' => '',
            'Date Placed' => 'created_at',
            'Date Updated' => 'updated_at',
            'Actions' => '',
        ];

        $this->customClasses = [
            'container' => '',
            'table' => '',
            'thead' => '',
            'th' => '',
            'tbody' => '',
            'tr' => 'hover:bg-transparent',
            // 'td' => 'first:bg-transparent',
            'tdNoData' => '',
        ];

        $this->columnsWithSorting = ['Order', 'Date Placed', 'Date Updated'];

        $this->noDataText = 'No orders to show.';

        $this->cells = [];
        $this->cellData = [];

        foreach ($orders as $rowIndex => $order) {
            $this->cells[] = [];

            foreach ($this->columns as $colIndex => $column) {
                switch ($column) {
                    case 'Order':
                        $this->cells[$rowIndex][] = view('components.order-cell', compact('order'))->render();
                        break;

                    case 'Customer':
                        $this->cells[$rowIndex][] = <<<HTML
                            <div class='flex justify-center'>
                                <span>{$order->customer->name}</span>
                            </div>
HTML;
                        break;

                    case 'Seller/s':
                        $sellerList = "<ul>";
                        $sellerNames = $order->orderItems
                            ->map(fn($item) => $item->product->seller)
                            ->unique('id')
                            ->sortBy('id')
                            ->pluck('name');

                        foreach ($sellerNames as $sellerName) {
                            $sellerList .= "<li>{$sellerName}</li>";
                        }

                        $this->cells[$rowIndex][] = <<<HTML
                            <div class='flex justify-center text-zinc-500'>
                                $sellerList
                            </div>
HTML;
                        break;

                    case 'Status':
                        $status = $order->overall_status;
                        $statusColor = match ($status) {
                            'pending' => 'bg-yellow-200',
                            'in_progress' => 'bg-blue-200',
                            'completed' => 'bg-green-300',
                        };

                        $this->cells[$rowIndex][] = "<div class='flex justify-center'><div class='rounded-full px-2 py-1 text-xs " . $statusColor . "'>" . ucwords(str_replace('_', ' ', $status)) . "</div></div>";
                        break;

                    case 'Date Placed':
                    case 'Date Updated':
                        $datum = $order->{$this->columnsToProperty[$column]};
                        $this->cells[$rowIndex][] = <<<HTML

                        <div class='flex flex-col items-center'>
                            <span> {$datum->format('F j, Y')} </span>
                            <span class='text-xs text-zinc-400'> {$datum->format('g:i A')} </span>
                        </div>
HTML;
                        break;


                    case 'Actions':
                        $this->cells[$rowIndex][] = view('components.order-actions', compact('order'))->render();
                        break;

                    default:
                        // dd($item, $column, $columnsToProperty);
                        $datum = $order->{$this->columnsToProperty[$column]};
                        $this->cells[$rowIndex][] = $datum ? "<div class='flex justify-center'>" . $datum . "</div>" : '';
                        break;
                }
            }
        }
    }
}
