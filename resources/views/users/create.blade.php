<x-tab title="Users">

    <div class="w-[70vw] max-w-[700px] p-4">
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name') }}" required autofocus maxlength="40" />
            </div>

            <div class="mt-4">
                <x-label for="username" value="{{ __('Username') }}" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ old('username') }}" required autofocus maxlength="40" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') }}" required autofocus maxlength="40" />
            </div>

            <div class="mt-4">
                <x-label for="number" value="{{ __('Phone Number') }}" />
                <x-input id="number" class="block mt-1 w-full" type="tel" name="number" required autofocus maxlength="40" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus maxlength="40" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autofocus maxlength="40" />
            </div>


            <div class="mt-4">
                <x-label for="role" value="{{ __('Role') }}" />
                <x-dropdown-wrapper
                    :defaultText="'customer'"
                    :options="$roles"
                    name="role"
                    align="left"
                    width="60"
                ></x-dropdown-wrapper>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('users.index') }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Create') }}
                </x-button>
            </div>
        </form>
    </div>
</x-tab>
