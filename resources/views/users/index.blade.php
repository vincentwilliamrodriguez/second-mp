<x-tab>
    @livewire('user-index')
</x-tab>

{{-- This is the old user index file --}}

{{-- The PHP code below defines the parameters of the admin's users tables

@php
    $usersTableWidths = [
        'Name' => '120px',
        'Username' => '120px',
        'Email' => '120px',
        'Number' => '120px',
    ];

    $usersTableColumns = [
        'Name' => function($user) {
            return "<a class='hover:underline' href='" . route('users.show', $user) . "'>" . $user->name . "</a>";
        },
        'Username' => function($user) {
            return $user->username;
        },
        'Email' => function($user) {
            return $user->email;
        },
        'Number' => function($user) {
            return $user->number;
        },
        'Role' => function($user) {
            $html = "<div class='flex justify-center'>";

            foreach($user->getRoleNames() as $role) {
                $html .= "<span class='px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800'>{$role}</span>";
            }

            $html .= "</div>";

            return $html;
        },
        'Actions' => function($user) {
            return view('components.user-actions', compact('user'))->render();
        }
    ];
@endphp


<x-tab title="All Users">
    <div class="flex flex-col p-8 w-[90vw] max-w-[1200px]">
        @if(session('message'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <div class="flex justify-between items-center mb-4">
            <h2 class="font-black text-3xl">All Users</h2>
            @can('create-users')
                <x-button
                    onclick="window.location.href='{{ route('users.create') }}'" :baseColor="'blue'" :iconSize="'w-6 h-6'">
                    <x-slot name='icon'><x-eos-add-box-o/></x-slot>
                    Create User
                </x-button>
            @endcan
        </div>

        <x-table
            :items="$users"
            :columns="$usersTableColumns"
            :widths="$usersTableWidths"
        />
    </div>
</x-tab> --}}
