{{-- This is the new show/create/edit product page with Livewire --}}

<div class="bg-white rounded-lg shadow-sm max-w-5xl mx-auto p-6 w-[700px]">
    <form method="POST" action="{{ route('orders.store') }}" class="flex flex-col lg:flex-row gap-8">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">


        <div class="flex-shrink-0 w-full lg:w-2/5 aspect-square relative bg-gradient-to-br from-blue-50 to-gray-100 rounded-lg overflow-hidden">
            @if(isset($product->picture) && Storage::disk('public')->exists($product->picture))
                <img
                    class="w-full h-full object-cover"
                    src="{{ Storage::url($product->picture) }}"
                    alt="{{ $product->name }}"
                    onload="this.naturalWidth / this.naturalHeight > 1.2 ?
                        this.classList.replace('object-contain', 'object-cover') :
                        this.classList.replace('object-contain', 'object-contain')"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"
                >
                <div class="hidden absolute inset-0 items-center justify-center">
                    <x-eos-image class="h-28 w-28 text-gray-200"/>
                </div>
            @else
                <div class="flex items-center justify-center h-full">
                    <x-eos-image class="h-28 w-28 text-gray-200"/>
                </div>
            @endif
        </div>

        <div class="flex-grow flex flex-col">
            <x-validation-errors class="mb-4" />

            <div class="mb-6">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="font-bold text-2xl text-gray-800">{{ $product->name }}</h1>
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm">{{ $product->category }}</span>
                </div>

                <h2 class="font-bold text-2xl text-blue-600 mb-4">₱{{ number_format($product->price, 2) }}</h2>

                <p class="text-gray-600 mb-4">
                    Sold by <span class="font-medium text-blue-600">{{ $product->seller->username }}</span>
                </p>

                <div class="mb-6 text-gray-700">
                    @php
                        $truncated = strlen($product->description) > $maxDescriptionLength;
                        $displayText = $truncated ? substr($product->description, 0, $maxDescriptionLength) . '...' : $product->description;
                    @endphp

                    <h3 class="font-black mb-2">Description</h3>
                    <p id="short-description" class="break-words overflow-wrap hyphens-auto">{{ $displayText }}</p>

                    @if($truncated)
                        <p id="full-description" class="hidden break-words overflow-wrap hyphens-auto">{{ $product->description }}</p>
                        <button
                            type="button"
                            id="read-more-btn"
                            class="text-blue-600 hover:text-blue-800 text-sm mt-2 focus:outline-none"
                            onclick="document.getElementById('short-description').classList.toggle('hidden');
                                    document.getElementById('full-description').classList.toggle('hidden');
                                    this.textContent = this.textContent === 'Read more' ? 'Read less' : 'Read more';"
                        >Read more</button>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap gap-4 items-center mb-6">
                @can('create-orders')
                    <div class="flex items-center gap-3 w-48">
                        <span class="text-gray-700 font-black">Quantity:</span>
                        <livewire:counter :max="$product->quantity" wire:model="quantity"/>
                        <input type="hidden" name="quantity" :value="$wire.quantity">
                    </div>
                @endcan

                <div class="flex items-center">
                    <span class="text-gray-600">
                        <span class="font-medium">{{ $product->quantity }}</span> pieces in stock
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center" href="{{ route('products.index') }}">
                    <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" />
                    Back to Products
                </a>

                @can('create-orders')
                    @if ($product->quantity >= 1)
                        <x-button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2">
                            <x-eos-shopping-cart-o class="h-6 w-6 mr-1 opacity-90" />
                            Add to Cart
                        </x-button>
                    @else
                        <span class="text-red-500 font-medium">Out of Stock</span>
                    @endif
                @endcan
            </div>
        </div>
    </form>
</div>
