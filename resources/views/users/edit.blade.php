<x-tab title="Users">
    <div class="w-[70vw] max-w-[700px] p-4">
        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Full Name -->
            <div>
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                         value="{{ old('name', $user->name) }}" required autofocus maxlength="40" />
            </div>

            <!-- Username -->
            <div class="mt-4">
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username"
                         value="{{ old('username', $user->username) }}" required maxlength="40" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                         value="{{ old('email', $user->email) }}" required maxlength="40" />
            </div>

            <!-- Phone Number -->
            <div class="mt-4">
                <x-label for="number" value="{{ __('Phone Number') }}" />
                <x-input id="number" class="block mt-1 w-full" type="tel" name="number"
                         value="{{ old('number', $user->number) }}" required maxlength="40" />
            </div>

            <!-- Password (optional: leave blank to keep current) -->
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password (Leave blank to keep current)') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" maxlength="40" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                         name="password_confirmation" maxlength="40" />
            </div>

            <!-- Role Dropdown -->
            <div class="mt-4">
                <x-label for="role" value="{{ __('Role') }}" />
                <x-dropdown-wrapper
                    :defaultText="'customer'"
                    :options="$roles"
                    setValue="{{ old('role', $user->getRoleNames()->first()) }}"
                    name="role"
                    align="left"
                    width="60"
                ></x-dropdown-wrapper>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none
                          focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('users.index') }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Update') }}
                </x-button>
            </div>
        </form>
    </div>
</x-tab>
