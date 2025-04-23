<flux:modal name='orders-child-confirm' class="fixed !max-w-none" x-on:open="$wire.state = null; method=$event.detail.method" wire:close.prevent="$wire.close()" x-data="{method: '', defaultSizes: {
    'Accept All': '!min-w-[400px] !min-h-[150px]',
    'Cancel All': '!min-w-[400px] !min-h-[150px]',
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
            <div class="flex flex-col flex-1">
                <div class="mb-2 flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ match ($state) {
                            'Accept All' => 'Accept All Pending Items',
                            'Cancel All' => 'Cancel All Pending Items',
                            default => ''
                        } }}
                    </h1>
                </div>
                <form class='flex-1 flex flex-col' wire:submit.prevent="updateAll">
                    <div class="flex pb-4 max-w-[300px]">
                        <flux:text size='lg'>Are you sure you want to {{ Str::lower($state) }} pending items in this order?</flux:text>
                    </div>
                    <div class="flex items-center justify-end mt-auto pt-4 border-t border-gray-100">
                        <a class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center hover:cursor-pointer" x-on:click="$flux.modal('orders-child-confirm').close()">
                            Cancel
                        </a>
                        <flux:button class="ml-6" type='submit'
                                    :variant="($state === 'Accept All') ? 'primary' : 'danger'"
                                    :icon="($state === 'Accept All') ? 'check' : 'x-mark'">
                            {{ __($state) }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</flux:modal>
