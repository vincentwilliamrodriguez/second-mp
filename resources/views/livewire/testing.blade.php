<div>
    <livewire:counter :max="$product->quantity" wire:model="quantity" />
    <p class="text-2xl" x-text="$wire.quantity"></p>
</div>
