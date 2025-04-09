<x-tab title="Products">
    <div class="flex flex-col p-8 gap-4 w-[90vw] max-w-[1200px]">
        @if(session('message'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif
        
        @can('create-products')
            <x-button
                onclick="window.location.href='{{ route('products.create') }}'"
                class="w-[100px] mb-4">

                Create Product
            </x-button>
        @endcan

        @foreach ($products as $product)
            <div class="mb-4 flex flex-col">
                <img class="w-[200px]" src="{{ Storage::url($product->picture) }}" alt="{{ $product->name }}">
                <a href="{{ route('products.show', $product) }}" class="text-blue-700 mb-1">Visit</a>

                @foreach ($product->getAttributes() as $key => $value)
                    @if (in_array($key, ['name', 'category', 'price']))
                        <div class="flex gap-1">
                            <strong>{{ $key }}: </strong> {{ $value }}
                        </div>
                    @endif
                @endforeach

                <div class="flex gap-4">
                    @can('update-products')
                        <x-button
                            onclick="window.location.href='{{ route('products.edit', $product) }}'">

                            Edit

                        </x-button>
                    @endcan

                    @can('delete-products')
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
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

        {{ $products->links() }}
    </div>
</x-tab>
