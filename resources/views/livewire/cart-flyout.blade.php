<flux:modal variant='flyout' name='cart-flyout' class="fixed !max-w-none !pb-6 h-screen"  x-if="open">
    <div class="bg-white shadow-sm max-w-5xl mx-auto pt-4 min-w-[500px] h-full flex-1 flex flex-col">
        <div class="flex flex-col flex-1 -mt-6">
            <div class="mb-2 flex items-center">
                <h1 class="text-2xl font-bold text-gray-800">Your ShopStream Cart</h1>
            </div>

            <div class="flex flex-col divide-y divide-zinc-200 max-h-[70vh] overflow-y-scroll">
                @foreach ($cartItems as $cartItem)
                    <div class="grid grid-cols-[100px_1fr_auto] gap-4 py-4">
                        <livewire:product-image :product='$this->retrieveProduct($cartItem)' placeholderSize='size-16' />

                        <div class="flex flex-col justify-center">
                            <h2 class="max-w-[250px] text-lg font-medium text-gray-800 truncate whitespace-nowrap overflow-hidden">
                                {{ $cartItem['product_name'] }}
                            </h2>
                            <p class="text-sm text-zinc-500">x{{ $cartItem['order_quantity'] }}</p>
                        </div>

                        <div class="text-right flex items-center justify-end">
                            <span class="text-lg font-semibold text-indigo-700">
                                ₱{{ number_format($cartItem['product_price'] * $cartItem['order_quantity'], 2) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div class="flex mt-auto items-center justify-end pt-4 border-t border-gray-100">
            <flux:button variant='subtle' x-on:click="$flux.modals().close()">
                View More Details
            </flux:button>

            <flux:button variant='primary' icon='shopping-bag' class="ml-6 bg-blue-600 hover:bg-blue-700">
                {{ __('Check Out') }}
            </flux:button>
        </div>
    </div>
</flux:modal>
