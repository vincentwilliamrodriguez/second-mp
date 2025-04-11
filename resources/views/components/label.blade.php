@props([
    'value',
    'iconSize' => 'w-4 h-4'
])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    @if (isset($icon))
        <div class="{{$iconSize}} flex justify-center items-center">
            {{ $icon }}
        </div>
    @endif

    {{ $value ?? $slot }}
</label>
