<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Get Loyalty Card Details</title>
    
    <!-- Include the JS file from public/js -->
    <script src="js/apiHandler.js"></script> <!-- Corrected path for asset -->
</head>
<body>
    <h1>Loyalty Card Details</h1>

    <label for="loyalty-card-id">Enter Loyalty Card ID:</label>
    <input type="number" id="loyalty-card-id" placeholder="Enter Loyalty Card ID" required>
    <button onclick="getLoyaltyCard()">Fetch Loyalty Card</button>

    <div id="loyalty-card-details">Loading loyalty card...</div>
    <div id="error-message"></div>

    <script>
        async function getLoyaltyCard() {
            const loyaltyCardId = document.getElementById('loyalty-card-id').value;
            if (!loyaltyCardId) {
                displayError('Please enter a Loyalty Card ID.');
                return;
            }

            try {
                // Pass the dynamically entered Loyalty Card ID to the apiHandler function
                const loyaltyCard = await apiHandler('fetchLoyaltyCard', loyaltyCardId);
                renderLoyaltyCard(loyaltyCard);
            } catch (error) {
                displayError(error);
            }
        }

        // Function to render loyalty card data
        function renderLoyaltyCard(loyaltyCard) {
            const loyaltyCardDetails = document.getElementById('loyalty-card-details');
            loyaltyCardDetails.innerHTML = ''; // Clear previous content

            if (!loyaltyCard || !loyaltyCard.LoyaltyCardID) {
                loyaltyCardDetails.innerHTML = '<p>No loyalty card found with that ID.</p>';
                return;
            }

            loyaltyCardDetails.innerHTML = `
                <strong>Loyalty Card ID:</strong> ${loyaltyCard.LoyaltyCardID} <br>
                <strong>Name:</strong> ${loyaltyCard.FirstName} ${loyaltyCard.MiddleInitial ? loyaltyCard.MiddleInitial + '.' : ''} ${loyaltyCard.LastName} ${loyaltyCard.Suffix ? loyaltyCard.Suffix : ''} <br>
                <strong>Contact Number:</strong> ${loyaltyCard.ContactNo} <br>
                <strong>Points:</strong> ${loyaltyCard.Points} <br>
                <strong>Created At:</strong> ${loyaltyCard.created_at} <br>
                <strong>Updated At:</strong> ${loyaltyCard.updated_at}
            `;
        }

        // Function to handle displaying error messages
        function displayError(message) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.innerHTML = `<p>Error: ${message}</p>`;
        }
    </script>
</body>
</html>
