@props(['product'])

<div class="relative bg-white rounded-lg shadow-sm border-2 border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md hover:transform hover:translate-y-[-4px] hover:border-blue-300">
    <div class="h-48 overflow-hidden bg-gradient-to-br from-blue-50 to-gray-100">
        <a href="{{ route('products.show', $product) }}" class="block h-full">

            @if(isset($product->picture) && Storage::disk('public')->exists($product->picture))
                <img class="w-full h-full object-cover"
                     src="{{ Storage::url($product->picture) }}"
                     alt="{{ $product->name }}"
                     onerror="this.onerror=null; this.parentElement.classList.add('placeholder-active');">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <div class="text-center p-4 flex flex-col justify-center">
                        <x-eos-image class="h-28 w-28 text-gray-200"/>
                    </div>
                </div>
            @endif
        </a>
    </div>

    <div class="p-4">
        <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $product->name }}</h3>

        <div class="text-blue-600 text-sm mb-3 flex gap-1 items-center">
            <div class="text-gray-500 w-4 h-4"><x-eos-person/></div>
            <span class="font-medium">{{ $product->seller->username }}</span>
        </div>

        <div class="flex items-center text-sm text-gray-600 mb-2">
            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-xs">{{ $product->category }}</span>
        </div>

        <div class="text-lg font-bold text-blue-800">
            ₱{{ number_format($product->price, 2) }}
        </div>

        <div class="flex gap-2 mt-4">
            @can('update-products')
                <x-button class="z-10 relative opacity-90"
                        onclick="window.location.href='{{ route('products.edit', $product) }}'"
                        :baseColor="'blue'">
                    <x-slot name='icon'><x-eos-edit/></x-slot>
                    Edit
                </x-button>
            @endcan

            @can('delete-products')
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline z-10 relative">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" class="z-10 relative opacity-90"
                            onclick="return confirm('Are you sure you want to delete this item?')"
                            :baseColor="'red'">
                        <x-slot name='icon'><x-eos-delete /></x-slot>
                        Delete
                    </x-button>
                </form>
            @endcan
        </div>
    </div>

    <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-0" aria-label="View {{ $product->name }}"></a>
</div>

<style>
    .placeholder-active img {
        display: none;
    }

    .placeholder-active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #f0f5ff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
