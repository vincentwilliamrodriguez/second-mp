
<div class='flex justify-center'>
    <flux:button variant='danger' icon='x-mark' size='xs'
        wire:click.prevent="$dispatchTo('cart-child', 'open', {method: 'Delete', itemId: '{{ $item['id'] }}' })"
        x-on:click.prevent="$flux.modal('cart-child').show()"
    >
        Remove
    </flux:button>
</div>
