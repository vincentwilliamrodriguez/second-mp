<div>
    <flux:modal.trigger name='cart-flyout'>
        <flux:button square variant='ghost' class="transition-all"
            wire:click.prevent="$dispatchTo('cart-flyout', 'open', {method: 'Show'})"
        >
            <flux:icon.shopping-cart class="!relative !-z-10"></flux:icon>
            @if ($cartCount > 0)
                <div class="!absolute !z-10 bottom-0.5 -right-1 text-xs rounded-md px-1 py-0.6 text-indigo-800 bg-indigo-200 font-bold shadow-sm"> {{ $cartCount }}
            </div>
            @endif
        </flux:button>
    </flux:modal>
</div>

