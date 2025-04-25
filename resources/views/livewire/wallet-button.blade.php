
<flux:dropdown>
    <flux:button icon='wallet' variant='ghost'></flux:button>
    <flux:menu>
        <flux:menu.item disabled class="!opacity-100">
            <div class="flex justify-center font-black text-lg text-zinc-900 w-full">My Wallet</div>
        </flux:menu.item>
        <flux:menu.item disabled class="!opacity-100">
            <div class="flex justify-between font-black text-zinc-600 w-full">
                <span class="text-zinc-700 font-bold">Balance</span>
                <span class="text-indigo-700 font-semibold"
                        x-text="$wire.balance.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' })">
                </span>
            </div>
        </flux:menu.item>
        <flux:menu.separator></flux:menu.separator>
        <flux:menu.item icon:trailing="plus" class="hover:bg-zinc-100"
                        wire:click.stop="addFunds"
        >
            Add Funds
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
