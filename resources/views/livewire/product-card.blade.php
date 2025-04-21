{{-- This is the new Product Card component that uses Livewire --}}

<div class="relative flex flex-col bg-white rounded-lg shadow-sm border-2 border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md hover:transform hover:translate-y-[-4px] hover:border-blue-300">
    <livewire:product-image :$product classes='h-48 border-b border-gray-200'></livewire:product-image>

    <div class="p-4 flex-1 flex flex-col">
        <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $product->name }}</h3>

        <div class="text-blue-600 text-sm mb-3 flex gap-1 items-center">
            <div class="text-gray-500 w-4 h-4"><x-eos-person/></div>
            <span class="font-medium">{{ $product->seller->username }}</span>
        </div>

        <div class="flex items-center gap-1 w-min bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-xs mb-2">
            <x-dynamic-component class="size-4"
                :component="'flux::icon.' . Str::of($categoryValues[$product->category][1])" />
            {{ $product->category }}
        </div>

        <div class="text-lg font-bold text-blue-800">
            ₱{{ number_format($product->price, 2) }}
        </div>

        <div class="text-sm font-medium mt-auto text-zinc-400">
            <strong>{{ $product->quantity }}</strong> in stock
        </div>


        {{-- These are the old Edit and Delete Products button for sellers and admins--}}

        {{-- <div class="flex gap-2 mt-4">
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
        </div> --}}
    </div>

    {{-- This redirects the user to the Products Show page using Livewire events --}}
    <div class="absolute inset-0 z-0 hover:cursor-pointer" wire:click.prevent='$dispatchTo("products-child", "open", {method: "Show", productId: "{{ $product->id }}" })' x-on:click.prevent="$flux.modal('products-child').show()"></div>


    {{-- This is the old way of redirecting the user to the Products Show page --}}
    {{-- <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-0" aria-label="View {{ $product->name }}"></a> --}}
</div>
