@props(['user'])

<div class="flex flex-col gap-2 justify-center items-center">
    @can('read-users')
        <form action="{{ route('users.show', $user) }}" method='GET' class='inline z-20 relative pointer-events-auto'>
            <button class='text-blue-600 hover:text-blue-900 hover:cursor-pointer'>
                <span class='px-2 py-1 bg-blue-100 rounded-md text-xs flex gap-1 items-center'><x-eos-remove-red-eye class="h-4 w-4 opacity-90"/> View</span>
            </button>
        </form>
    @endcan

    @can('update-users')
        <form action="{{ route('users.edit', $user) }}" method='GET' class='inline z-20 relative pointer-events-auto'>
            <button class='text-yellow-600 hover:text-yellow-900 hover:cursor-pointer'>
                <span class='px-2 py-1 bg-yellow-100 rounded-md text-xs flex gap-1 items-center'><x-eos-edit class="h-4 w-4 opacity-90"/> Edit</span>
            </button>
        </form>
    @endcan

    @can('delete-users')
        <form action={{route('users.destroy', $user)}} method='POST' class='inline z-20 relative pointer-events-auto'>
            @csrf
            @method('DELETE')

            <button type='submit'
                    class='text-red-600 hover:text-red-900 hover:cursor-pointer'
                    onclick="return confirm('Are you sure you want to remove this user?')">
                <span class='px-2 py-1 bg-red-100 rounded-md text-xs flex gap-1 items-center'><x-eos-delete class="h-4 w-4 opacity-90"/> Remove</span>
            </button>
        </form>
    @endcan
</div>
