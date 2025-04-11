<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div>
        <h3>Contact us via this form, and wait for your Support Ticket!</h3></h3>

        <form id="ticket-form">
            <div class="mb-3">
                <label for="user-name" class="block text-gray-700">Name:</label>
                <input type="text" id="user-name" name="user_name" placeholder="Your Name" required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mb-3">
                <label for="user-email" class="block text-gray-700">Email:</label>
                <input type="email" id="user-email" name="user_email" placeholder="Your Email" required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mb-3">
                <label for="user-phone" class="block text-gray-700">Phone Number:</label>
                <input type="text" id="user-phone" name="user_phone" placeholder="Your Phone Number" class="py-2 px-4 rounded-lg border mt-2" style="width: 300px;">
            </div>

            <div class="mt-4">
                <label for="user-description" class="block text-gray-700">Describe Your Issue:</label>
                <textarea id="user-description" name="user_description" placeholder="Please describe your issue or request here..." required class="py-2 px-4 rounded-lg border mt-2" style="width: 300px; height: 150px;"></textarea>
            </div>

            <button type="submit" class="bg-blue-300 text-white py-2 px-4 rounded-lg hover:bg-blue-400 mt-2">Submit</button>
        </form>

        <div id="confirmation" class="mt-5" style="display: none;">
            <p>Your ticket has been submitted successfully. We will contact you shortly!</p>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('ticket-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const userName = document.getElementById('user-name').value;
            const userEmail = document.getElementById('user-email').value;
            const userPhone = document.getElementById('user-phone').value;

            fetch('/ticket', {
                method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
        userName,
        userEmail,
        userPhone,
        user_description: document.getElementById('user-description').value
    })
})

            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => Promise.reject(error));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('ticket-form').style.display = 'none';
                    document.getElementById('confirmation').style.display = 'block';
                } else {
                    alert('There was an error submitting your ticket. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + (error.message || 'An unexpected error occurred.'));
            });
    </script>

</x-app-layout>
