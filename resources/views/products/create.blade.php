<x-tab title="Products">

    <div class="w-[70vw] max-w-[700px] p-4">
        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus maxlength="40" />
            </div>

            <div class="mt-4">
                <x-label for="description" value="{{ __('Description') }}" />
                <textarea id="description" class="resize-none block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" autofocus cols="30" rows="5" maxlength="250">{{ old('description') }}</textarea>
            </div>

            <div class="mt-4">
                <x-label for="quantity" value="{{ __('Quantity Available') }}" />
                <x-input id="quantity" class="block mt-1 w-full" type="number" min='0' max='10000000' name="quantity" :value="old('quantity')" required/>
            </div>

            <div class="mt-4">
                <x-label for="price" value="{{ __('Price') }}" />
                <x-input id="price" class="block mt-1 w-full" type="number" min='0' max='100000000' name="price" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" min='0' :value="old('price')" required autofocus/>
            </div>

            <div class="mt-4">
                <x-label for="category" value="{{ __('Category') }}" />
                <x-dropdown-wrapper
                    :defaultText="'Select a Category'"
                    :options="$categories"
                    name="category"
                    align="left"
                    width="60"
                ></x-dropdown-wrapper>
            </div>

            <div class="mt-4">
                <x-label for="picture" value="{{ __('Picture (Optional)') }}" />
                <x-input id="picture" class="block mt-1 w-full rounded-none" type="file" name="picture" :value="old('picture')" autofocus/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('products.index') }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Create') }}
                </x-button>
            </div>
        </form>
    </div>
</x-tab>
