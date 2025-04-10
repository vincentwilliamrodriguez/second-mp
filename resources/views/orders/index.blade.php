@php
    $baseTableWidths = [
        'Product' => '220px',
        'Status' => '120px',
        'Actions' => '150px',
    ];

    $baseTableColumns = [
        'Product' => function($order) {
            $product = $order->product;

            return "<div class='flex flex-col gap-2'>
                        <h3 class='font-black'>".$order->product->name."</h3>
                        <p>".$order->product->quantity." in stock</p>
                        <img class='w-[200px]' src='".Storage::url($product->picture)."' alt='".$product->name."'>
                    </div>";
        },
        'Price per Piece' => function($order) {
            return Number::currency($order->product->price, 'PHP');
        },
        'Quantity' => function($order) {
            $product = $order->product;
            $res = '';

            if (auth()->user()->hasRole('admin') ||
                auth()->user()->hasRole('customer') && !$order->is_placed)
            {
                $init = strval($order->quantity);
                $order_id = $order->id;
                $res = view('components.counter-orders', compact(['product', 'init', 'order_id']))->render();
            } else {
                $res = $order->quantity;
            }

            return "<div class='flex justify-center w-[100px]'>{$res}</div>";
        },
        'Total' => function($order) {
            return Number::currency($order->product->price * $order->quantity, 'PHP');
        },
        'Date' => function($order) {
            return date('d-m-Y', strtotime($order->date_placed));
        },
        'Status' => function($order) {
            $colors = [
                'pending' => 'yellow',
                'completed' => 'green',
                'cancelled' => 'red',
            ];
            $color = $colors[$order->status] ?? 'gray';

            return "<div class='flex justify-center'><span class='px-2 py-1 text-xs font-semibold rounded-full bg-{$color}-100 text-{$color}-800'>{$order->status}</span></div>";
        },
        'Actions' => function($order) {
            return view('components.order-actions', compact('order'))->render();
        }
    ];

    $customerTableColumns = $baseTableColumns;

    $sellerTableColumns = [];

    foreach ($baseTableColumns as $key => $value) {
        $sellerTableColumns[$key] = $value;

        if ($key === 'Product') {
            $sellerTableColumns['Customer'] = function($order) {
                return $order->customer->username;
            };
        }
    }


    $adminTableColumns = $sellerTableColumns;

@endphp


<x-tab title="Orders">
    <div class="flex flex-col p-8 w-[90vw] max-w-[1200px]">
        @if(session('message'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />



        @role('customer')
            @php
                $unplacedOrders = $orders->filter(fn($order) => !$order->is_placed);
                $placedOrders = $orders->filter(fn($order) => $order->is_placed);
            @endphp

            <div class="flex justify-between items-center mb-4">
                <h2 class="font-black text-3xl">My Shopping Cart</h2>
                <form action="{{ route('orders.place-all') }}" method="POST" class="inline">
                    @csrf
                    <x-button type="submit">Place Orders</x-button>
                </form>
            </div>

            <x-table
                :items="$unplacedOrders"
                :columns="$customerTableColumns"
                :widths="$baseTableWidths"
            />

            <br/>

            <h2 class="font-black text-3xl mb-2">My Orders</h2>
            <x-table
                :items="$placedOrders"
                :columns="$customerTableColumns"
                :widths="$baseTableWidths"
            />
        @endrole

        @role('seller')
            <h2 class="font-black text-3xl mb-2">Pending Order Requests</h2>
            <x-table
                :items="$orders->filter(fn($order) => $order->is_placed && $order->status === 'pending')"
                :columns="$sellerTableColumns"
                :widths="$baseTableWidths"
            />

            <br/>

            <h2 class="font-black text-3xl mb-2">Past Order Requests</h2>
            <x-table
                :items="$orders->filter(fn($order) => $order->is_placed && $order->status !== 'pending')"
                :columns="$sellerTableColumns"
                :widths="$baseTableWidths"
            />
        @endrole

        @role('admin')
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-black text-3xl">All Orders</h2>
            </div>
            <x-table
                :items="$orders"
                :columns="$adminTableColumns"
                :widths="$baseTableWidths"
            />
        @endrole
    </div>
</x-tab>
