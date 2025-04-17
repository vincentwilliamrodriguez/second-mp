{{-- This is the actions part of the Products table, upgraded using Livewire and flux --}}
{{-- TODO: redirect to products-child --}}

<div class="flex gap-2 justify-center items-center">
    @can('read-products')
        <flux:button class="!h-min !text-blue-600 hover:!text-blue-900 !px-2 !py-1 !bg-blue-100 !hover:bg-blue-200 rounded-md text-xs flex gap-1 items-center transition-all">
            <flux:icon.eye class="size-4"></flux:icon.eye>
            View
        </flux:button>
    @endcan

    @can('update-products')
        <flux:button class="!h-min !text-yellow-600 hover:!text-yellow-900 !px-2 !py-1 !bg-yellow-100 !hover:bg-yellow-200 rounded-md text-xs flex gap-1 items-center transition-all">
            <flux:icon.pencil class="size-4"></flux:icon.pencil>
            Edit
        </flux:button>
    @endcan

    @can('delete-products')
        <flux:button class="!h-min !text-red-600 hover:!text-red-900 !px-2 !py-1 !bg-red-100 !hover:bg-red-200 rounded-md text-xs flex gap-1 items-center transition-all">
            <flux:icon.trash class="size-4"></flux:icon.trash>
            Delete
        </flux:button>
    @endcan
</div>
