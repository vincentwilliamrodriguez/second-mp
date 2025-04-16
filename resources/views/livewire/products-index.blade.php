<div class="flex flex-col p-8 gap-4 w-[90vw] max-w-[1200px]">
    @if(session('message'))
        <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-2">
        <h2 class="font-black text-3xl">
            {{ (auth()->user()->hasRole('seller')) ? 'My Products' : 'All Products' }}
        </h2>

        @if(auth()->user()->can('create-products') && !$products->isEmpty())

            <x-button
                onclick="window.location.href='{{ route('products.create') }}'"
                :baseColor="'blue'" :iconSize="'w-6 h-6'">

                <x-slot name='icon'><x-eos-add-box-o/></x-slot>
                Create

            </x-button>

        @endif
    </div>

    @if(auth()->user()->can('create-products') && $products->isEmpty())
        <div class="col-span-full flex items-center justify-center">
            <a href="{{ route('products.create') }}" class="group w-full md:w-2/3 lg:w-1/2 h-64 border-2 border-dashed border-blue-300 rounded-lg flex flex-col items-center justify-center p-6 transition-all hover:border-blue-500 hover:bg-blue-50">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                    <x-eos-add class="w-10 h-10 text-blue-600" />
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2 pointer-events-none select-none">No Products Yet</h3>
                <p class="text-gray-600 text-center mb-4 pointer-events-none select-none">Add your first product to start selling</p>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 auto-rows-[400px] justify-center gap-6 mb-8">
            @foreach ($products as $product)
                <x-product-card :product="$product"></x-product-card>
            @endforeach
        </div>
    @endif

    @if(!$products->isEmpty())
        {{ $products->links() }}
    @endif
</div>
