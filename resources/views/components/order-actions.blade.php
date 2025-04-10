@props(['order'])

<div class="flex flex-col gap-2 justify-center items-center">
    @can('update-orders') @unlessrole('customer') @if ($order->is_placed && $order->status === 'pending')

        <form action="{{ route('orders.update', $order) }}" method='POST' class='inline z-20 relative pointer-events-auto'>
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="completed">

            <button class='text-green-600 hover:text-green-900 hover:cursor-pointer'>
                <span class='px-2 py-1 bg-green-100 rounded-md text-xs'>Accept</span>
            </button>
        </form>

        <form action="{{ route('orders.update', $order) }}" method='POST' class='inline z-20 relative pointer-events-auto'>
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="cancelled">

            <button class='text-red-600 hover:text-red-900 hover:cursor-pointer'
                onclick="return confirm('Are you sure you want to cancel this order?')">

                <span class='px-2 py-1 bg-red-100 rounded-md text-xs'>Cancel</span>
            </button>
        </form>

    @endif @endunlessrole @endcan


    @can('delete-orders')
        <form action={{route('orders.destroy', $order)}} method='POST' class='inline z-20 relative pointer-events-auto'>
            @csrf
            @method('DELETE')

            <button type='submit'
                    class='text-red-600 hover:text-red-900 hover:cursor-pointer'
                    onclick="return confirm('Are you sure you want to remove this order?')">
                <span class='px-2 py-1 bg-red-100 rounded-md text-xs'>Remove</span>
            </button>
        </form>
    @endcan
</div>
