<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-col p-8">
                    @foreach ($products as $product)
                        <div class="mb-4 flex flex-col">
                            @foreach ($product->getAttributes() as $key => $value)
                                <div class="flex gap-1">
                                    <strong>{{ $key }}: </strong> {{ $value }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
