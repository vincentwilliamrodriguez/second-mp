<x-tab title="Products">
    <form method="POST" action="{{ route('orders.store') }}" class="flex flex-col items-start w-[70vw] max-w-[600px] p-4">
        @csrf
        <input type="hidden" name="product_id" value="{{$product->id}}">

        <x-validation-errors class="mb-4" />

        <img class="w-[500px] mb-4 self-center" src="{{ Storage::url($product->picture) }}" alt="{{ $product->name }}">
        <h1 class='font-black mb-1 text-2xl'>{{ $product->name }}</h1>
        <div class="bg-slate-500 rounded-full px-2 py-1 text-white text-sm">{{ $product->category }}</div>
        <h2 class='font-black mb-1 text-2xl text-blue-500'>₱{{ $product->price }}</h2>
        <p>{{ $product->description }}</p>
        <div class="flex gap-2">

            @can('create-orders')
                <p class="text-blue-700 font-black">Quantity:</p>
                <x-counter :product="$product"></x-counter>
            @endcan

            <p class="text-gray-600">{{$product->quantity}} pieces in stock</p>
        </div>

        <div class="self-end flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('products.index') }}">
                {{ __('Go Back') }}
            </a>

            @can('create-orders')
                @if ($product->quantity >= 1)
                    <x-button class="ms-4">
                        {{ __('Order') }}
                    </x-button>
                @endif
            @endcan

        </div>
    </form>
</x-tab>
