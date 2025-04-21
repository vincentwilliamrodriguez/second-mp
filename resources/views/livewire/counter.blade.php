{{-- This is the new counter that uses Livewire and Alpine --}}

<div class="flex items-center justify-center">

    <button type="button"
        class="px-2 py-1 border border-zinc-300 rounded select-none hover:bg-slate-50"
        x-on:click="$wire.count = Math.max($wire.min, $wire.count - 1);
                    if(!$wire.inProductsChild) {
                        $dispatch('counterchanged', {count: $wire.count, cartItem: $wire.cartItem});
                    }"
    >
        -
    </button>

    <div class="px-1 w-9 select-none text-center" x-text="$wire.count"></div>

    <button type="button"
        class="px-2 py-1 border border-zinc-300  rounded select-none hover:bg-slate-50"
        x-on:click="$wire.count = Math.min($wire.max, $wire.count + 1);
                    if(!$wire.inProductsChild) {
                        $dispatch('counterchanged', {count: $wire.count, cartItem: $wire.cartItem});
                    }"
    >
        +
    </button>
</div>
