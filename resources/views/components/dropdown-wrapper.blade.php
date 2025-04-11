@props([
    'defaultText' => 'Select an option',
    'options' => [],
    'name' => 'option',
    'align' => 'left',
    'width' => '48',
    'setValue' => null,
    'disableHiddenInput' => false
])

<div x-data="{ selected: '{{ $setValue ?? (old($name) ?? $defaultText) }}' }">
    <x-dropdown :align="$align" :width="$width">
        <x-slot name="trigger">
            <button type="button" class="inline-flex w-full justify-between items-center px-4 py-2 bg-transparent border-gray-300 border rounded">
                <span x-text="selected" class="capitalize text-sm"></span>
                <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            @foreach ($options as $option)
                <button
                    type="button"
                    x-on:click="
                        selected = '{{ $option }}';
                        document.getElementById('hiddenRole').value = '{{ $option }}';
                        document.getElementById('hiddenRole').dispatchEvent(new Event('input'));
                    "
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 capitalize"
                >
                    {{ $option }}
                </button>
            @endforeach
        </x-slot>
    </x-dropdown>

    @if (!$disableHiddenInput)
        <input
            type="hidden"
            name="{{ $name }}"
            x-model="selected"
            wire:model.lazy="{{ $name }}"
            data-flux-control
            data-flux-group-target
        >
    @endif
</div>
