<div class="w-[70vw] max-w-[700px] p-6 bg-white rounded-lg shadow-sm mx-auto">
    <div class="mb-6 flex items-center">
        <h1 class="text-2xl font-bold text-gray-800">Create New User</h1>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="text-red-800">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form wire:submit="store">
        <div class="mb-5">
            <label for="name" class="flex items-center gap-1 mb-1">
                <span>Full Name</span>
            </label>
            <input id="name" class="block w-full rounded-md border-gray-300 shadow-sm" type="text" wire:model="name" required autofocus maxlength="40" placeholder="Enter full name" />
        </div>

        <div class="mb-5">
            <label for="username" class="flex items-center gap-1 mb-1">
                <span>Username</span>
            </label>
            <input id="username" class="block w-full rounded-md border-gray-300 shadow-sm" type="text" wire:model="username" required maxlength="40" placeholder="Enter username" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label for="email" class="flex items-center gap-1 mb-1">
                    <span>Email</span>
                </label>
                <input id="email" class="block w-full rounded-md border-gray-300 shadow-sm" type="email" wire:model="email" required maxlength="40" placeholder="user@example.com" />
            </div>

            <div>
                <label for="number" class="flex items-center gap-1 mb-1">
                    <span>Phone Number</span>
                </label>
                <input id="number" class="block w-full rounded-md border-gray-300 shadow-sm" type="tel" wire:model="number" required maxlength="40" placeholder="Enter phone number" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            <div>
                <label for="password" class="flex items-center gap-1 mb-1">
                    <span>Password</span>
                </label>
                <input id="password" class="block w-full rounded-md border-gray-300 shadow-sm" type="password" wire:model="password" required maxlength="40" placeholder="Enter password" />
            </div>

            <div>
                <label for="password_confirmation" class="flex items-center gap-1 mb-1">
                    <span>Confirm Password</span>
                </label>
                <input id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm" type="password" wire:model="password_confirmation" required maxlength="40" placeholder="Confirm password" />
            </div>
        </div>

        <div class="mb-5">
            <label for="role" class="flex items-center gap-1 mb-1">
                <span>Role</span>
            </label>
            <select id="role" class="block w-full rounded-md border-gray-300 shadow-sm" wire:model="role">
                @foreach($roles as $availableRole)
                    <option value="{{ $availableRole }}">{{ ucfirst($availableRole) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-100">
            <a class="text-gray-600 hover:text-blue-600 transition-colors flex items-center" href="{{ route('users.index') }}">
                <svg class="h-5 w-5 mr-1 opacity-90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Users
            </a>

            <button type="submit" class="ml-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Create User
            </button>
        </div>
    </form>
</div>
