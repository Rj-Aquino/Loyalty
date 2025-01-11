<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Get Transactions by Loyalty Card</title>
    
    <!-- Include the JS file from public/js -->
    <script src="js/apiHandler.js"></script> <!-- Corrected path for asset -->
</head>
<body>
    <h1>Transaction List</h1>

    <label for="loyalty-card-id">Enter Loyalty Card ID:</label>
    <input type="number" id="loyalty-card-id" placeholder="Enter Loyalty Card ID" required>
    <button onclick="getTransactions()">Fetch Transactions</button>

    <div id="transaction-list">Loading transactions...</div>
    <div id="error-message"></div>

    <script>
        async function getTransactions() {
            const loyaltyCardId = document.getElementById('loyalty-card-id').value;
            if (!loyaltyCardId) {
                displayError('Please enter a Loyalty Card ID.');
                return;
            }

            try {
                // Pass the dynamically entered Loyalty Card ID to the apiHandler function
                const transactions = await apiHandler('fetchTransactionsByLoyaltyCard', loyaltyCardId);
                renderTransactions(transactions);
            } catch (error) {
                displayError(error);
            }
        }

        // Function to render the transaction list
        function renderTransactions(transactions) {
            const transactionList = document.getElementById('transaction-list');
            transactionList.innerHTML = ''; // Clear previous content

            if (!transactions || transactions.length === 0) {
                transactionList.innerHTML = '<p>No transactions found for this Loyalty Card ID.</p>';
                return;
            }

            let html = '<ul>';
            transactions.forEach(transaction => {
                html += `
                    <li>
                        <strong>Transaction ID:</strong> ${transaction.TransactionID} <br>
                        <strong>Order ID:</strong> ${transaction.OrderID} <br>
                         <strong>User ID:</strong> ${transaction.UserID} <br>
                        <strong>Total Points Used:</strong> ${transaction.TotalPointsUsed} <br>
                        <strong>Points Earned:</strong> ${transaction.PointsEarned} <br>
                        <strong>Transaction Date:</strong> ${transaction.TransactionDate} <br>
                    </li>
                `;
            });
            html += '</ul>';
            transactionList.innerHTML = html;
        }

        // Function to handle displaying error messages
        function displayError(message) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.innerHTML = `<p>Error: ${message}</p>`;
        }
    </script>
</body>
</html>
