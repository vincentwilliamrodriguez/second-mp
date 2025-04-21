<flux:modal variant='flyout' name='cart-flyout' class="fixed !max-w-none !pb-6 h-screen">
    <div class="bg-white shadow-sm max-w-5xl mx-auto pt-4 min-w-[500px] h-full flex-1 flex flex-col">
        <div class="flex flex-col flex-1 -mt-6">
            <div class="mb-2 flex items-center border-b border-zinc-200 pb-2">
                <h1 class="text-2xl font-bold text-gray-800">Your ShopStream Cart</h1>
            </div>

            <div class="flex-1 flex flex-col divide-y divide-zinc-100 max-h-[70vh] overflow-y-auto @if(empty($cartItems)) max-h-none @endif">
                @forelse ($cartItems as $cartItem)
                    <div class="grid grid-cols-[100px_1fr_auto] gap-4 py-4">
                        <livewire:product-image :product='$this->retrieveProduct($cartItem)' placeholderSize='size-16' />

                        <div class="flex flex-col justify-center">
                            <h2 class="max-w-[250px] text-lg font-medium text-gray-800 truncate whitespace-nowrap overflow-hidden">
                                {{ $cartItem['product_name'] }}
                            </h2>
                            <p class="text-sm text-zinc-500">Qty. {{ $cartItem['order_quantity'] }} @ {{ Number::currency($cartItem['product_price'], 'PHP') }} </p>
                        </div>

                        <div class="text-right flex items-center justify-end pr-2">
                            <span class="text-lg font-semibold text-indigo-700">
                                {{ Number::currency($cartItem['product_price'] * $cartItem['order_quantity'], 'PHP') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex flex-col justify-center items-center gap-4">
                        <flux:icon.cube-transparent class="size-28 text-zinc-300"/>
                        <flux:text class="text-lg max-w-[60%] text-center" variant='subtle'>
                            Oops! Your cart is empty. You may add an item from the <a href="{{ route('products.index') }}" class="text-indigo-500 hover:underline">Shop</a>.
                        </flux:text>
                    </div>
                @endforelse
            </div>
        </div>

        @if (!empty($cartItems))
            <div class="flex mt-auto items-center justify-end pt-4 border-t border-zinc-200">
                <a href="{{ route('cart') }}">
                    <flux:button variant='subtle'>
                        View More Details
                    </flux:button>
                </a>

                <a href="{{ route('checkout') }}">
                    <flux:button variant='primary' icon='shopping-bag' class="ml-6 bg-blue-600 hover:bg-blue-700">
                        {{ __('Check Out') }}
                    </flux:button>
                </a>
            </div>
        @endif
    </div>
</flux:modal>
