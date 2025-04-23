@props(['order'])

{{-- This is the new code for managing orders --}}
<div class='flex flex-col justify-center items-center gap-2'>
    <flux:button variant='primary' icon='eye' size='xs' class="!h-min !text-blue-600 hover:!text-blue-900 !px-2 !py-1 !bg-blue-100 !hover:bg-blue-200 rounded-md text-xs flex gap-1 items-center transition-all"
        wire:click.prevent="$dispatchTo('orders-child', 'open', {method: 'Show', orderId: '{{ $order->id }}' })"
        x-on:click.prevent="$flux.modal('orders-child').show()"
    >
        View
    </flux:button>

    <flux:button variant='primary' icon='pencil-square' size='xs' class="!h-min !text-amber-600 hover:!text-amber-900 !px-2 !py-1 !bg-amber-100 !hover:bg-amber-200 rounded-md text-xs flex gap-1 items-center transition-all"
        wire:click.prevent="$dispatchTo('orders-child', 'open', {method: 'Edit', orderId: '{{ $order->id }}' })"
        x-on:click.prevent="$flux.modal('orders-child').show()"
    >
        Manage
    </flux:button>


    @role('admin')
        <flux:button variant='primary' icon='trash' size='xs' class="!h-min !text-red-600 hover:!text-red-900 !px-2 !py-1 !bg-red-100 !hover:bg-red-200 rounded-md text-xs flex gap-1 items-center transition-all"
            wire:click.prevent="$dispatchTo('orders-child', 'open', {method: 'Delete', orderId: '{{ $order->id }}' })"
            x-on:click.prevent="$flux.modal('orders-child').show()"
        >
            Delete
        </flux:button>
    @endrole
</div>



{{-- This is the old code for managing orders --}}

{{-- <div class="flex flex-col gap-2 justify-center items-center">
    @can('update-orders') @unlessrole('customer')
        @if ($order->is_placed && $order->status === 'pending')
            <form action="{{ route('orders.update', $order) }}" method='POST' class='inline z-20 relative pointer-events-auto'>
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">

                <button class='text-green-600 hover:text-green-900 hover:cursor-pointer'>
                    <span class='px-2 py-1 bg-green-100 rounded-md text-xs flex gap-1 items-center'><x-eos-check class="h-4 w-4 opacity-90"/> Accept</span>
                </button>
            </form>

            <form action="{{ route('orders.update', $order) }}" method='POST' class='inline z-20 relative pointer-events-auto @role('admin') mb-2 @endrole'>
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">

                <button class='text-red-600 hover:text-red-900 hover:cursor-pointer'
                    onclick="return confirm('Are you sure you want to cancel this order?')">

                    <span class='px-2 py-1 bg-red-100 rounded-md text-xs flex gap-1 items-center'><x-eos-close class="h-4 w-4 opacity-90"/> Cancel</span>
                </button>
            </form>


        @elseif (!$order->is_placed &&  auth()->user()->hasRole('admin'))
            <form action="{{ route('orders.update', $order) }}" method='POST' class='inline z-20 relative pointer-events-auto'>
                @csrf
                @method('PUT')
                <input type="hidden" name="is_placed" value="1">

                <button class='text-blue-600 hover:text-blue-900 hover:cursor-pointer'>
                    <span class='px-2 py-1 bg-blue-100 rounded-md text-xs flex gap-1 items-center'><x-eos-check class="h-4 w-4 opacity-90"/> Place</span>
                </button>
            </form>
        @endif

    @endunlessrole @endcan


    @can('delete-orders')
        <form action={{route('orders.destroy', $order)}} method='POST' class='inline z-20 relative pointer-events-auto'>
            @csrf
            @method('DELETE')

            <button type='submit'
                    class='text-red-600 hover:text-red-900 hover:cursor-pointer'
                    onclick="return confirm('Are you sure you want to remove this order?')">

                <span class='px-2 py-1 bg-red-100 rounded-md text-xs flex gap-1 items-center'> <x-eos-delete class="h-4 w-4"/> Remove</span>
            </button>
        </form>
    @endcan
</div> --}}
