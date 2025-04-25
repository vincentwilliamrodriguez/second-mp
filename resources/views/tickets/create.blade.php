<x-tab>
    @livewire('tickets-create')
</x-tab>

{{-- <div class="w-[50vw] max-w-[900px] mx-auto">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        @if(session('ticket_submitted'))
            <div class="bg-green-300 text-black px-6 py-4">
                <h3 class="text-xl font-semibold">Thank You for Your Submission!</h3>
            </div>
            <div class="p-8 text-center">
                <h4 class="text-2xl font-bold text-gray-800 mb-2">Your ticket has been submitted!</h4>
                <p class="text-gray-600 mb-6">We'll contact you shortly regarding your request!</p>

                <div class="bg-gray-100 rounded-lg p-6 inline-block">
                    <p class="text-gray-600 mb-1">Your ticket number is:</p>
                    <p class="text-2xl font-bold text-blue-600">{{ session('ticket_number') }}</p>
                </div>

                <p class="mt-6 text-gray-600">Please keep this number for your reference</p>

                <a href="{{ route('tickets.index') }}" class="mt-6 inline-block bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Another Ticket
                </a>
            </div>
        @else
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-semibold">Contact us via this form, and wait for your Support Ticket!</h3>
            </div>

            <div class="p-6">
                <form id="ticket-form" action="{{ route('tickets.index') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="user_name" class="block text-gray-700 font-black medium mb-2">Name:</label>
                        <input type="text" id="user_name" name="user_name" placeholder="Your Name" required
                            class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-5">
                        <label for="user_email" class="block text-gray-700 font-medium mb-2">Email:</label>
                        <input type="email" id="user_email" name="user_email" placeholder="Your Email" required
                            class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-5">
                        <label for="user_phone" class="block text-gray-700 font-medium mb-2">Phone Number:</label>
                        <input type="text" id="user_phone" name="user_phone" placeholder="Your Phone Number"
                            class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-5">
                        <label for="user_description" class="block text-gray-700 font-medium mb-2">Describe Your Issue:</label>
                        <textarea id="user_description" name="user_description" placeholder="Please describe your issue or request here..." required
                            class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="5"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div> --}}

