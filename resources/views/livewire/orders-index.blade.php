{{-- This is the old index file for the Orders Page --}}


{{-- The PHP code below defines the parameters of the user's orders tables --}}

@php

    $baseTableWidths = [
        'Product' => '220px',
        'Status' => '120px',
        'Actions' => '150px',
    ];

    $baseTableColumns = [
        // 'Product' => function($order) {
        //     $product = $order->product;

        //     return view('components.orders-product-cell', compact('order', 'product'))->render();
        // },
        // 'Price per Piece' => function($order) {
        //     return Number::currency($order->product->price, 'PHP');
        // },
        // 'Quantity' => function($order) {
        //     $product = $order->product;
        //     $res = '';

        //     if (!$order->is_placed)
        //     {
        //         $init = strval($order->quantity);
        //         $order_id = $order->id;
        //         $res = view('components.counter-orders', compact(['product', 'init', 'order_id']))->render();
        //     } else {
        //         $res = $order->quantity;
        //     }

        //     return "<div class='flex justify-center w-[100px]'>{$res}</div>";
        // },
        // 'Total' => function($order) {
        //     return Number::currency($order->product->price * $order->quantity, 'PHP');
        // },
        // 'Date' => function($order) {
        //     return $order->date_placed->format('F j, Y');
        // },
        // 'Status' => function($order) {
        //     $colors = [
        //         'pending' => 'yellow',
        //         'completed' => 'green',
        //         'cancelled' => 'red',
        //     ];
        //     $color = $colors[$order->status] ?? 'gray';

        //     return "<div class='flex justify-center'><span class='px-2 py-1 text-xs font-semibold rounded-full bg-{$color}-100 text-{$color}-800'>{$order->status}</span></div>";
        // },
        // 'Actions' => function($order) {
        //     return view('components.order-actions', compact('order'))->render();
        // }
    ];

    $customerTableColumns = $baseTableColumns;

    $sellerTableColumns = [];

    foreach ($baseTableColumns as $key => $value) {
        $sellerTableColumns[$key] = $value;

        if ($key === 'Product') {
            // $sellerTableColumns['Customer'] = function($order) {
            //     return $order->customer->username;
            // };
        }
    }


    $adminTableColumns = $sellerTableColumns;

@endphp


<div class="flex flex-col p-8 w-[90vw] max-w-[1300px]">
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

    <h2 class="font-black text-3xl mb-4 mt-4">
        {{ match (auth()->user()->getRoleNames()->first()) {
            'customer' => 'My Orders',
            'seller' => 'Pending Orders',
            'admin' => 'All Orders',
        } }}
    </h2>

    <div class="transition-all opacity-100" wire:loading.class="pointer-events-none select-none opacity-80">
        <livewire:table
            wire:key="{{ now() }}"
            :items="collect($orders)['data']"
            :$columns
            :$widths
            :$cells
            :$cellData
            :$columnsToProperty
            :$columnsWithSorting
            :$sortBy
            :$sortOrder
            :$noDataText
            :$customClasses
        >
        </livewire:table>
    </div>


    @if (!$orders->isEmpty())
        <div class="mt-8">
            {{ $orders->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
