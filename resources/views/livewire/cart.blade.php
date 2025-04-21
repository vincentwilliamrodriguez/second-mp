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
            'Total' => '120px',
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
            'tr' => 'hover:bg-transparent',
            'td' => 'first:bg-transparent',
            'tdNoData' => '',
        ];

        $columnsWithRowspan = [
            'Seller' => 'seller_rowspan'
        ];

        $columnsWithSorting = [];
        $sortBy = null;
        $sortOrder = null;

        $noDataText = 'Your cart is empty.';

        $items = collect($cartItems);
        $cells = [];
        $cellData = [];

        foreach ($items as $rowIndex => $item) {
            $cells[] = [];
            $product = App\Models\Product::where('id', $item['product_id'])->first();

            foreach ($cartTableColumns as $colIndex => $column) {
                switch ($column) {
                    case 'Product':
                        $cells[$rowIndex][] = view('components.cart-product-cell', compact('product'))->render();

                        break;

                    case 'Seller':
                        $cells[$rowIndex][] = "<div class='flex justify-center'>{$product->seller->name}</div>";
                        break;

                    case 'Price per Piece':
                        $cells[$rowIndex][] = "<div class='flex justify-center'>" . Number::currency($item['product_price'], 'PHP') . "</div>";
                        break;

                    case 'Quantity':
                        $cellData[$rowIndex][$colIndex] = [
                            'count' => $item['order_quantity'],
                            'max' => $product->quantity,
                            'cartItem' => $item,
                        ];
                        $cells[$rowIndex][] = "<livewire:counter>";
                        break;

                    case 'Total':
                        $total = '₱' . number_format($item['product_price'] * $item['order_quantity'], 2);
                        $cells[$rowIndex][$colIndex] = <<<HTML
                            <div class='flex justify-center'
                                x-data="{
                                    total: '$total',
                                }"
                                x-text='total'
                                x-on:counterchanged.window="if (\$event.detail.cartItem.id === '${item['id']}') {
                                    total = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', }).format(\$event.detail.count * \$event.detail.cartItem.product_price);
                                }"
                            ></div>
HTML;
                        break;

                    case 'Actions':
                        $cells[$rowIndex][] = view('components.cart-actions', compact('item'))->render();
                        break;

                    default:
                        // dd($item, $column, $columnsToProperty);
                        $cells[$rowIndex][] = $item[$columnsToProperty[$column]] ?? '';
                        break;
                }
            }
        }
    @endphp


    <div wire:loading.class="pointer-events-none select-none">
        <livewire:table
            wire:key="{{ now() }}"
            :items="$items"
            :columns="$cartTableColumns"
            :widths="$cartTableWidths"
            :$cells
            :$cellData
            :$columnsToProperty
            :$columnsWithRowspan
            :$columnsWithSorting
            :$sortBy
            :$sortOrder
            :$noDataText
            :$customClasses
        >
        </livewire:table>
    </div>

    <div class="flex flex-col justify-end gap-6 mt-auto pt-8">
        <div class="flex flex-col justify-end items-end">
            <flux:text variant='subtle' class="text-md">
                Subtotal Estimate (<span wire:text='totalCount'></span> items):
            </flux:text>
            <flux:text variant='stong' class="text-2xl text-accent font-bold" wire:text='subtotalFormatted'></flux:text>
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant='subtle' icon="trash"
                wire:click.prevent="$dispatchTo('cart-child', 'open', {method: 'Clear'})"
                x-on:click.prevent="$flux.modal('cart-child').show()"
            >
                Clear Cart
            </flux:button>
            <a href="{{ route('checkout') }}">
                <flux:button class="ml-6" type='submit' variant='primary' icon='shopping-bag'>
                    {{ __('Check Out') }}
                </flux:button>
            </a>
        </div>
    </div>
</div>
