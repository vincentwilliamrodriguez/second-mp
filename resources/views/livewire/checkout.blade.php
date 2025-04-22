<div class="flex flex-col md:flex-row gap-8 p-8 w-[90vw] max-w-[1200px]">

    <!-- Checkout Steps Column -->
    <div class="flex-1">
        <h2 class="font-black text-3xl text-gray-800 mb-6">Checkout</h2>

        @if(session('message'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-red-100 rounded-md w-full mb-4">
            <x-validation-errors class=" p-4"></x-validation-errors>
        </div>

        <div class="flex flex-col gap-4" x-data="{
            activeSection: 'shipping',
            isCompleted: {
                shipping: false,
                delivery: false,
                payment: false
            },
            toggleSection(section) {
                this.activeSection = this.activeSection === section ? this.activeSection : section;
            },
            completeSection(section, nextSection) {
                this.isCompleted[section] = true;
                this.activeSection = nextSection;
            }
        }">
            <!-- Shipping Address -->
            <div class="border border-indigo-200 rounded-lg overflow-hidden">
                <div class="bg-indigo-50 p-4 flex justify-between items-center cursor-pointer"
                     x-on:click="toggleSection('shipping')"
                     x-bind:class="(activeSection === 'shipping' || isCompleted.shipping) ? 'bg-indigo-100' : ''">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                             x-bind:class="isCompleted.shipping ? 'bg-indigo-600 text-white' : 'bg-indigo-200 text-indigo-700'">
                            <template x-if="isCompleted.shipping">
                                <flux:icon.check class="size-5" />
                            </template>
                            <template x-if="!isCompleted.shipping">
                                <span>1</span>
                            </template>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800">Shipping Address</h3>
                    </div>
                    <flux:icon.chevron-down class="size-5 text-indigo-600 transition-transform"
                                            x-bind:class="(activeSection === 'shipping') ? 'rotate-180' : ''" />
                </div>

                <div class="p-6" x-show="activeSection === 'shipping'" x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <flux:input
                            wire:model="fullName"
                            :label="__('Full Name')"
                            type="text"
                            required
                            autocomplete="name"
                            :placeholder="__('Juan Dela Cruz')"
                        />

                        <!-- Phone Number -->
                        <flux:input
                            wire:model="phoneNumber"
                            :label="__('Phone Number')"
                            type="tel"
                            required
                            autocomplete="tel"
                            placeholder="09123456789"
                        />

                        <!-- Complete Address -->
                        <div class="md:col-span-2">
                            <flux:input
                                wire:model="address"
                                :label="__('Complete Address')"
                                type="text"
                                required
                                :placeholder="__('House/Unit No., Building, Street')"
                            />
                        </div>

                        <!-- Barangay -->
                        <flux:input
                            wire:model="barangay"
                            :label="__('Barangay')"
                            type="text"
                            required
                            :placeholder="__('Barangay Name')"
                        />

                        <!-- City/Municipality -->
                        <flux:input
                            wire:model="city"
                            :label="__('City/Municipality')"
                            type="text"
                            required
                            :placeholder="__('City Name')"
                        />

                        <!-- Province -->
                        <flux:input
                            wire:model="province"
                            :label="__('Province')"
                            type="text"
                            required
                            :placeholder="__('Province Name')"
                        />

                        <!-- Postal Code -->
                        <flux:input
                            wire:model="postalCode"
                            :label="__('Postal Code')"
                            type="text"
                            required
                            :placeholder="__('e.g. 1200')"
                        />

                        <div class="md:col-span-2 flex justify-end">
                            <flux:button
                                wire:click="validateShippingAddress"
                                x-on:click="$wire.validateShippingAddress().then(isValid => {
                                    if(isValid) completeSection('shipping', 'delivery')
                                })"
                                variant="primary"
                                class="w-full md:w-auto">
                                Continue to Delivery Method
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Method -->
            <div class="border border-indigo-200 rounded-lg overflow-hidden">
                <div class="bg-indigo-50 p-4 flex justify-between items-center cursor-pointer"
                     x-on:click="toggleSection('delivery')"
                     x-bind:class="(activeSection === 'delivery' || isCompleted.delivery) ? 'bg-indigo-100' : ''" x-bind:class="(!isCompleted.shipping) ? 'opacity-75' : ''">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                            x-bind:class="isCompleted.delivery ? 'bg-indigo-600 text-white' : 'bg-indigo-200 text-indigo-700'"
                        >
                            <template x-if="isCompleted.delivery">
                                <flux:icon.check class="size-5" />
                            </template>
                            <template x-if="!isCompleted.delivery">
                                <span>2</span>
                            </template>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800">Delivery Method</h3>
                    </div>
                    <flux:icon.chevron-down class="size-5 text-indigo-600 transition-transform"
                                            x-bind:class="(activeSection === 'delivery') ? 'rotate-180' : ''" />
                </div>

                <div class="p-6" x-show="activeSection === 'delivery'" x-transition>
                    <div class="flex flex-col gap-4">
                        <!-- Standard Delivery -->
                        <div class="border rounded-lg p-4 cursor-pointer"
                             x-bind:class="($wire.deliveryMethod === 'standard') ? 'border-indigo-500 bg-indigo-50' : ''"
                             wire:click="$set('deliveryMethod', 'standard')">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">Standard Delivery</h4>
                                    <p class="text-sm text-gray-600">2-3 business days</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-indigo-700">₱20</span>
                                    <div class="w-5 h-5 rounded-full border-2"
                                         x-bind:class="($wire.deliveryMethod === 'standard') ? 'border-indigo-600' : ''">
                                        <div class="w-3 h-3 bg-indigo-600 rounded-full m-0.5"
                                             x-show="$wire.deliveryMethod === 'standard'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Express Delivery -->
                        <div class="border rounded-lg p-4 cursor-pointer"
                             x-bind:class="($wire.deliveryMethod === 'express' ) ? 'border-indigo-500 bg-indigo-50' : ''"
                             wire:click="$set('deliveryMethod', 'express')">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">Express Delivery</h4>
                                    <p class="text-sm text-gray-600">1 business day</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-indigo-700">₱30</span>
                                    <div class="w-5 h-5 rounded-full border-2"
                                         x-bind:class="($wire.deliveryMethod === 'express' ) ? 'border-indigo-600' : ''">
                                        <div class="w-3 h-3 bg-indigo-600 rounded-full m-0.5"
                                             x-show="$wire.deliveryMethod === 'express'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Same-day Delivery -->
                        <div class="border rounded-lg p-4 cursor-pointer"
                             x-bind:class="($wire.deliveryMethod === 'same_day' ) ? 'border-indigo-500 bg-indigo-50' : ''"
                             wire:click="$set('deliveryMethod', 'same_day')">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">Same-day Delivery</h4>
                                    <p class="text-sm text-gray-600">Metro Manila only</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-indigo-700">₱50</span>
                                    <div class="w-5 h-5 rounded-full border-2"
                                         x-bind:class="($wire.deliveryMethod === 'same_day' ) ? 'border-indigo-600' : ''">
                                        <div class="w-3 h-3 bg-indigo-600 rounded-full m-0.5"
                                             x-show="$wire.deliveryMethod === 'same_day'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <flux:button
                                wire:click="validateDeliveryMethod"
                                x-on:click="$wire.validateDeliveryMethod().then(isValid => {
                                    if(isValid) completeSection('delivery', 'payment')
                                })"
                                variant="primary"
                                class="w-full md:w-auto">
                                Continue to Payment Method
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="border border-indigo-200 rounded-lg overflow-hidden">
                <!-- Section Header -->
                <div class="bg-indigo-50 p-4 flex justify-between items-center cursor-pointer"
                     x-on:click="toggleSection('payment')"
                     x-bind:class="(activeSection === 'payment' || isCompleted.payment) ? 'bg-indigo-100' : ''" x-bind:class="(!isCompleted.delivery) ? 'opacity-75' : ''">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                             :class="isCompleted.payment ? 'bg-indigo-600 text-white' : 'bg-indigo-200 text-indigo-700'">
                            <template x-if="isCompleted.payment">
                                <flux:icon.check class="size-5" />
                            </template>
                            <template x-if="!isCompleted.payment">
                                <span>3</span>
                            </template>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-800">Payment Method</h3>
                    </div>
                    <flux:icon.chevron-down class="size-5 text-indigo-600 transition-transform"
                                          x-bind:class="(activeSection === 'payment') ? 'rotate-180' : ''" />
                </div>

                <!-- Section Content -->
                <div class="p-6" x-show="activeSection === 'payment'" x-transition>
                    <div class="flex flex-col gap-4">
                        <!-- Cash on Delivery -->
                        <div class="border rounded-lg p-4 cursor-pointer"
                             x-bind:class="($wire.paymentMethod === 'cod' ) ? 'border-indigo-500 bg-indigo-50' : ''"
                             wire:click="$set('paymentMethod', 'cod')">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">Cash on Delivery</h4>
                                    <p class="text-sm text-gray-600">Pay when you receive your order</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2"
                                     x-bind:class="($wire.paymentMethod === 'cod' ) ? 'border-indigo-600' : ''">
                                    <div class="w-3 h-3 bg-indigo-600 rounded-full m-0.5"
                                         x-show="$wire.paymentMethod === 'cod'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- ShopStream E-Wallet -->
                        <div class="border rounded-lg p-4 cursor-pointer"
                             x-bind:class="($wire.paymentMethod === 'e_wallet' ) ? 'border-indigo-500 bg-indigo-50' : ''"
                             wire:click="$set('paymentMethod', 'e_wallet')">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">ShopStream E-Wallet</h4>
                                    <p class="text-sm text-gray-600">Pay using your ShopStream wallet balance</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2"
                                     x-bind:class="($wire.paymentMethod === 'e_wallet' ) ? 'border-indigo-600' : ''">
                                    <div class="w-3 h-3 bg-indigo-600 rounded-full m-0.5"
                                         x-show="$wire.paymentMethod === 'e_wallet'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-8">
            <a href="{{ route('cart') }}">
                <flux:button variant="subtle" icon="arrow-left">
                    Back to Cart
                </flux:button>
            </a>
            <flux:button
                wire:click="placeOrder"
                variant="primary"
                icon="shopping-bag"
                class="bg-indigo-600 hover:bg-indigo-700">
                Place Order
            </flux:button>
        </div>
    </div>

    <!-- Order Summary Column -->
    <div class="md:w-1/3 lg:w-1/4">
        <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-100">
            <h3 class="font-bold text-xl text-gray-800 mb-4">Order Summary</h3>

            <!-- Cart Items -->
            <div class="flex flex-col divide-y divide-indigo-200 max-h-[50vh] overflow-y-auto mb-6">
                @forelse ($cartItems as $cartItem)
                    <div class="py-3 flex gap-3">
                        <x-product-image :product="$this->retrieveProduct($cartItem)" classes="size-16 border-indigo-100 flex-shrink-0" placeholderSize="size-8"></x-product-image>

                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 text-sm line-clamp-2">{{ $cartItem['product_name'] }}</h4>
                            <p class="text-xs text-gray-600">Qty: {{ $cartItem['order_quantity'] }}</p>
                            <p class="text-sm font-semibold text-indigo-700">{{ Number::currency($cartItem['product_price'] * $cartItem['order_quantity'], 'PHP') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-gray-500">
                        Your cart is empty.
                    </div>
                @endforelse
            </div>

            <!-- Cost Breakdown -->
            <div class="border-t border-indigo-200 pt-4">
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium" wire:text="subtotalFormatted"></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Shipping Fee</span>
                    <span class="font-medium" wire:text="shippingFeeFormatted"></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Tax (12% VAT)</span>
                    <span class="font-medium" wire:text="taxAmountFormatted"></span>
                </div>
                <div class="flex justify-between py-3 border-t border-indigo-300 mt-2">
                    <span class="font-bold text-gray-800">Total</span>
                    <span class="font-bold text-indigo-700 text-xl" wire:text="totalAmountFormatted"></span>
                </div>
            </div>
        </div>
    </div>
</div>
