@props(['order'])

<div class='flex flex-col justify-center items-start hover:cursor-pointer'
        wire:click.prevent="$dispatchTo('orders-child', 'open', {method: 'Show', orderId: '{{ $order->id }}' })"
        x-on:click.prevent="$flux.modal('orders-child').show()">
    <h3 class='font-black text-lg hover:underline hover:cursor-pointer'>{{$order->display_name}}</h3>
    <ul class='pl-2'>
        @foreach ($order->orderItemsWrapper() as $orderItem)
            @php
                $statusColor = match ($orderItem->status) {
                    'pending' => 'bg-yellow-400',
                    'accepted' => 'bg-green-300',
                    'shipped' => 'bg-blue-400',
                    'delivered' => 'bg-green-700',
                    'cancelled' => 'bg-red-500',
                }
            @endphp

            <li class='text-zinc-600 flex items-center gap-1'>
                <flux:tooltip :content='Str::title($orderItem->status)'>
                    <div class='{{ $statusColor }} border border-black size-3 rounded-full'></div>
                </flux:tooltip>
                <span class='pt-[3px]'>{{$orderItem->product->name}}</span>
            </li>
        @endforeach
    </ul>
</div>
