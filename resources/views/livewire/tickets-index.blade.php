<div class="w-[1000px] mx-auto">
    <div class="bg-blue-600 flex justify-between items-center">
        <div class="text-white px-6 py-4">
            <h3 class="text-[25px] font-bold">Support Dashboard</h3>
        </div>
        <div class="flex space-x-2 px-6">
            <button wire:click="setTab('list')" class="px-4 py-2 font-bold rounded {{ $availableTab === 'list' ? 'bg-blue-300 text-white' : 'bg-gray-200 text-black' }}">
                Available Tickets
            </button>
            <button wire:click="setTab('accepted')" class="px-4 py-2 font-bold rounded {{ $availableTab === 'accepted' ? 'bg-blue-300 text-white' : 'bg-gray-200 text-black' }}">
                Accepted Tickets
            </button>
        </div>
    </div>

    <div class="bg-gray-200">
        <div class="px-6 py-4 flex flex-row flex-wrap items-center gap-2">

            <input wire:model="search" type="text" placeholder="Search by Email" class="text-black px-4 py-2 rounded border"/>

            <button wire:click="searchEmail" class="px-4 py-2 text-sm font-sans font-semibold rounded bg-blue-600 text-white border-2 border-blue-600">
                Search
            </button>

           <button wire:click="toggleSort" class="px-4 py-2 text-sm font-sans font-semibold rounded border-2 border-blue-600
                {{ $sortDirection === 'asc'
                    ? 'bg-blue-600 text-white'
                    : 'bg-white text-blue-600' }}">
               {{ $sortDirection === 'asc' ? 'Oldest → Newest' : 'Newest → Oldest' }}
           </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if ($availableTab === 'list')
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text">
                    <tr class="text-black">
                        <th class="px-4 py-2 text-center">Ticket</th>
                        <th class="px-4 py-2 text-center">Name</th>
                        <th class="px-4 py-2 text-cetner">Email</th>
                        <th class="px-4 py-2 text-center">Phone</th>
                        <th class="px-4 py-2 text-center">Created</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="border-t text-black">
                            <td class="px-4 py-2 text-blue-600">{{ $ticket->ticket_number }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_name }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_email }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_phone }}</td>
                            <td class="px-4 py-2">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col gap-2">
                                    <flux:button
                                        class="!h-min !text-green-600 hover:!text-green-900 !px-2 !py-1 !bg-green-100 hover:!bg-green-200 rounded-md text-xs flex gap-1 items-center transition-all"
                                        wire:click.prevent="deleteTicket({{ $ticket->id }}, 'hide')"
                                        x-on:click.prevent="$flux.modal('tickets-child-accept-{{ $ticket->id }}').show()"
                                    >
                                        <x-slot name="icon">
                                            <flux:icon.check class="size-4"></flux:icon.check>
                                        </x-slot>
                                        Accept
                                    </flux:button>

                                    @role('admin')
                                        <flux:button
                                            class="!h-min !text-red-600 hover:!text-red-900 !px-2 !py-1 !bg-red-100 hover:!bg-red-200 rounded-md text-xs flex gap-1 items-center transition-all"
                                            wire:click.prevent="deleteTicket({{ $ticket->id }}, 'delete')"
                                            x-on:click.prevent="$flux.modal('tickets-child-delete-{{ $ticket->id }}').show()"
                                        >
                                            <x-slot name="icon">
                                                <flux:icon.trash class="size-4"></flux:icon.trash>
                                            </x-slot>
                                            Delete
                                        </flux:button>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td colspan="6" class="px-6 py-3 text-sm text-gray-700">
                                <strong>Description:</strong>
                                <div class="mt-1 max-h-32 overflow-y-auto whitespace-pre-wrap pr-2">
                                    {{ $ticket->user_description }}
                                </div>

                                @if($ticket->replies && $ticket->replies->count() > 0)
                                    <div class="mt-3 border-t pt-2">
                                        <strong>Conversation:</strong>
                                        <div class="mt-2 space-y-2">
                                            @foreach($ticket->replies as $reply)
                                                <div class="p-2 rounded {{ $reply->is_from_staff ? 'bg-blue-50 ml-4' : 'bg-gray-50 mr-4' }}">
                                                    <p class="text-xs text-gray-500">
                                                        {{ $reply->is_from_staff ? 'Staff' : 'Customer' }} -
                                                        {{ $reply->created_at->format('M d, Y g:i A') }}
                                                    </p>
                                                    <p class="whitespace-pre-wrap">{{ $reply->message }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($replyingTo === $ticket->id)
                                    <div class="mt-4 p-4 border border-gray-200 rounded bg-gray-50">
                                        <h4 class="font-medium text-gray-700 mb-2">Send Reply</h4>

                                        <div class="mb-3">
                                            <label class="block text-gray-700 mb-1">Message:</label>
                                            <textarea wire:model="replyText" class="w-full p-2 border rounded" rows="4" placeholder="Type your reply here..."></textarea>
                                            @error('replyText') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex space-x-2">
                                            <button wire:click="sendReply" class="bg-green-600 text-white px-4 py-2 rounded">Send Reply</button>
                                            <button wire:click="cancelReply" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No available tickets.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($availableTab === 'accepted')
        <div class="bg-white shadow rounded-lg overflow-x-auto" wire:key="accepted-tickets">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr class="text-black">
                        <th class="px-4 py-2">Ticket</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($acceptedTickets as $ticket)
                        <tr class="border-t text-black">
                            <td class="px-4 py-2 text-blue-600">{{ $ticket->ticket_number }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_name }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_email }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_phone }}</td>
                            <td class="px-4 py-2">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2">
                                <flux:button
                                    class="!h-min !text-blue-600 hover:!text-blue-900 !px-2 !py-1 !bg-blue-100 hover:!bg-blue-200 rounded-md text-xs flex gap-1 items-center transition-all mb-2"
                                    wire:click.prevent="restoreTicket({{ $ticket->id }})"
                                    x-on:click.prevent="$flux.modal('tickets-child-restore-{{ $ticket->id }}').show()"
                                >
                                    <x-slot name="icon">
                                        <flux:icon.arrow-left class="size-4"></flux:icon.arrow-left>
                                    </x-slot>
                                    Return
                                </flux:button>
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td colspan="6" class="px-6 py-3 text-sm text-gray-700">
                                <strong>Description:</strong>
                                <div class="mt-1 max-h-32 overflow-y-auto whitespace-pre-wrap pr-2">
                                    {{ $ticket->user_description }}
                                </div>

                                @if($ticket->replies && $ticket->replies->count() > 0)
                                    <div class="mt-3 border-t pt-2">
                                        <strong>Conversation:</strong>
                                        <div class="mt-2 space-y-2">
                                            @foreach($ticket->replies as $reply)
                                                <div class="p-2 rounded {{ $reply->is_from_staff ? 'bg-blue-50 ml-4' : 'bg-gray-50 mr-4' }}">
                                                    <p class="text-xs text-gray-500">
                                                        {{ $reply->is_from_staff ? 'Staff' : 'Customer' }} -
                                                        {{ $reply->created_at->format('M d, Y g:i A') }}
                                                    </p>
                                                    <p class="whitespace-pre-wrap">{{ $reply->message }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($replyingTo === $ticket->id)
                                    <div class="mt-4 p-4 border border-gray-200 rounded bg-gray-50">
                                        <h4 class="font-medium text-gray-700 mb-2">Send Reply</h4>

                                        <div class="mb-3">
                                            <label class="block text-gray-700 mb-1">Message:</label>
                                            <textarea wire:model="replyText" class="w-full p-2 border rounded" rows="4" placeholder="Type your reply here..."></textarea>
                                            @error('replyText') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex space-x-2">
                                            <button wire:click="sendReply" class="bg-green-600 text-white px-4 py-2 rounded">Send Reply</button>
                                            <button wire:click="cancelReply" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <button wire:click="startReply({{ $ticket->id }})" class="text-blue-600 hover:underline text-sm font-medium">
                                            Reply to this ticket
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No accepted tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
