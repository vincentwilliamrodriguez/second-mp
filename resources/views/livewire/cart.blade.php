<div class="flex flex-col p-8 gap-4 w-[90vw] max-w-[1200px] min-h-[550px]">
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


    <h2 class="font-black text-3xl text-gray-800">My Shopping Cart</h2>


    {{-- Shopping Cart Table --}}

    @php
        $cartTableColumns = ['Product', 'Seller', 'Price per Piece', 'Quantity', 'Total', 'Actions'];

        $cartTableWidths = [
            'Product' => '300px',
            'Seller' => '70px',
            'Quantity' => '100px',
            'Actions' => '150px',
        ];

        $columnsToProperty = [
            'Product' => 'product_id',
            'Price per Piece' => 'product_price',
            'Quantity' => 'order_quantity',
            'Total' => 'order_total',
            'Actions' => '',
            'Seller' => ''
        ];

        $customClasses = [
            'container' => '',
            'table' => '',
            'thead' => '',
            'th' => '',
            'tbody' => '',
            'tr' => '',
            'td' => '',
            'tdNoData' => '',
        ];

        $columnsWithRowspan = [
            'Seller' => 'seller_rowspan'
        ];

        $columnsWithSorting = [];
        $sortBy = null;
        $sortOrder = null;

        $items = collect($cartItems);
        $cells = [];

        foreach ($items as $rowIndex => $item) {
            $cells[] = [];
            $product = App\Models\Product::where('id', $item['product_id'])->first();

            foreach ($cartTableColumns as $colIndex => $column) {
                switch ($column) {
                    case 'Product':
                        $cells[$rowIndex][] = <<<HTML

                            <div class="flex flex-col gap-1 hover:cursor-pointer" wire:click.prevent='\$dispatchTo("products-child", "open", {method: "Show", productId: "$product->id" })' x-on:click.prevent="\$flux.modal('products-child').show()">
                                <h3 class='font-black text-lg hover:cursor-pointer hover:underline'>$product->name</h3>
                            </div>
HTML;
                        break;

                    case 'Seller':
                        $cells[$rowIndex][] = $product->seller->name;
                        break;

                    case 'Price per Piece':
                        $cells[$rowIndex][] = Number::currency($item['product_price'], 'PHP');
                        break;

                    // case 'Quantity':
                    //     $product = \App\Models\Product::find($item['product_id']);
                    //     $init = strval($item['quantity']);
                    //     $order_id = $item['id'];
                    //     $res = view('components.counter-orders', compact(['product', 'init', 'order_id']))->render();
                    //     $cells[$rowIndex][] = "<div class='flex justify-center w-[100px]'>{$res}</div>";
                    //     break;

                    case 'Total':
                        $cells[$rowIndex][] = Number::currency($item['product_price'] * $item['order_quantity'], 'PHP');
                        break;

                    // case 'Actions':
                        // $cells[$rowIndex][] = view('components.order-actions', ['order' => (object)$item])->render();
                        // break;

                    default:
                        // dd($item, $column, $columnsToProperty);
                        $cells[$rowIndex][] = $item[$columnsToProperty[$column]] ?? '';
                        break;
                }
            }
        }
    @endphp


    <livewire:table
        wire:key="{{ now() }}"
        :items="$items"
        :columns="$cartTableColumns"
        :widths="$cartTableWidths"
        :$cells
        :$columnsToProperty
        :$columnsWithRowspan
        :$columnsWithSorting
        :$sortBy
        :$sortOrder
        :$customClasses
    >
    </livewire:table>
</div>
