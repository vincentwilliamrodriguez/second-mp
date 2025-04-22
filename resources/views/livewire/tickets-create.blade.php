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
                <h3 class="text-xl font-semibold">Contact us via this form, and wait for your Support Ticket!</h3>
            </div>
            <form wire:submit.prevent="submitTicket" class="p-6">
                <div class="mb-5">
                    <label for="user_name" class="block text-gray-700 font-black medium mb-2">Name:</label>
                    <input wire:model="user_name" type="text" id="user_name" name="user_name" placeholder="Your Name" required
                        class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                </div>
                <div class="mb-5">
                    <label for="user_email" class="block text-gray-700 font-medium mb-2">Email:</label>
                    <input wire:model="user_email" type="email" id="user_email" name="user_email" placeholder="Your Email" required
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
                    <div class="border rounded-lg p-4 mb-3 shadow-sm">
                        <p class="text-gray-800 font-semibold">Ticket #: {{ $ticket->ticket_number }}</p>
                        <p class="text-sm text-gray-600">Status: {{ ucfirst($ticket->status) }}</p>
                        <p class="text-sm text-gray-600 mt-1">Submitted: {{ $ticket->created_at->format('F j, Y g:i A') }}</p>
                        <p class="text-gray-700 mt-2">{{ $ticket->user_description }}</p>
                    </div>
                @empty
                    <p class="text-center text-gray-600">No previous tickets found for this email.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>


