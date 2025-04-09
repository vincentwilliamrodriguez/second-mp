<x-tab title="Products">

    <div class="w-[70vw] max-w-[700px] p-4">
        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$product->name" required autofocus/>
            </div>

            <div class="mt-4">
                <x-label for="description" value="{{ __('Description') }}" />
                <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="$product->description" autofocus/>
            </div>

            <div class="mt-4">
                <x-label for="quantity" value="{{ __('Quantity Available') }}" />
                <x-input id="quantity" class="block mt-1 w-full" type="number" min='1' max='10000000' name="quantity" :value="$product->quantity" required/>
            </div>

            <div class="mt-4">
                <x-label for="price" value="{{ __('Price') }}" />
                <x-input id="price" class="block mt-1 w-full" type="number" min='0' max='100000000' name="price" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" min='0' :value="$product->price" required autofocus/>
            </div>

            <div class="mt-4">
                <x-label for="category" value="{{ __('Category') }}" />
                <x-dropdown-wrapper
                    :defaultText="'Select a Category'"
                    :options="$categories"
                    setValue="{{ $product->category }}"
                    name="category"
                    align="left"
                    width="60"
                ></x-dropdown-wrapper>
            </div>

            <div class="mt-4">
                <x-label for="picture" value="{{ __('Picture (Optional)') }}" />

                @if($product->picture)
                    <div class="mb-2">
                        <p class="text-sm text-gray-600 mb-1">Current image:</p>
                        <img src="{{ Storage::url($product->picture) }}" alt="{{ $product->name }}" class="h-32 object-contain">
                    </div>
                @endif

                <x-input id="picture" class="block mt-1 w-full rounded-none" type="file" name="picture" autofocus/>
                <p class="text-xs text-gray-500 mt-1">Upload a new image to replace the current one</p>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('products.index') }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Update') }}
                </x-button>
            </div>
        </form>
    </div>
</x-tab>
