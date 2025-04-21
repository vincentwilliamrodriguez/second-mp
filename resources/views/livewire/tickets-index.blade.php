<div>
    @role('support|admin')
    <div class="max-w-7xl mx-auto my-8">
        <div class="bg-white shadow rounded-lg">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-lg font-bold">Support Tickets</h3>
                <h4 class="text-lg font-bold">Update status accordingly</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr class="text-black">
                            <th class="px-4 py-2">Ticket #</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2">Created</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2"></th>
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
                                    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                        <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1 text-xs">
                                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="done" {{ $ticket->status == 'done' ? 'selected' : '' }}>Done</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                        <input type="hidden" name="action" value="hide">
                                        <button type="submit" class="text-green-600 font-bold text-base hover:underline"
                                                onclick="return confirm('Are you sure you want to delete this ticket?')">
                                            Archive
                                        </button>
                                    </form>

                                    @role('admin')
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="text-red-600 font-bold text-base hover:underline"
                                                onclick="return confirm('Are you sure you want to permanently delete this ticket?')">
                                            Delete
                                        </button>
                                    </form>
                                    @endrole
                                </td>
                            </tr>
                            <tr class="border-b bg-gray-50">
                                <td colspan="7" class="px-6 py-3 text-sm text-gray-700">
                                    <strong>Description:</strong><div class="mt-1 max-h-32 overflow-y-auto whitespace-pre-wrap break-words pr-2">{{ $ticket->user_description }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endrole
</div>
