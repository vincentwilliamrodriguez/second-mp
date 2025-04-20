<div class="flex flex-col p-8 gap-4 w-[90vw] max-w-[1200px] min-h-[550px]">
    @if (session('message'))
        <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="font-black text-3xl text-gray-800">
            {{ auth()->user()->hasRole('seller') ? 'My Products' : 'All Products' }}
        </h2>

        @if (auth()->user()->can('create-products') && !$this->isProductsEmpty())
            <x-button baseColor="blue" iconSize="w-6 h-6"
                    wire:click.prevent="$dispatchTo('products-child', 'open', {method: 'Create'})"
                    x-on:click.prevent="$flux.modal('products-child').show()">

                <x-slot name='icon'><x-eos-add-box-o /></x-slot>
                Create

            </x-button>
        @endif
    </div>

    {{-- Search, Sort, and Filter using Livewire --}}
    <div class="flex gap-6">
        @php
            $searchPlaceholder = auth()->user()->hasRole('seller')
                                    ? 'Search Product, Description, etc...'
                                    : 'Search Product, Description, Seller, etc...'
        @endphp


        <flux:input class="max-w-80 mr-10" wire:model.live='search' icon:trailing='magnifying-glass' type='text'
            :placeholder='$searchPlaceholder' autocomplete='search' autofocus></flux:input>

        <div class="flex items-center basis-[9.5rem]">
            <flux:button variant='subtle' size='sm' class="!px-1"
                x-on:click=" $wire.set('sortOrder', ($wire.sortOrder === 'asc') ? 'desc' : 'asc')"
                x-bind:disabled="$wire.sortOrder === ''"
                x-on:sortchanged.window="$wire.set('sortBy', $event.detail.sortBy);
                                         $wire.set('sortOrder', $event.detail.sortOrder);"
            >
                <flux:icon.arrow-long-up x-show="$wire.sortOrder === 'asc'" x-cloak />
                <flux:icon.arrow-long-down x-show="$wire.sortOrder === 'desc'" x-cloak />
                <flux:icon.arrows-up-down x-show="!['asc', 'desc'].includes($wire.sortOrder)" x-cloak />
            </flux:button>

            <flux:dropdown>
                <flux:button variant='subtle' icon:trailing="chevron-down" size='sm' class="pl-2">
                    <span class="!select-none"
                        x-text='$wire.sortBy ? $wire.sortValues[$wire.sortBy][0] : "Sort by"'></span>
                </flux:button>

                <flux:menu class="shadow-xl">
                    <flux:menu.radio.group wire:model.live='sortBy'>
                        @foreach ($sortValues as $sortName => $sortData)
                            <flux:menu.radio
                                :class="'hover:bg-zinc-100 hover:cursor-pointer [&>*:first-child]:hidden '.(($sortBy ===
                                    $sortName) ? '!text-blue-500' : '')"
                                :value='$sortName' :icon:trailing='$sortData[1]'
                                x-on:click="$wire.sortOrder = 'asc';">
                                {{ $sortData[0] }}
                            </flux:menu.radio>
                        @endforeach
                </flux:menu>
                </flux:menu>
            </flux:dropdown>

            <div wire:loading.delay wire:target="sortBy, sortOrder" class="inline-block">
                <flux:icon.arrow-path class="w-4 h-4 animate-spin text-zinc-400" />
            </div>
        </div>


        <div class="flex items-center basis-40">
            <flux:button variant='subtle' size='sm' class="!px-1" disabled>
                <flux:icon.funnel />
            </flux:button>

            <flux:dropdown>
                <flux:button variant='subtle' icon:trailing="chevron-down" size='sm' class="pl-2">
                    <span class="!select-none"
                        x-text='$wire.category ? $wire.categoryValues[$wire.category][0] : "Category"'></span>
                </flux:button>

                <flux:menu class="shadow-xl">
                    <flux:menu.radio.group wire:model.live='category'>
                        @foreach ($categoryValues as $categoryName => $categoryData)
                            <flux:menu.radio
                                :class="'hover:bg-zinc-100 hover:cursor-pointer [&>*:first-child]:hidden '.(($category ===
                                    $categoryName) ? '!text-blue-500' : '')"
                                :value='$categoryName' :icon:trailing='$categoryData[1]'
                                x-on:click="if ({{ $category === $categoryName }}) { $wire.set('category', '') }">
                                {{ $categoryData[0] }}
                            </flux:menu.radio>
                        @endforeach
                </flux:menu>
                </flux:menu>
            </flux:dropdown>

            <div wire:loading.delay wire:target="category" class="inline-block">
                <flux:icon.arrow-path class="w-4 h-4 animate-spin text-zinc-400" />
            </div>
        </div>

        <div class="flex justify-end items-center flex-1 gap-2">
            <flux:text class="pt-1 font-medium select-none!" variant="subtle">Price Range:</flux:text>

            <flux:input size='sm' class='max-w-32 pl-1' class:input="!pl-8"
                wire:model.live.debounce.500ms='minPrice' placeholder='Min.' type='number' min='0'
                max='10000000' pattern="[0-9]+([\.,][0-9]+)?" step="0.01">

                <x-slot name='icon'><flux:icon.philippine-peso class='size-4' /></x-slot>
            </flux:input>

            <flux:text class="pt-1 ml-1 font-medium select-none!" variant="subtle">to</flux:text>

            <flux:input size='sm' class='max-w-32 pl-1' class:input="!pl-8"
                wire:model.live.debounce.500ms='maxPrice' placeholder='Max.' type='number' min='0'
                max='10000000' pattern="[0-9]+([\.,][0-9]+)?" step="0.01">

                <x-slot name='icon'><flux:icon.philippine-peso class='size-4' /></x-slot>
            </flux:input>
        </div>
    </div>



    @if ($products->isEmpty())
        <div class="col-span-full flex items-center justify-center">


            {{-- Seller's empty view (without quey) --}}
            @if (auth()->user()->can('create-products') && !($search || $category || $minPrice || $maxPrice))
                <a class="group w-full md:w-2/3 lg:w-1/2 h-64 border-2 border-dashed border-blue-300 rounded-lg flex flex-col items-center justify-center p-6 transition-all hover:border-blue-500 hover:bg-blue-50"
                    wire:click.prevent="$dispatchTo('products-child', 'open', {method: 'Create'})"
                    x-on:click.prevent="$flux.modal('products-child').show()">

                    <div
                        class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                        <flux:icon.plus class="w-10 h-10 text-blue-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2 pointer-events-none select-none">No Products Yet
                    </h3>
                    <p class="text-gray-600 text-center mb-4 pointer-events-none select-none">Add your first product to
                        start selling</p>
                </a>


            @else
                {{-- Customer's empty view --}}
                <p class="text-gray-500">
                    {{ $this->isProductsEmpty() ? 'No products to show.' : 'No products match your query.' }}</p>
            @endif

        </div>
    @elseif (auth()->user()->hasRole('customer'))
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 auto-rows-[400px] justify-center gap-6 mb-8">
            @foreach ($products as $product)
                {{-- This calls the new Product Card component --}}
                <livewire:product-card :$product :$categoryValues wire:key='{{ $product->id }}' />

                {{-- This calls the old Product Card component  --}}
                {{-- <x-product-card :product="$product"></x-product-card> --}}
            @endforeach
        </div>
    @else
        {{-- This uses the new Livewire component for tables --}}
        @php
            // This is the array of closures used for the old Table component

            // $productsTableColumns = [
            //     'Name' => function($product) {
            //         return view('livewire.product-cell', compact('product'))->render();
            //     },
            //     'Category' => function($product) {
            //         return $product->category;
            //     },
            //     'Quantity' => function($product) {
            //         return $product->quantity;
            //     },
            //     'Price' => function($product) {
            //         return Number::currency($product->price, 'PHP');
            //     },
            //     'Created' => function($product) {
            //         return $product->created_at->format('F j, Y');
            //     },
            //     'Modified' => function($product) {
            //         return $product->updated_at->format('F j, Y');
            //     },
            //     'Actions' => function($product) {
            //         return view('livewire.product-actions', compact('product'))->render();
            //     }
            // ];
        @endphp


        {{-- This is the new implementation of the Livewire-based table component --}}
        @php
            $productsTableColumns = ['Name', 'Category', 'Quantity', 'Price', 'Created', 'Modified', 'Actions'];
            $columnsWithSorting = ['Name', 'Category', 'Quantity', 'Price', 'Created', 'Modified'];
            $columnsToProperty = [
                'Created' => 'created_at',
                'Modified' => 'updated_at',
                'Actions' => '',
            ];
            $productsTableWidths = [
                'Name' => '130px',
                'Category' => '100px',
                'Quantity' => '70px',
                'Actions' => '150px',
            ];

            if (auth()->user()->hasRole('admin')) {
                array_splice($productsTableColumns, 1, 0, 'Seller');
            }

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

            $items = $products->items();
            $cells = [];

            foreach ($items as $rowIndex => $product) {
                $cells[] = [];

                foreach ($productsTableColumns as $colIndex => $column) {
                    switch ($column) {
                        case 'Name':
                            $cells[$rowIndex][] = view('livewire.product-cell', compact('product'))->render();
                            break;

                        case 'Seller':
                            $cells[$rowIndex][] = $product->seller->username;
                            break;

                        case 'Price':
                            $cells[$rowIndex][] = Number::currency($product->price, 'PHP');
                            break;

                        case 'Created':
                            $cells[$rowIndex][] = $product->created_at->format('F j, Y');
                            break;

                        case 'Modified':
                            $cells[$rowIndex][] = $product->updated_at->format('F j, Y');
                            break;

                        case 'Actions':
                            $cells[$rowIndex][] = view('livewire.product-actions', compact('product'))->render();
                            break;

                        default:
                            $cells[$rowIndex][] = $product->{Str::snake($column)} ?? '';
                            break;
                    }
                }
            }
        @endphp

        <livewire:table
            wire:key="{{ now() }}"
            :items="$products->items()"
            :columns="$productsTableColumns"
            :widths="$productsTableWidths"
            :$cells
            :$columnsToProperty
            :$columnsWithSorting
            :$sortBy
            :$sortOrder
            :$customClasses
        >
        </livewire:table>


        {{-- This uses the old Blade component for tables --}}

        {{-- <x-table --}}
            {{-- :items="$products"
            :columns="$productsTableColumns"
            :widths="$productsTableWidths"
        /> --}}
    @endif

    @if (!$products->isEmpty())
        {{ $products->links(data: ['scrollTo' => false]) }}
    @endif


    {{-- Modal for Products Child (show, create, edit, or delete) --}}
    <livewire:products-child
        wire:key="products-child"
        :$categoryValues
        :categories="array_keys($categoryValues)"
    />

</div>
