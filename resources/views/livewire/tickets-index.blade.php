<div class="w-[1000px] mx-auto my-8">
    <div class="flex space-x-4 mb-6">
        <button wire:click="setTab('list')" class="px-4 py-2 font-bold rounded {{ $activeTab === 'list' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-black' }}">
            Active Tickets
        </button>
        <button wire:click="setTab('archived')" class="px-4 py-2 font-bold rounded {{ $activeTab === 'archived' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-black' }}">
            My Archived Tickets
        </button>
    </div>

    @if ($activeTab === 'list')
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-lg font-bold">Support Tickets</h3>
                <h4 class="text-sm">Publicly visible to all admins/support staff</h4>
            </div>
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
                                <button wire:click="deleteTicket({{ $ticket->id }}, 'hide')" class="text-green-600 font-bold text-sm hover:underline"
                                    onclick="return confirm('Archive this ticket?')">Archive</button>
                                @role('admin')
                                    <button wire:click="deleteTicket({{ $ticket->id }}, 'delete')" class="text-red-600 font-bold text-sm hover:underline"
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
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No active tickets.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if ($this->activeTab === 'archived')
        <div class="bg-white shadow rounded-lg overflow-x-auto" wire:key="archived-tickets">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-lg font-bold">Archived Tickets</h3>
            </div>
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
                                <button wire:click="restoreTicket({{ $ticket->id }})" class="text-blue-600 font-bold text-sm hover:underline">
                                    Return
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50">
                            <td colspan="6" class="px-6 py-3 text-sm text-gray-700">
                                <strong>Description:</strong>
                                <div class="mt-1 max-h-32 overflow-y-auto whitespace-pre-wrap pr-2">
                                    {{ $ticket->user_description }}
                                </div>

                                <div class="mt-4">
                                    <textarea wire:model.defer="replies.{{ $ticket->id }}" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Write a reply..."></textarea>
                                    <button wire:click="submitReply({{ $ticket->id }})" class="mt-2 px-3 py-1 bg-green-600 text-white rounded text-sm">
                                        Reply
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                No archived tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

</div>
