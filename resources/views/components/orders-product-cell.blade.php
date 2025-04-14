<div class='flex flex-col gap-2'>
    <a href="{{ route('products.show', $product) }}">
        <h3 class='font-black text-lg hover:cursor-pointer hover:underline'>{{ $order->product->name }}</h3>
    </a>
    <p class="mb-2"><strong>{{ $order->product->quantity }}</strong> in stock</p>

    <div class="flex-shrink-0 self-center relative bg-gradient-to-br from-blue-50 to-gray-100 rounded-lg overflow-hidden">
        @if(isset($product->picture) && Storage::disk('public')->exists($product->picture))
            <img
                class="max-h-[200px] max-w-[150px] object-cover"
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
            <div class="flex items-center justify-center h-[200px] w-[150px]">
                <x-eos-image class="h-28 w-28 text-gray-200"/>
            </div>
        @endif
    </div>
</div>
