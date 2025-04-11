<x-tab title="Users">
    <div class="flex flex-col items-start w-[70vw] max-w-[600px] p-4">
        <h1 class='font-black mb-1 text-2xl'>{{ $user->name }}</h1>
        <div class="bg-slate-500 rounded-full px-2 py-1 text-white text-sm">{{ $user->getRoleNames()[0] }}</div>
        <p class='font-black mb-1 text-lg text-blue-500'>Username: {{ $user->username }}</p>
        <p class='font-black mb-1 text-lg text-blue-500'>E-mail: {{ $user->email }}</p>
        <p class='font-black mb-1 text-lg text-blue-500'>Phone Number: {{ $user->number }}</p>

        <div class="self-end flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('users.index') }}">
                {{ __('Go Back') }}
            </a>
        </div>
    </div>
</x-tab>
