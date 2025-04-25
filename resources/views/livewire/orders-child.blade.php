{{-- This is the new show/edit/delete order page with Livewire --}}
<flux:modal name='orders-child' class="fixed !max-w-none" x-on:open="$wire.state = null; method=$event.detail.method" wire:close="$dispatch('close')" x-data="{method: '', defaultSizes: {
    'Show': '!min-w-[750px] !min-h-[400px]',
    'Edit': '!min-w-[750px] !min-h-[400px]',
    'Delete': '!min-w-[400px] !min-h-[150px]',
}}">
    <div x-bind:class="'bg-white rounded-lg shadow-sm max-w-5xl mx-auto pt-4 flex flex-col ' + defaultSizes[method]">
        {{-- Loading Indicator --}}
        <div x-cloak x-show="$wire.state === null" class="absolute top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2">
            <div class="flex gap-4 justify-center items-center">
                <span class="text-zinc-400 text-xl pb-1">Loading</span>
                <flux:icon.arrow-path class="w-4 h-4 animate-spin text-zinc-400" />
            </div>
        </div>


        <div x-cloak x-show="$wire.state !== null" class="flex flex-col flex-1 -mt-4">
            @if($state === 'Show')
                {{-- Show --}}
                <div class="flex justify-start items-center mb-4">
                    <h2 class="text-2xl font-bold text-indigo-700 mr-4">{{ $order->display_name }}</h2>
                </div>

                {{-- Show Header --}}
                <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                    <div class="flex justify-between">
                        <div class="flex flex-col justify-between">
                            <div class="mb-2">
                                <span class="font-semibold">Customer:</span> {{ $order->customer->name }}
                            </div>
                            <div class="mb-2">
                                <span class="font-semibold">Phone:</span> {{ $order->phone_number }}
                            </div>
                            <div>
                                <span class="font-semibold">Status:</span>
                                <x-order-status-badge :$order></x-order-status-badge>
                            </div>
                        </div>

                        <div class="flex flex-col justify-between">
                            <div>
                                <span class="font-semibold">Date Placed:</span> {{ $order->created_at->timezone('Asia/Manila')->format('F j, Y g:i A') }}
                            </div>
                            <div>
                                <span class="font-semibold">Payment Method:</span>
                                {{ match ($order->payment_method) {
                                    'cod' => 'Cash on Delivery',
                                    'e_wallet' => 'ShopStream E-Wallet',
                                    default => ucwords(str_replace('_', ' ', $order->delivery_method))
                                }  }}
                            </div>
                            <div>
                                <span class="font-semibold">Delivery Method:</span> {{ ucwords(str_replace('_', ' ', $order->delivery_method)) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address Information --}}
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2 text-indigo-600">Delivery Address</h3>
                    <div class="border border-zinc-200 rounded p-3">
                        <p class="font-semibold">{{ $order->full_name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->barangay }}, {{ $order->city }}</p>
                        <p>{{ $order->province }}, {{ $order->postal_code }}</p>
                    </div>
                </div>

                {{-- Order Items --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2 text-indigo-600">Order Items</h3>

                    @foreach($order->orderItems as $item)
                        @if ($this->canSellerViewItem($item))
                            <div class="border border-zinc-200 rounded-lg mb-4 overflow-hidden">
                                <div class="bg-zinc-50 p-3 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @php
                                            $product = App\Models\Product::where('id', $item->product_id)->first();
                                        @endphp

                                        <x-product-image :$product classes='size-12 mr-2' placeholderSize='size-6'></x-product-image>

                                        <div>
                                            <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-zinc-500">Qty. {{ $item->order_quantity }} @ {{ Number::currency($item->product_price, 'PHP') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="font-semibold">{{ Number::currency($item->product_price * $item->order_quantity, 'PHP') }}</span>
                                    </div>
                                </div>


                                {{-- Timeline --}}
                                @php
                                    $steps = [
                                        [
                                            'label' => 'Placed',
                                            'icon' => 'shopping-bag',
                                            'date' => $item->date_placed,
                                            'condition' => true,
                                        ],
                                        [
                                            'label' => 'Accepted',
                                            'icon' => 'check',
                                            'date' => $item->date_accepted,
                                            'condition' => $item->date_placed,
                                        ],
                                        [
                                            'label' => 'Shipped',
                                            'icon' => 'truck',
                                            'date' => $item->date_shipped,
                                            'condition' => $item->date_accepted,
                                        ],
                                        [
                                            'label' => 'Delivered',
                                            'icon' => 'home',
                                            'date' => $item->date_delivered,
                                            'condition' => $item->date_shipped,
                                        ],
                                    ];
                                @endphp

                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        @foreach ($steps as $index => $step)
                                            @php
                                                $isActive = $step['date'];
                                                $isCancelled = $item->status === 'cancelled' && $step['condition'] && !$step['date'];
                                                $iconColor = $isActive ? 'bg-green-100 text-green-700'
                                                            : ($isCancelled ? 'bg-red-100 text-red-700'
                                                            : 'bg-zinc-100 text-zinc-400');
                                                $textColor = $isActive ? 'text-green-700'
                                                            : ($isCancelled ? 'text-red-700'
                                                            : 'text-zinc-400');
                                            @endphp

                                            <div class="flex flex-col items-center">
                                                <div class="rounded-full p-2 {{ $iconColor }}">
                                                    @php
                                                        $iconComponent = match ($step['icon']) {
                                                            'shopping-bag' => 'heroicon-o-shopping-bag',
                                                            'check' => 'heroicon-o-check',
                                                            'truck' => 'heroicon-o-truck',
                                                            'home' => 'heroicon-o-home',
                                                            default => null,
                                                        };
                                                    @endphp

                                                    @if ($iconComponent)
                                                        @switch($step['icon'])
                                                            @case('shopping-bag')
                                                                <flux:icon.shopping-bag></flux:icon.shopping-bag>
                                                                @break
                                                            @case('check')
                                                                <flux:icon.check></flux:icon>
                                                                @break
                                                            @case('truck')
                                                                <flux:icon.truck></flux:icon>
                                                                @break
                                                            @case('home')
                                                                <flux:icon.home></flux:icon>
                                                                @break
                                                        @endswitch
                                                    @endif
                                                </div>
                                                <p class="text-xs mt-1 font-medium {{ $textColor }}">
                                                    {{ $step['label'] }}
                                                </p>

                                                @if ($isActive)
                                                    <p class="text-xs text-zinc-500">{{ $step['date']->timezone('Asia/Manila')->format('M j') }}</p>
                                                    <p class="text-xs text-zinc-500">{{ $step['date']->timezone('Asia/Manila')->format('g:i A') }}</p>
                                                @elseif ($isCancelled)
                                                    <p class="text-xs text-red-500">Cancelled</p>
                                                @endif
                                            </div>

                                            @if ($index < count($steps) - 1)
                                                @php
                                                    $nextStep = $steps[$index + 1];
                                                    $lineGreen = $nextStep['date'];
                                                    $lineRed = $item->status === 'cancelled' && $step['date'] && !$nextStep['date'];
                                                    $lineColor = $lineGreen ? 'bg-green-300'
                                                                : ($lineRed ? 'bg-red-300'
                                                                : 'bg-zinc-200');
                                                @endphp

                                                <div class="flex-1 h-0.5 mt-5 {{ $lineColor }}"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Order Summary --}}
                @unlessrole('seller')
                    <div class="bg-zinc-50 rounded-lg p-4 mb-4 mt-4">
                        <h3 class="text-lg font-semibold mb-2 text-indigo-600">Order Summary</h3>
                        <div class="flex justify-between mb-1">
                            <span>Subtotal</span>
                            <span>{{ Number::currency($order->subtotal, 'PHP') }}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Shipping Fee</span>
                            <span>{{ Number::currency($order->shipping_fee, 'PHP') }}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Tax</span>
                            <span>{{ Number::currency($order->tax, 'PHP') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t border-zinc-200">
                            <span>Total</span>
                            <span>{{ Number::currency($order->total_amount, 'PHP') }}</span>
                        </div>
                    </div>
                @endunlessrole


            {{-- Edit --}}
            @elseif($state === 'Edit')
                <div class="flex justify-start items-center mb-4">
                    <h2 class="text-2xl font-bold text-indigo-700 mr-4">Edit Order: <span class="text-indigo-500">{{ $order->display_name }}</span></h2>
                </div>

                <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between">
                        <div>
                            <div class="mb-2">
                                <span class="font-semibold">Customer:</span> {{ $order->customer->name }}
                            </div>
                            <div>
                                <span class="font-semibold">Status:</span>
                                <x-order-status-badge :$order></x-order-status-badge>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <span class="font-semibold">Date Placed:</span> {{ $order->created_at->timezone('Asia/Manila')->format('F j, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8 transition-all" wire:loading.class="pointer-events-none select-none animate-pulse">

                    <livewire:table
                        wire:key="{{ now() }}"
                        :items="collect($order->orderItemsWrapper())"
                        :$columns
                        :$widths
                        :$cells
                        :$cellData
                        :$columnsToProperty
                        :$noDataText
                        :$customClasses
                    >
                    </livewire:table>
                </div>


                <div class="flex mt-auto gap-2 justify-end">
                    <flux:button variant='danger' size='sm' icon='x-mark'
                        :disabled="$order->overall_status !== 'pending'"
                        wire:click.prevent="$dispatchTo('orders-child-confirm', 'open', {method: 'Cancel All', orderId: '{{ $order->id }}' })"
                        x-on:click.prevent="$flux.modal('orders-child-confirm').show()"
                    >
                        Cancel All Pending
                    </flux:button>

                    @hasanyrole(['seller', 'admin'])
                        <flux:button variant='primary' size='sm' icon='check'
                            :disabled="$order->overall_status !== 'pending'"
                            wire:click.prevent="$dispatchTo('orders-child-confirm', 'open', {method: 'Accept All', orderId: '{{ $order->id }}' })"
                            x-on:click.prevent="$flux.modal('orders-child-confirm').show()"
                        >
                            Accept All Pending
                        </flux:button>
                    @endhasanyrole
                </div>

                <!-- Item Management Table -->
                {{-- <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2 text-indigo-600">Manage Order Items</h3>

                    <div class="overflow-x-auto bg-white rounded-lg shadow border border-zinc-200">
                        <table class="min-w-full divide-y divide-indigo-200">
                            <thead class="bg-indigo-500">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-indigo-100">
                                @foreach($order->orderItems as $item)
                                    <tr class="hover:bg-indigo-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center">
                                                @if($item->product->picture)
                                                    <img src="{{ asset('storage/' . $item->product->picture) }}" alt="{{ $item->product->name }}" class="h-10 w-10 object-cover rounded mr-3">
                                                @else
                                                    <div class="h-10 w-10 bg-zinc-200 rounded mr-3 flex items-center justify-center">
                                                        <flux:icon.photograph class="h-5 w-5 text-zinc-400" />
                                                    </div>
                                                @endif
                                                <span class="font-medium">{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            {{ $item->order_quantity }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            {{ Number::currency($item->product_price, 'PHP') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            {{ Number::currency($item->product_price * $item->order_quantity, 'PHP') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                @if($item->status === 'pending') bg-yellow-200 text-yellow-800
                                                @elseif($item->status === 'accepted') bg-blue-200 text-blue-800
                                                @elseif($item->status === 'shipped') bg-purple-200 text-purple-800
                                                @elseif($item->status === 'delivered') bg-green-200 text-green-800
                                                @else bg-red-200 text-red-800 @endif">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            @if($item->status === 'pending' || (auth()->user()->hasRole('admin') && $item->status !== 'delivered' && $item->status !== 'cancelled'))
                                                <select wire:change="updateItemStatus({{ $item->id }}, $event.target.value)" class="rounded-md border-zinc-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <option value="pending" @if($item->status === 'pending') selected @endif>Pending</option>

                                                    @if($item->status === 'pending' || $item->status === 'accepted' || auth()->user()->hasRole('admin'))
                                                        <option value="accepted" @if($item->status === 'accepted') selected @endif>Accept</option>
                                                    @endif

                                                    @if($item->status === 'accepted' || $item->status === 'shipped' || auth()->user()->hasRole('admin'))
                                                        <option value="shipped" @if($item->status === 'shipped') selected @endif>Ship</option>
                                                    @endif

                                                    @if($item->status === 'shipped' || auth()->user()->hasRole('admin'))
                                                        <option value="delivered" @if($item->status === 'delivered') selected @endif>Deliver</option>
                                                    @endif

                                                    <option value="cancelled" @if($item->status === 'cancelled') selected @endif>Cancel</option>
                                                </select>
                                            @else
                                                <span class="text-zinc-500">No actions available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                @if($order->orderItems->isEmpty())
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 text-center">
                                            No items in this order
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if($order->orderItems->where('status', 'pending')->count() > 0)
                        <div class="mt-4 flex justify-en --}}

            @elseif ($state === 'Delete')
                <div class="flex flex-col flex-1">
                    <div class="mb-2 flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Delete Order</h1>
                    </div>
                    <form class='flex-1 flex flex-col' wire:submit.prevent="deleteOrder">
                        <div class="flex pb-4">
                            <flux:text size='lg'>Are you sure you want to delete <strong>{{ $order->display_name }}</strong>?</flux:text>
                        </div>
                        <div class="flex items-center justify-end mt-auto pt-4 border-t border-gray-100">
                            <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center hover:cursor-pointer" x-on:click="$flux.modals().close()">
                                Cancel
                            </a>
                            <flux:button class="ml-6" type='submit'
                                        variant='danger'
                                        icon='trash'
                                        wire:loading.attr="disabled">
                                {{ __('Delete Order') }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
</flux:modal>
