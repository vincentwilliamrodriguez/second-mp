<div class="flex flex-col p-8 gap-4 w-[90vw] max-w-[1200px]">
    @if (session('message'))
        <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="font-black text-3xl">
            {{ auth()->user()->hasRole('seller') ? 'My Products' : 'All Products' }}
        </h2>

        @if (auth()->user()->can('create-products') && !$products->isEmpty())
            <x-button onclick="window.location.href='{{ route('products.create') }}'" :baseColor="'blue'"
                :iconSize="'w-6 h-6'">

                <x-slot name='icon'><x-eos-add-box-o /></x-slot>
                Create

            </x-button>
        @endif
    </div>

    {{-- Search, Sort, and Filter using Livewire --}}
    <div class="flex gap-6 mb-2">
        <flux:input class="max-w-80 mr-10" wire:model.live='search' icon:trailing='magnifying-glass' type='text'
            placeholder='Search Product, Description, Seller, etc...' autocomplete='search' autofocus></flux:input>

        <div class="flex items-center basis-36">
            <flux:button variant='subtle' size='sm' class="!px-1"
                @click=" $wire.set('sortOrder', ($wire.sortOrder === 'asc') ? 'desc' : 'asc')"
                x-bind:disabled="$wire.sortOrder === ''">
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


        <div class="flex items-center basis-36">
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

            <flux:input size='sm' class='max-w-36 pl-1' class:input="!pl-8"
                wire:model.blur='minPrice' placeholder='Min.' type='number' min='0'
                max='10000000' pattern="[0-9]+([\.,][0-9]+)?" step="0.01">

                <x-slot name='icon'><flux:icon.philippine-peso class='size-4' /></x-slot>
            </flux:input>

            <flux:text class="pt-1 font-medium select-none!" variant="subtle">to</flux:text>

            <flux:input size='sm' class='max-w-36 pl-1' class:input="!pl-8"
                wire:model.blur='maxPrice' placeholder='Max.' type='number' min='0'
                max='10000000' pattern="[0-9]+([\.,][0-9]+)?" step="0.01">

                <x-slot name='icon'><flux:icon.philippine-peso class='size-4' /></x-slot>
            </flux:input>
        </div>
    </div>



    @if ($products->isEmpty())
        <div class="col-span-full flex items-center justify-center">

            {{-- Seller's empty view --}}
            @if (auth()->user()->can('create-products'))
                <a href="{{ route('products.create') }}"
                    class="group w-full md:w-2/3 lg:w-1/2 h-64 border-2 border-dashed border-blue-300 rounded-lg flex flex-col items-center justify-center p-6 transition-all hover:border-blue-500 hover:bg-blue-50">
                    <div
                        class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                        <flux:icon.plus class="w-10 h-10 text-blue-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2 pointer-events-none select-none">No Products Yet
                    </h3>
                    <p class="text-gray-600 text-center mb-4 pointer-events-none select-none">Add your first product to
                        start selling</p>
                </a>


                {{-- Customer's empty view --}}
            @else
                <p class="text-gray-500 pb-[400px]">
                    {{ $this->isProductsEmpty() ? 'No products to show.' : 'No products match your query.' }}</p>
            @endif

        </div>
    @else
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 auto-rows-[400px] justify-center gap-6 mb-8">
            @foreach ($products as $product)
                <x-product-card :product="$product"></x-product-card>
            @endforeach
        </div>
    @endif

    @if (!$products->isEmpty())
        {{ $products->links(data: ['scrollTo' => false]) }}
    @endif
</div>
