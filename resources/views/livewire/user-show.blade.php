<div class="bg-white rounded-lg shadow-sm max-w-5xl mx-auto p-6 w-[700px]">
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-grow flex flex-col">
            <div class="mb-6">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="font-bold text-2xl text-gray-800">{{ $user->name }}</h1>
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm">{{ $user->getRoleNames()[0] }}</span>
                </div>

                <div class="space-y-4 mt-4">
                    <div class="flex items-center gap-1">
                        <span class="font-black text-gray-700">Username:</span>
                        <span class="text-blue-600">{{ $user->username }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <span class="font-black text-gray-700">Email:</span>
                        <span class="text-blue-600">{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <span class="font-black text-gray-700">Phone Number:</span>
                        <span class="text-blue-600">{{ $user->number }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-auto pt-4 border-t border-gray-100">
                <button wire:click="backToUsers" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center">
                    <x-eos-arrow-circle-left-o class="h-6 w-6 mr-1 opacity-90" />
                    Back to Users
                </button>
            </div>
        </div>
    </div>
</div>
