<div class='flex flex-col'>
    <h3 class='font-black text-md hover:cursor-pointer hover:underline'
            wire:click.prevent='$dispatchTo("products-child", "openShow", {productId: "{{ $product->id }}" })'
            x-on:click.prevent="$flux.modal('products-child').show()">

        {{ $product->name }}
    </h3>

    <div class="hidden flex-shrink-0 relative bg-gradient-to-br from-blue-50 to-gray-100 rounded-lg overflow-hidden">
        @if(isset($product->picture) && Storage::disk('public')->exists($product->picture))
            <img
                class="max-h-20 max-w-20 object-cover"
                src="{{ Storage::url($product->picture) }}"
                alt="{{ $product->name }}"
                onload="this.naturalWidth / this.naturalHeight > 1.2 ?
                    this.classList.replace('object-contain', 'object-cover') :
                    this.classList.replace('object-contain', 'object-contain')"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"
            >
            <div class="hidden absolute inset-0 items-center justify-center">
                <x-eos-image class="size-20 text-gray-200"/>
            </div>
        @else
            <div class="flex items-center justify-center h-[200px] w-[150px]">
                <x-eos-image class="size-20 text-gray-200"/>
            </div>
        @endif
    </div>
</div>
