<div class="w-[1000px] mx-auto">
    <div class="bg-blue-600 flex justify-between items-center">
        <div class="text-white px-6 py-4">
            <h3 class="text-[25px] font-bold">Support Dashboard</h3>
        </div>
        <div class="flex space-x-2 px-6">
            <button wire:click="setTab('list')"
            class="px-4 py-2 font-bold rounded {{ $activeTab === 'list' ? 'bg-blue-300 text-white' : 'bg-gray-200 text-black' }}">
                Active Tickets
            </button>
            <button wire:click="setTab('archived')"
                class="px-4 py-2 font-bold rounded {{ $activeTab === 'archived' ? 'bg-blue-300 text-white' : 'bg-gray-200 text-black' }}">
                Archived Tickets
            </button>

        </div>
    </div>

    <div class="bg-gray-200">
        <div class="px-6 py-4 flex space-x-2">
            <input wire:model="search" type="text" placeholder="Search by Email" class="text-black px-4 py-2 rounded border">
            <button wire:click="searchEmail" class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
        </div>
    </div>

    @if ($activeTab === 'list')
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr class="text-black">
                        <th class="px-4 py-2">Ticket #</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2">Actions</th>
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
                            <td class="px-4 py-2 space-y-2">
                                <button wire:click="deleteTicket({{ $ticket->id }}, 'hide')"
                                    class="text-green-600 font-bold text-sm hover:underline"
                                    onclick="return confirm('Archive this ticket?')">Archive</button>
                                @role('admin')
                                    <button wire:click="deleteTicket({{ $ticket->id }}, 'delete')"
                                        class="text-red-600 font-bold text-sm hover:underline"
                                        onclick="return confirm('Permanently delete this ticket?')">Delete</button>
                                @endrole
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td colspan="6" class="px-6 py-3 text-sm text-gray-700">
                                <strong>Description:</strong>
                                <div class="mt-1 max-h-32 overflow-y-auto whitespace-pre-wrap pr-2">
                                    {{ $ticket->user_description }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No active tickets.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($activeTab === 'archived')
        <div class="bg-white shadow rounded-lg overflow-x-auto" wire:key="archived-tickets">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr class="text-black">
                        <th class="px-4 py-2">Ticket #</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivedTickets as $ticket)
                        <tr class="border-t text-black">
                            <td class="px-4 py-2 text-blue-600">{{ $ticket->ticket_number }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_name }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_email }}</td>
                            <td class="px-4 py-2">{{ $ticket->user_phone }}</td>
                            <td class="px-4 py-2">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2 space-y-2">
                                <button wire:click="restoreTicket({{ $ticket->id }})"
                                    class="text-blue-600 font-bold text-sm hover:underline">Return</button>
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td colspan="6" class="px-6 py-3 text-sm text-gray-700">
                                <strong>Description:</strong>
                                <div class="mt-1 max-h-32 overflow-y-auto whitespace-pre-wrap pr-2">
                                    {{ $ticket->user_description }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No archived tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
