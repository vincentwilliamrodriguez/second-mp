<x-app-layout>
    <div>
        <h3>Contact us via this form, and wait for your Support Ticket!</h3>

        @if(session('success'))
            <div class="mt-4 p-4 mb-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form id="ticket-form" action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_name" class="block text-gray-700">Name:</label>
                <input type="text" id="user_name" name="user_name" placeholder="Your Name" required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mb-3">
                <label for="user_email" class="block text-gray-700">Email:</label>
                <input type="email" id="user_email" name="user_email" placeholder="Your Email" required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mb-3">
                <label for="user_phone" class="block text-gray-700">Phone Number:</label>
                <input type="text" id="user_phone" name="user_phone" placeholder="Your Phone Number" class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mt-4">
                <label for="user_description" class="block text-gray-700">Describe Your Issue:</label>
                <textarea id="user_description" name="user_description" placeholder="Please describe your issue or request here..." required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px; height: 150px;"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 mt-2">Submit</button>
        </form>
    </div>
</x-app-layout>
