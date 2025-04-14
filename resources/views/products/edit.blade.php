<x-tab title="Edit Product">
    <div class="w-[70vw] max-w-[700px] p-6 bg-white rounded-lg shadow-sm mx-auto">
        <div class="mb-6 flex items-center">
            <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
        </div>

        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <x-label for="name" value="{{ __('Name') }}" class="flex items-center gap-1 mb-1">
                    <x-eos-shopping-bag class="w-4 h-4 text-blue-600" />
                    <span>Product Name</span>
                </x-label>
                <x-input id="name" class="block w-full" type="text" name="name" :value="$product->name" required autofocus maxlength="40" placeholder="Enter product name" />
            </div>

            <div class="mb-5">
                <x-label for="description" value="{{ __('Description') }}" class="flex items-center gap-1 mb-1"></x-label>
                <textarea id="description" class="resize-none block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                    name="description" rows="4" maxlength="250" placeholder="Describe your product">{{ $product->description }}</textarea>
                <div class="mt-1 text-xs text-gray-500 flex justify-end">
                    <span id="char-count">0</span>/250 characters
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <x-label for="quantity" value="{{ __('Quantity Available') }}" class="flex items-center gap-1 mb-1"></x-label>
                    <x-input id="quantity" class="block w-full" type="number" min='0' max='10000000' name="quantity" :value="$product->quantity" required placeholder="0" />
                </div>

                <div>
                    <x-label for="price" value="{{ __('Price') }}" class="flex items-center gap-1 mb-1"></x-label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">₱</span>
                        </div>
                        <x-input id="price" class="block w-full pl-8" type="number" min='0' max='100000000' name="price"
                            pattern="[0-9]+([\.,][0-9]+)?" step="0.01" :value="$product->price" required placeholder="0.00" />
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <x-label for="category" value="{{ __('Category') }}" class="flex items-center gap-1 mb-1"></x-label>
                <x-dropdown-wrapper
                    :defaultText="'Select a Category'"
                    :options="$categories"
                    setValue="{{ $product->category }}"
                    name="category"
                    align="left"
                    width="60"
                ></x-dropdown-wrapper>
            </div>

            <div class="mb-6">
                <x-label for="picture" value="{{ __('Product Image (Optional)') }}" class="flex items-center gap-1 mb-1"></x-label>

                <div class="mt-1 relative border border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors">
                    <input type="file" id="picture" name="picture"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        accept="image/*"
                        onchange="updateFileLabel(this)">
                    <div class="p-4 flex items-center justify-center">
                        <div id="file-placeholder" class="text-center">
                            <x-eos-cloud-upload class="w-8 h-8 mx-auto text-blue-500 mb-2" />
                            <span class="text-gray-600">{{ $product->picture ? 'Change product image' : 'Click to upload image' }}</span>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (Max. 5MB)</p>
                        </div>
                        <div id="file-selected" class="text-center hidden">
                            <div class="flex items-center">
                                <x-eos-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span id="file-name" class="text-gray-800 font-medium truncate max-w-xs"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $product->picture ? 'Upload a new image to replace the current one' : '' }}</p>
            </div>

            <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-100">
                <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center" href="{{ route('products.index') }}">
                    <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" />
                    Back to Products
                </a>

                <x-button class="ml-6 bg-blue-600 hover:bg-blue-700">
                    <x-eos-save class="w-4 h-4 mr-1" />
                    {{ __('Update Product') }}
                </x-button>
            </div>
        </form>
    </div>

    <script>
        function updateFileLabel(input) {
            const placeholderElement = document.getElementById('file-placeholder');
            const selectedElement = document.getElementById('file-selected');
            const fileNameElement = document.getElementById('file-name');

            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                fileNameElement.textContent = fileName;
                placeholderElement.classList.add('hidden');
                selectedElement.classList.remove('hidden');
            } else {
                placeholderElement.classList.remove('hidden');
                selectedElement.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('description');
            const charCount = document.getElementById('char-count');

            function updateCount() {
                charCount.textContent = textarea.value.length;
            }

            textarea.addEventListener('input', updateCount);
            updateCount();
        });
    </script>
</x-tab>
