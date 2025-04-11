@props([
    'baseColor' => 'gray',
    'iconSize' => 'w-4 h-4'
])

@php
    $classes = 'inline-flex items-center gap-2 px-4 py-2 bg-' . $baseColor . '-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-' . $baseColor . '-700 focus:bg-' . $baseColor . '-700 active:bg-' . $baseColor . '-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150'
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>

    @if (isset($icon))
        <div class="{{$iconSize}} flex justify-center items-center">
            {{ $icon }}
        </div>
    @endif

    {{ $slot }}
</button>
