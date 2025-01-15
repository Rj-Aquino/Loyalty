<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Loyalty Card Points</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #scanner-container {
            width: 300px;
            height: 200px;
            overflow: hidden;
            position: relative;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #video-preview {
            width: 100%;
            height: 100%;
            background-color: black;
        }

        #video-preview video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="main_header">
        <a href="/">
            <button class="back_button">Back</button>
        </a>
        <h1 class="main_header_text">Dipensa Teknolohiya Grocery</h1>
    </div>

    <div class="container mt-4">
        <div class="row">
            {{-- Camera and Barcode Scanner --}}
            <div class="col-md-6">
                <div id="scanner-container">
                    <div id="video-preview"></div>
                </div>
            </div>

            {{-- Form to View Loyalty Card Points --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        View Loyalty Card Points
                    </div>
                    <form id="viewPointsForm" action="/viewpoints" method="post">
                        @csrf
                        <div class="card-body">
                            <!-- Hidden Inputs for barcode scanning -->
                            <input type="hidden" id="loyaltycardID" name="loyaltycardID">
                            <input type="hidden" id="firstname" name="firstname">
                            <input type="hidden" id="lastname" name="lastname">

                            <!-- Manual Inputs -->
                            <div class="mb-3">
                                <label for="manualLoyaltyCardID" class="form-label">Loyalty Card ID</label>
                                <input type="text" class="form-control" id="manualLoyaltyCardID" name="manualLoyaltyCardID" placeholder="Enter Loyalty Card ID" value="{{ old('manualLoyaltyCardID') }}">
                                @error('manualLoyaltyCardID')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manualFirstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="manualFirstname" name="manualFirstname" placeholder="Enter First Name" value="{{ old('manualFirstname') }}">
                                @error('manualFirstname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manualLastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="manualLastname" name="manualLastname" placeholder="Enter Last Name" value="{{ old('manualLastname') }}">
                                @error('manualLastname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary submit-button">View Points</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Points Display Section --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info" role="alert" id="pointsDisplay">
                    No points data available. Please scan or enter a loyalty card.
                </div>
            </div>
        </div>

        {{-- Error Alert --}}
        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Table Section for Transactions --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Loyalty Card Transactions
                    </div>
                    <div class="card-body">
                        @if(!empty($transactions) && count($transactions) > 0)
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">User ID</th>
                                        <th scope="col">Total Points Used</th>
                                        <th scope="col">Points Earned</th>
                                        <th scope="col">Transaction Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction['TransactionID'] }}</td>
                                            <td>{{ $transaction['OrderID'] }}</td>
                                            <td>{{ $transaction['UserID'] }}</td>
                                            <td>{{ $transaction['TotalPointsUsed'] }}</td>
                                            <td>{{ $transaction['PointsEarned'] }}</td>
                                            <td>{{ $transaction['TransactionDate'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No transactions found for this loyalty card.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Display points if available
        const pointsData = "{{ session('points') ?? '' }}";
        const pointsDisplay = document.getElementById('pointsDisplay');

        if (pointsData) {
            pointsDisplay.classList.replace('alert-info', 'alert-success');
            pointsDisplay.innerText = `Points Available: ${pointsData}`;
        }

        // Initialize QuaggaJS
        Quagga.init({
            inputStream: {
                type: "LiveStream",
                target: document.querySelector("#video-preview"),
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
            }
        }, function(err) {
            if (err) {
                console.error("Error initializing Quagga:", err);
                return;
            }
            console.log("QuaggaJS initialized.");
            Quagga.start();
        });

        Quagga.onDetected(function(data) {
            const barcode = data.codeResult.code;
            console.log("Barcode detected:", barcode);

            const parts = barcode.split("-");
            if (parts.length === 3) {
                document.getElementById("loyaltycardID").value = parts[0];
                document.getElementById("firstname").value = parts[1];
                document.getElementById("lastname").value = parts[2];
                document.getElementById("viewPointsForm").submit();
            } else {
                console.error("Invalid barcode format.");
            }
        });
    </script>
</body>
</html>
