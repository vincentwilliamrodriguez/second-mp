@props(['title' => ''])

<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot> --}}

    <div class="flex-1 bg-blue-200 flex flex-col items-center justify-start pt-12">
        <div class="max-w-7xl flex flex-col items-center mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-min shadow-xl sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
