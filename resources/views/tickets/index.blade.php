<x-app-layout>

    <div>
        <h3>Contact us via this form, and wait for your Support Ticket!</h3>

        <form id="ticket-form">
            <div class="mb-3">
                <label for="user-name" class="block text-gray-700">Name:</label>
                <input type="text" id="user-name" name="name" placeholder="Your Name" required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mb-3">
                <label for="user-email" class="block text-gray-700">Email:</label>
                <input type="email" id="user-email" name="email" placeholder="Your Email" required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mb-3">
                <label for="user-phone" class="block text-gray-700">Phone Number:</label>
                <input type="text" id="user-phone" name="phone" placeholder="Your Phone Number" class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mt-4">
                <label for="user-description" class="block text-gray-700">Describe Your Issue:</label>
                <textarea id="user-description" name="description" placeholder="Please describe your issue or request here..." required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px; height: 150px;"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 mt-2">Submit</button>
        </form>

        <div id="confirmation" class="mt-5 hidden">
            <p>Your ticket has been submitted successfully. We will contact you shortly!</p>
        </div>
    </div>
</x-app-layout>
