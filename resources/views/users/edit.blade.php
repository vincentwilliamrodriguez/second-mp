<x-tab title="Edit User">
    @livewire('user-edit', ['user' => $user])
</x-tab>

{{-- This is the old user edit file --}}

{{-- <x-tab title="Edit User">
    <div class="w-[70vw] max-w-[700px] p-6 bg-white rounded-lg shadow-sm mx-auto">
        <div class="mb-6 flex items-center">
            <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
        </div>

        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <x-label for="name" value="{{ __('Full Name') }}" class="flex items-center gap-1 mb-1">
                    <x-eos-person class="w-4 h-4 text-blue-600" />
                    <span>Full Name</span>
                </x-label>
                <x-input id="name" class="block w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus maxlength="40" placeholder="Enter full name" />
            </div>

            <div class="mb-5">
                <x-label for="username" value="{{ __('Username') }}" class="flex items-center gap-1 mb-1">
                </x-label>
                <x-input id="username" class="block w-full" type="text" name="username" :value="old('username', $user->username)" required maxlength="40" placeholder="Enter username" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="flex items-center gap-1 mb-1"></x-label>
                    <x-input id="email" class="block w-full" type="email" name="email" :value="old('email', $user->email)" required maxlength="40" placeholder="user@example.com" />
                </div>

                <div>
                    <x-label for="number" value="{{ __('Phone Number') }}" class="flex items-center gap-1 mb-1"></x-label>
                    <x-input id="number" class="block w-full" type="tel" name="number" :value="old('number', $user->number)" required maxlength="40" placeholder="Enter phone number" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <x-label for="password" value="{{ __('Password (Leave blank to keep current)') }}" class="flex items-center gap-1 mb-1"></x-label>
                    <x-input id="password" class="block w-full" type="password" name="password" maxlength="40" placeholder="Enter new password" />
                </div>

                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="flex items-center gap-1 mb-1"></x-label>
                    <x-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" maxlength="40" placeholder="Confirm new password" />
                </div>
            </div>

            <div class="mb-5">
                <x-label for="role" value="{{ __('Role') }}" class="flex items-center gap-1 mb-1"></x-label>
                <x-dropdown-wrapper
                    :defaultText="'customer'"
                    :options="$roles"
                    setValue="{{ old('role', $user->getRoleNames()->first()) }}"
                    name="role"
                    align="left"
                    width="60"
                ></x-dropdown-wrapper>
            </div>

            <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-100">
                <a class="text-gray-600 hover:text-blue-600 transition-colors flex items-center" href="{{ route('users.index') }}">
                    <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" />
                    Back to Users
                </a>

                <x-button class="ml-6 bg-blue-600 hover:bg-blue-700">
                    <x-eos-save class="w-4 h-4 mr-1" />
                    {{ __('Update User') }}
                </x-button>
            </div>
        </form>
    </div>
</x-tab> --}}
