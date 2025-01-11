<!-- resources/views/transactions.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction List</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Optional for styling -->
     <!-- Include external JS file -->
     <script src="'js/apiHandler.js'"></script>
</head>
<body>
    <div class="container">
        <h1>Transaction List</h1>
        <form id="loyalty-card-form">
            <label for="loyalty-card-id">Enter Loyalty Card ID:</label>
            <input type="text" id="loyalty-card-id" name="loyalty-card-id" required>
            <button type="submit">Get Transactions</button>
        </form>

        <div id="transaction-list">
            <!-- Transaction list will be populated here -->
        </div>
    </div>
</body>
</html>
