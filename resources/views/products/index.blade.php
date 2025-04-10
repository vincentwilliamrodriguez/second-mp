
<x-tab title="{{ (auth()->user()->hasRole('seller')) ? 'My Products' : 'All Products' }}">
    <div class="flex flex-col p-8 gap-4 w-[90vw] max-w-[1200px]">
        @if(session('message'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-2">
            @can('create-products')
                <x-button
                    onclick="window.location.href='{{ route('products.create') }}'"
                    class="w-[100px] mb-4">

                    Create Product
                </x-button>
            @endcan
        </div>

        <div class="flex flex-wrap gap-4">
            @foreach ($products as $product)
                <div class="mb-4 flex flex-col border border-gray-200 hover:bg-gray-100 p-4 relative">
                    <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-0 pointer-events-auto"></a>

                    <img class="w-[200px]" src="{{ Storage::url($product->picture) }}" alt="{{ $product->name }}">
                    <p class="text-blue-700 mb-2">Sold by <strong>{{ $product->seller->username }}</strong></p>

                    @foreach ($product->getAttributes() as $key => $value)
                        @if (in_array($key, ['name', 'category', 'price']))
                            <div class="flex gap-1">
                                <strong>{{ $key }}:</strong> {{ $value }}
                            </div>
                        @endif
                    @endforeach

                    <div class="flex gap-1">
                        <strong>Orders:</strong>
                        @foreach ($product->orders as $order)
                            {{ $order->customer->username }}
                        @endforeach
                    </div>

                    <div class="flex gap-4 mt-2">
                        @can('update-products')
                            <x-button class="z-20 relative pointer-events-auto"
                                    onclick="window.location.href='{{ route('products.edit', $product) }}'">
                                Edit
                            </x-button>
                        @endcan

                        @can('delete-products')
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline z-20 relative pointer-events-auto">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                    Delete
                                </x-button>
                            </form>
                        @endcan
                    </div>
                </div>

            @endforeach
        </div>

        {{ $products->links() }}
    </div>
</x-tab>
