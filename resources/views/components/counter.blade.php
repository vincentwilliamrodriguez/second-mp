@props(['product'])

<div x-data="{ count: 1 }" class="flex items-center">
    <button type="button"
        class="px-2 py-1 border rounded select-none"
        @click="count = count > 1 ? count - 1 : 1">-</button>

    <span class="px-4 select-none" x-text="count"></span>

    <button type="button"
        class="px-2 py-1 border rounded select-none"
        @click="count = count < {{ $product->quantity }} ? count + 1 : count">+</button>

    <input type="hidden" name="quantity" :value="count">
</div>
