<div>
    @php
        $usersTableWidths = $this->getUsersTableWidths();
        $usersTableColumns = $this->getUsersTableColumns();
    @endphp

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
</div>
