<div wire:click.prevent="$dispatchTo('{{$name}}', '{{$method}}')"
x-on:click.prevent="$flux.modal('$wire.name').show()">

    {!! $buttonHtml !!}


    @php
        $props = ['component' => $name, 'key' => $name] + $data;
    @endphp

    {{-- <livewire:dynamic-component :component="'products-child'" :$name wire:key="'products-child'" wire:id="'awaw'"/> --}}


</div>
