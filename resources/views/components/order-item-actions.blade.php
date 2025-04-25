@props(['item'])

<div class='flex flex-col justify-center items-center gap-2'>
    <flux:button variant='danger' icon='x-mark' size='xs' class="!h-min !text-red-100 hover:!text-red-200 !px-2 !py-1 !bg-red-600 !hover:bg-red-900 rounded-md text-xs flex gap-1 items-center transition-all opacity-80"
        wire:click.prevent="$dispatchTo('orders-child', 'cancelItem', {itemId: '{{ $item->id }}' })"
    >
        Cancel
    </flux:button>

    @hasanyrole(['seller', 'admin'])
        <flux:button variant='primary' icon='check' size='xs' class="!h-min !text-indigo-100 hover:!text-indigo-200 !px-2 !py-1 !bg-indigo-600 !hover:bg-indigo-900 rounded-md text-xs flex gap-1 items-center transition-all opacity-80"
            wire:click.prevent="$dispatchTo('orders-child', 'acceptItem', {itemId: '{{ $item->id }}' })"
        >
            Accept
        </flux:button>
    @endhasanyrole


</div>
