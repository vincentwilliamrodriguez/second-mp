{{-- This is the new show/create/edit product page with Livewire --}}

<flux:modal name='products-child' class="fixed !max-w-none" x-on:open="$wire.state = null; method=$event.detail.method" wire:close="$dispatch('resetform'); method=''" x-data="{method: '', defaultSizes: {
            'Show': '!min-w-[750px] !min-h-[400px]',
            'Create': '!min-w-[750px] !min-h-[400px]',
            'Edit': '!min-w-[750px] !min-h-[400px]',
            'Delete': '!min-w-[400px] !min-h-[150px]',
        }}">

    <div x-bind:class="'bg-white rounded-lg shadow-sm max-w-5xl mx-auto pt-4 flex flex-col ' + defaultSizes[method]">
        {{-- Loading Indicator --}}
        <div x-cloak x-show="$wire.state === null" class="absolute top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2">
            <div class="flex gap-4 justify-center items-center">
                <span class="text-zinc-400 text-xl pb-1">Loading</span>
                <flux:icon.arrow-path class="w-4 h-4 animate-spin text-zinc-400" />
            </div>
        </div>


        <div x-cloak x-show="$wire.state !== null" class="flex flex-col flex-1 -mt-4">

            {{-- Show --}}
            @if ($state === 'Show' && $product !== null)
                <form class="flex-1 flex flex-row gap-8 max-w-[750px]" wire:submit.prevent='addToCart'>

                    <livewire:product-image classes='flex-shrink-0 !min-w-[40%] !max-w-[40%] aspect-square relative bg-gradient-to-br from-blue-50 to-gray-100 rounded-lg overflow-hidden' :$product></livewire:product-image>

                    <div class="flex-grow flex flex-col">
                        <div class="mb-6">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <h1 class="font-bold text-2xl text-gray-800">{{ $product->name }}</h1>
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm">{{ $product->category }}</span>
                            </div>
                            <h2 class="font-bold text-2xl text-blue-600 mb-4">₱{{ number_format($product->price, 2) }}</h2>
                            <p class="text-gray-600 mb-4">
                                Sold by <span class="font-medium text-blue-600">{{ $product->seller->name }}</span>
                            </p>
                            <div class="mb-6 text-gray-700">
                                @php
                                    $truncated = strlen($product->description) > $maxDescriptionLength;
                                    $displayText = $truncated ? substr($product->description, 0, $maxDescriptionLength) . '...' : $product->description;
                                @endphp
                                <h3 class="font-black mb-2">Description</h3>
                                <p id="short-description" class="break-words overflow-wrap hyphens-auto">
                                    {{ $displayText }}

                                    @if (!$displayText)
                                        <span class="italic text-zinc-400">No description available.</span>
                                    @endif
                                </p>
                                @if($truncated)
                                    <p id="full-description" class="hidden break-words overflow-wrap hyphens-auto">{{ $product->description }}</p>
                                    <button
                                        type="button"
                                        id="read-more-btn"
                                        class="text-blue-600 hover:text-blue-800 text-sm mt-2 focus:outline-none"
                                        onclick="document.getElementById('short-description').classList.toggle('hidden');
                                                document.getElementById('full-description').classList.toggle('hidden');
                                                this.textContent = this.textContent === 'Read more' ? 'Read less' : 'Read more';"
                                    >Read more</button>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4 items-center mb-6">
                            @can('create-orders')
                                <div class="flex items-center gap-3 w-48">
                                    <span class="text-gray-700 font-black">Quantity:</span>
                                    <livewire:counter wire:model="orderQuantity"
                                                    :max="$product->quantity"
                                                    :key='$product->id' />
                                </div>
                            @endcan
                            <div class="flex items-center">
                                <span class="text-gray-600">
                                    <span class="font-medium">{{ $product->quantity }}</span> pieces in stock
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center {{ auth()->user()->can('create-orders') ? 'justify-between' : 'justify-end'}} mt-auto pt-4 border-t border-gray-100">
                            <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center hover:cursor-pointer" x-on:click="$flux.modals().close()">
                                <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" />
                                Back to Products
                            </a>
                            @can('create-orders')
                                @if ($product->quantity >= 1)
                                    <x-button type='submit' class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2">
                                        <x-eos-shopping-cart-o class="h-6 w-6 mr-1 opacity-90" />
                                        Add to Cart
                                    </x-button>
                                @else
                                    <span class="text-red-500 font-medium">Out of Stock</span>
                                @endif
                            @endcan
                        </div>
                    </div>
                </form>


            {{-- Create/Edit --}}
            @elseif (in_array($state, ['Create', 'Edit']))
                <div class="mb-6 flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $state }} Product</h1>
                </div>


                <form class='max-w-[800px]' wire:submit.prevent="saveProduct">

                    {{-- These are new input fields that use Livewire and Flux --}}

                    <flux:input
                        wire:model.live.debounce.500ms="item.name"
                        :label="__('Name')"
                        type="text"
                        :placeholder="__('Enter product name')"
                        required
                        autofocus
                        maxlength="40"
                        class="mb-2"
                    />

                    <div class="mb-5" x-data="{description: $wire.item.description ?? ''}">
                        <flux:textarea
                            wire:model.live.debounce.500ms="item.description"
                            :label="__('Description')"
                            :placeholder="__('Describe your product')"
                            rows="4"
                            maxlength="250"
                            resize='none'
                            x-model="description"
                        />
                        <div class="mt-1 text-xs text-gray-500 flex justify-end">
                            <span x-text='description.length'></span>/250 characters
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <flux:input
                            wire:model.live.debounce.500ms="item.quantity"
                            type="number"
                            :label="__('Quantity Available')"
                            :placeholder="__('0')"
                            required
                            min='0'
                            max='10000000'
                        />

                        <flux:input
                            wire:model.live.debounce.500ms="item.price"
                            type="number"
                            :label="__('Price')"
                            :placeholder="__('0.00')"
                            required
                            icon='philippine-peso'
                            min='0'
                            max='10000000'
                            pattern="[0-9]+([\.,][0-9]+)?"
                            step="0.01"
                        />
                    </div>

                    <div class="mb-5 w-full">
                        <flux:label for="category">Category</flux:label>

                        <flux:dropdown>
                            <flux:button id='category' variant='ghost' icon:trailing="chevron-down" class="pl-2 w-full border border-zinc-200 !justify-start" x-bind:class="(($wire.item['category'] ?? '') === '') ? '!text-zinc-400' : ''">
                                <span class="!select-none"
                                    x-text='$wire.item.category ? $wire.categoryValues[$wire.item.category][0] : "Select a Category"'></span>
                            </flux:button>
                            <flux:menu class="shadow-xl">
                                <flux:menu.radio.group wire:model.live='item.category'>
                                    @foreach ($categoryValues as $categoryName => $categoryData)
                                        <flux:menu.radio
                                            :class="'hover:bg-zinc-100 hover:cursor-pointer [&>*:first-child]:hidden '.((($item['category'] ?? '') ===
                                                $categoryName) ? '!text-blue-500' : '')"
                                            :value='$categoryName' :icon:trailing='$categoryData[1]'
                                        >
                                            {{ $categoryData[0] }}
                                        </flux:menu.radio>
                                    @endforeach
                                </flux:menu>
                            </flux:menu>
                        </flux:dropdown>

                        <flux:error name="item.category" />
                    </div>


                    {{-- These are old input fields that used basic Blade components --}}

                    {{-- <div class="mb-5">
                        <x-label for="name" value="{{ __('Name') }}" class="flex items-center gap-1 mb-1">
                            <x-eos-shopping-bag class="w-4 h-4 text-blue-600" />
                            <span>Product Name</span>
                        </x-label>
                        <x-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus maxlength="40" placeholder="Enter product name" />
                    </div>

                    <div class="mb-5" x-data="{description: ''}">
                        <x-label for="description" value="{{ __('Description') }}" class="flex items-center gap-1 mb-1"></x-label>
                        <textarea id="description" class="resize-none block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            name="description" rows="4" maxlength="250" placeholder="Describe your product" x-model='description'>{{ old('description') }}</textarea>
                        <div class="mt-1 text-xs text-gray-500 flex justify-end">
                            <span x-text='description.length'></span>/250 characters
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <x-label for="quantity" value="{{ __('Quantity Available') }}" class="flex items-center gap-1 mb-1"></x-label>
                            <x-input id="quantity" class="block w-full" type="number" min='0' max='10000000' name="quantity" :value="old('quantity')" required placeholder="0" />
                        </div>

                        <div>
                            <x-label for="price" value="{{ __('Price') }}" class="flex items-center gap-1 mb-1"></x-label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">₱</span>
                                </div>
                                <x-input id="price" class="block w-full pl-8" type="number" min='0' max='10000000' name="price"
                                    pattern="[0-9]+([\.,][0-9]+)?" step="0.01" :value="old('price')" required placeholder="0.00" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <x-label for="category" value="{{ __('Category') }}" class="flex items-center gap-1 mb-1"></x-label>
                        <x-dropdown-wrapper
                            :defaultText="'Select a Category'"
                            :options="$categories"
                            name="category"
                            align="left"
                            width="60"
                        ></x-dropdown-wrapper>
                    </div> --}}


                    {{-- This is the new way of making a custom file upload using Alpine --}}

                    <div x-data="{ fileName: '', fileSelected: false }" class="mb-6">
                        <x-label for="picture" value="{{ __('Product Image (Optional)') }}" class="flex items-center gap-1 mb-1" />

                        <div class="mt-1 relative border border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors">
                            <input
                                type="file"
                                id="picture"
                                wire:model="item.picture"
                                accept="image/*"
                                @change="fileName = $event.target.files[0]?.name || ''; fileSelected = !!fileName;"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            >

                            <div class="p-4 flex items-center justify-center">
                                <div x-show="!fileSelected" id="file-placeholder" class="text-center">
                                    <x-eos-cloud-upload class="w-8 h-8 mx-auto text-blue-500 mb-2" />
                                    <span class="text-gray-600">Click to upload image</span>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (Max. 2MB)</p>
                                </div>

                                <div x-show="fileSelected" id="file-selected" class="text-center">
                                    <div class="flex items-center">
                                        <x-eos-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                        <span x-text="fileName" class="text-gray-800 font-medium truncate max-w-xs"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div wire:loading wire:target="item.picture" class="text-sm text-zinc-400 mt-2">
                            Uploading image...
                        </div>

                        <flux:error name="item.picture" />
                    </div>



                    {{-- This is the old way of creating a custom file upload using vanilla JavaScript --}}

                    {{-- <div class="mb-6">
                        <x-label for="picture" value="{{ __('Product Image (Optional)') }}" class="flex items-center gap-1 mb-1"></x-label>

                        <div class="mt-1 relative border border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors">
                            <input type="file" id="picture" name="picture"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                accept="image/*"
                                onchange="updateFileLabel(this)">
                            <div class="p-4 flex items-center justify-center">
                                <div id="file-placeholder" class="text-center">
                                    <x-eos-cloud-upload class="w-8 h-8 mx-auto text-blue-500 mb-2" />
                                    <span class="text-gray-600">Click to upload image</span>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (Max. 2MB)</p>
                                </div>
                                <div id="file-selected" class="text-center hidden">
                                    <div class="flex items-center">
                                        <x-eos-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                        <span id="file-name" class="text-gray-800 font-medium truncate max-w-xs"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-100">
                        <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center hover:cursor-pointer" x-on:click="$flux.modals().close()">
                            <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" />
                            Back to Products
                        </a>

                        <x-button class="ml-6 bg-blue-600 hover:bg-blue-700" type='submit'
                                    wire:loading.attr="disabled"
                                    wire:target="{{ implode(', ', array_keys($validationAttributes)) }}, saveProduct">

                            <x-eos-save class="w-4 h-4 mr-1" />
                            {{ __($state === 'Create' ? 'Create Product' : 'Update Product') }}
                        </x-button>
                    </div>
                </div>

                <script>
                    // This is the former JavaScript mechanism for handling the custom file upload

                    // function updateFileLabel(input) {
                    //     const placeholderElement = document.getElementById('file-placeholder');
                    //     const selectedElement = document.getElementById('file-selected');
                    //     const fileNameElement = document.getElementById('file-name');

                    //     if (input.files && input.files[0]) {
                    //         const fileName = input.files[0].name;
                    //         fileNameElement.textContent = fileName;
                    //         placeholderElement.classList.add('hidden');
                    //         selectedElement.classList.remove('hidden');
                    //     } else {
                    //         placeholderElement.classList.remove('hidden');
                    //         selectedElement.classList.add('hidden');
                    //     }
                    // }



                    // This is the former JavaScript mechanism for displaying the description's character length

                    // document.addEventListener('DOMContentLoaded', function() {
                    //     const textarea = document.getElementById('description');
                    //     const charCount = document.getElementById('char-count');

                    //     function updateCount() {
                    //         charCount.textContent = textarea.value.length;
                    //     }

                    //     textarea.addEventListener('input', updateCount);
                    //     updateCount();
                    // });
                </script>
            @elseif ($state === 'Delete')
                <div class="flex flex-col flex-1">
                    <div class="mb-2 flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Delete Product</h1>
                    </div>
                    <form class='flex-1 flex flex-col' wire:submit.prevent="deleteProduct">
                        <div class="flex pb-4">
                            <flux:text size='lg'>Are you sure you want to delete <strong>{{ $product->name }}</strong>?</flux:text>
                        </div>
                        <div class="flex items-center justify-end mt-auto pt-4 border-t border-gray-100">
                            <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center hover:cursor-pointer" x-on:click="$flux.modals().close()">
                                {{-- <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" /> --}}
                                Cancel
                            </a>
                            <flux:button class="ml-6" type='submit'
                                        variant='danger'
                                        icon='trash'
                                        wire:loading.attr="disabled"
                                        wire:target="{{ implode(', ', array_keys($validationAttributes)) }}, saveProduct">
                                {{ __('Delete Product') }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

</flux:modal>

