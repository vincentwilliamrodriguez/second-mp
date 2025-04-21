<div class="flex gap-2 hover:cursor-pointer select-none" wire:click.prevent='$dispatchTo("products-child", "open", {method: "Show", productId: "{{ $product->id }}" })' x-on:click.prevent="$flux.modal('products-child').show()">
    <x-product-image :product="$product" classes='size-16' placeholderSize='size-8'></x-product-image>
    <h3 class='font-black text-lg hover:cursor-pointer hover:underline'>{{ $product->name }}</h3>
</div>
