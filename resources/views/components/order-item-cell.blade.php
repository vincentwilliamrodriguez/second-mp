<div class="flex gap-2 hover:cursor-pointer select-none" wire:click.prevent='$dispatchTo("products-child", "open", {method: "Show", productId: "{{ $product->id }}" })' x-on:click.prevent="$flux.modal('products-child').show()">
    <x-product-image :product="$product" classes='size-16' placeholderSize='size-8'></x-product-image>
    <div>
        <flux:text class='font-black text-lg pb-0.5 hover:cursor-pointer hover:underline'>{{ $product->name }}</flux:text>
        <flux:text class='font-black text-md flex gap-1' variant='subtle'>
            <flux:icon.user class='size-4'></flux:icon.user>
            {{ $product->seller->name }}
        </flux:text>
    </div>
</div>
