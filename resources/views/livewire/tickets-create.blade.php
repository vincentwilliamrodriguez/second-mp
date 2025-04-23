<div class="w-[50vw] max-w-[900px] mx-auto">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        @if($ticket_submitted)
            <div class="bg-green-300 text-black px-6 py-4">
                <h3 class="text-xl font-semibold">Thank You for Your Submission!</h3>
            </div>
            <div class="p-8 text-center">
                <h4 class="text-2xl font-bold text-gray-800 mb-2">Your ticket has been submitted!</h4>
                <p class="text-gray-600 mb-6">We'll contact you shortly regarding your request!</p>

                <div class="bg-gray-100 rounded-lg p-6 inline-block">
                    <p class="text-gray-600 mb-1">Your ticket number is:</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $ticket_number }}</p>
                </div>

                <p class="mt-6 text-gray-600">Please keep this number for your reference</p>

                <button wire:click="resetForm"
                    class="mt-6 inline-block bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Another Ticket
                </button>
            </div>
        @else
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-[20px] font-semibold">Contact us via this form, and wait for your Support Ticket!</h3>
            </div>
            <form wire:submit.prevent="submitTicket" class="p-6">
                <div class="mb-5">
                    <label for="user_name" class="block text-gray-700 font-black medium mb-2">Name:</label>
                    <input wire:model="user_name" type="text" id="user_name" name="user_name" placeholder="Your Name" required
                        class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                </div>
                <div class="mb-5">
                    <label for="user_phone" class="block text-gray-700 font-medium mb-2">Phone Number:</label>
                    <input wire:model="user_phone" type="text" id="user_phone" name="user_phone" placeholder="Your Phone Number"
                        class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                </div>
                <div class="mb-5">
                    <label for="user_description" class="block text-gray-700 font-medium mb-2">Describe Your Issue:</label>
                    <textarea wire:model="user_description" id="user_description" name="user_description"
                        placeholder="Please describe your issue or request here..." required
                        class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                        rows="5"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit"
                        class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Submit Ticket
                    </button>
                </div>

                <div class="text-center mt-4">
                    <div class="inline-block bg-white border border-blue-600 rounded-lg px-6 py-4">
                        <button wire:click="showUserTickets"
                            type="button"
                            class="text-blue-600 font-semibold hover:underline">
                            View My Submitted Tickets
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    @if($showTicketsModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg p-6 w-[90%] max-w-[700px] max-h-[80vh] overflow-y-auto relative">
                <button wire:click="closeModal" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
                <h2 class="text-2xl font-bold mb-4 text-center text-blue-600">Your Submitted Tickets</h2>

                @forelse($userTickets as $ticket)
                    <div class="border rounded-lg p-4 mb-6 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-800 font-semibold">Ticket Number: {{ $ticket->ticket_number }}</p>
                                <p class="text-sm text-gray-600 mt-1">Submitted: {{ $ticket->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                            <div class="text-xs px-2 py-1 rounded {{ $ticket->is_hidden ? 'bg-green-200 text-green-800':'bg-gray-200 text-gray-700'}}">
                                {{ $ticket->is_hidden ? 'Accepted' : 'Available' }}
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-gray-50 rounded">
                            <p class="text-gray-700">{{ $ticket->user_description }}</p>
                        </div>

                        @if($ticket->replies && $ticket->replies->count() > 0)
                            <div class="mt-4 border-t pt-3">
                                <h4 class="font-medium text-gray-700 mb-2">Conversation:</h4>
                                <div class="space-y-3">
                                    @foreach($ticket->replies as $reply)
                                        <div class="p-3 rounded-lg {{ $reply->is_from_staff ? 'bg-blue-50' : 'bg-green-50' }}">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-medium text-sm {{ $reply->is_from_staff ? 'text-blue-600' : 'text-green-600' }}">
                                                    {{ $reply->is_from_staff ? 'Support Staff' : 'You' }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $reply->created_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                            <p class="text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($replyingTo === $ticket->id)
                            <div class="mt-4 p-3 border border-gray-200 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2">Your Reply:</h4>
                                <textarea
                                    wire:model.defer="replyText"
                                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-300 outline-none text-black"
                                    rows="3"
                                    placeholder="Type your reply here..."></textarea>
                                @error('replyText') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                                <div class="flex space-x-2 mt-2">
                                    <button wire:click="sendReply" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Send Reply
                                    </button>
                                    <button wire:click="cancelReply" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="mt-3">
                                <button wire:click="startReply({{ $ticket->id }})" class="text-blue-600 hover:underline text-sm">
                                    Reply to this ticket
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-600">No previous tickets found for this email.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
