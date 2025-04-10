@props([
    'product',
    'init' => '1',
    'order_id' => null,
])

<div x-data="{ count: {{ $init }}, changed: false }" class="flex flex-col gap-1 items-center">
    <div class="flex items-center">
        <button type="button"
        class="px-2 py-1 border rounded select-none"
        @click="count = count > 1 ? count - 1 : 1; changed = true">-</button>

        <span class="px-4 select-none" x-text="count"></span>

        <button type="button"
            class="px-2 py-1 border rounded select-none"
            @click="count = count < {{ $product->quantity }} ? count + 1 : count; changed = true">+</button>
    </div>

    <form x-show="changed" class="block" action="{{ route('orders.update-quantity', $order_id) }}" method="POST">
        @csrf
        <input type="hidden" name="quantity" :value="count">
        <button type="submit" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Update</button>
    </form>
</div>
