{{-- This is the old index file for the Orders Page --}}


{{-- The PHP code below defines the parameters of the user's orders tables --}}

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

    <h2 class="font-black text-3xl mb-6">
        {{ match (auth()->user()->getRoleNames()->first()) {
            'customer' => 'My Orders',
            'seller' => 'Pending Orders',
            'admin' => 'All Orders',
        } }}
    </h2>


    {{-- Search, Sort, and Filter using Livewire --}}
    <div class="flex gap-6 mb-4">
        @php
            $searchPlaceholder =
            match(auth()->user()->getRoleNames()->first()){
                                    'customer' => 'Search Product, Seller, etc...',
                                    'seller' => 'Search Product, Customer, etc...',
                                    'admin' => 'Search Product, Seller, Customer, etc...',
                                };
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
                        x-text='$wire.statusFilter ? $wire.statusFilterValues[$wire.statusFilter][0] : "Filter Status"'></span>
                </flux:button>

                <flux:menu class="shadow-xl">
                    <flux:menu.radio.group wire:model.live='statusFilter' x-data="{oldStatusFilter: ''}">
                        @foreach ($statusFilterValues as $filterName => $filterData)
                            <flux:menu.radio
                                class="hover:bg-zinc-100 hover:cursor-pointer [&>*:first-child]:hidden" x-bind:class="($wire.statusFilter === '{{ $filterName }}') ? '!text-blue-500' : ''"
                                :value='$filterName' :icon:trailing='$filterData[1]'
                                x-on:click="if ($wire.statusFilter === oldStatusFilter) {
                                                oldStatusFilter = '';
                                                $wire.set('statusFilter', '');
                                            } else {
                                                oldStatusFilter = $wire.statusFilter;
                                            }">
                                {{ $filterData[0] }}
                            </flux:menu.radio>
                        @endforeach
                </flux:menu>
                </flux:menu>
            </flux:dropdown>

            <div wire:loading.delay wire:target="statusFilter" class="inline-block">
                <flux:icon.arrow-path class="w-4 h-4 animate-spin text-zinc-400" />
            </div>
        </div>
    </div>

    <div class="transition-all" wire:loading.class="pointer-events-none select-none animate-pulse">
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
