<x-app-layout>
    <div class="max-w-2xl mx-auto my-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h3 class="text-xl font-semibold">Contact us via this form, and wait for your Support Ticket!</h3>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="ticket-form" action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="user_name" class="block text-gray-700 font-medium mb-2">Name:</label>
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
        </div>
    </div>
</x-app-layout>
