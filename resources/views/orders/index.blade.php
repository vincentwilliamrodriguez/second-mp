@php
    $baseTableColumns = [
        'Product' => function($order) {
            $product = $order->product;

            return "<div class='flex flex-col gap-2'>
                        <p>".$order->product->name."</p>
                        <img class='w-[200px]' src='".Storage::url($product->picture)."' alt='".$product->name."'>
                    </div>";
        },
        'Customer' => function($order) {
            return $order->customer->username;
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
                $res = view('components.counter', compact('product'))->render();
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

            return "<span class='px-2 py-1 text-xs font-semibold rounded-full bg-{$color}-100 text-{$color}-800'>{$order->status}</span>";
        },
        'Actions' => function($order) {
            return view('components.order-actions', compact('order'))->render();
        }
    ];

    $actionsColumn = [
    ];

    $sellerTableColumns = array_merge($baseTableColumns, $actionsColumn);

@endphp


<x-tab title="Orders">
    <div class="flex flex-col p-8 w-[90vw] max-w-[1200px]">
        @if(session('message'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        <x-table
            :items="$orders"
            :columns="$sellerTableColumns"
            :widths="[
                'Product' => '200px',
                'Status' => '150px'
            ]"
        />
    </div>
</x-tab>
