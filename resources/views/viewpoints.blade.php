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
            width: 100%;
            height: 400px;
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
    <div class="main_header d-flex flex-column justify-content-center align-items-center">
        <div class="store_logo">
            <img src="Picture/StoreLogo.png" alt="Store Logo" class="img-fluid">
        </div>
        <h1 class="main_header_text">Dipensa Teknolohiya Grocery</h1>
    </div>

    <a href="/">
        <button class="back_button">Back</button>
    </a>

    <div class="container mt-4" id="main_container">
        <div class="row">
            {{-- Camera and Barcode Scanner --}}
            <div class="col-md-4">
                <div id="scanner-container">
                    <div id="video-preview"></div>
                </div>
            </div>

            {{-- Form to View Loyalty Card Points --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        View Loyalty Card Points
                    </div>
                    <form id="viewPointsForm" action="/viewpoints" method="post">
                        @csrf
                        <div class="card-body">
                            <!-- Hidden Inputs for barcode scanning -->
                            <input type="hidden" id="loyaltycardID" name="loyaltycardID">

                            <!-- Manual Inputs -->
                            <div class="mb-3">
                                <label for="manualLoyaltyCardID" class="form-label">Loyalty Card UID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="manualLoyaltyCardID" name="manualLoyaltyCardID" placeholder="Enter Loyalty Card ID" value="{{ old('manualLoyaltyCardID') }}" required>
                                @error('manualLoyaltyCardID')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manualFirstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="manualFirstname" name="manualFirstname" placeholder="Enter First Name" value="{{ old('manualFirstname') }}" required>
                                @error('manualFirstname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manualLastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="manualLastname" name="manualLastname" placeholder="Enter Last Name" value="{{ old('manualLastname') }}" required>
                                @error('manualLastname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="submit-button">View Points</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Points Display Section --}}
        <div class="row mt-4">
            <div class="col-12">
                @if (!empty($points))
                    <div class="alert alert-success" role="alert" id="pointsDisplay">
                        Points Available: {{ $points }}
                    </div>
                @else
                    <div class="alert alert-warning" role="alert" id="pointsDisplay">
                        No points data available. Please scan or enter a loyalty card.
                    </div>
                @endif
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
                                            <td>{{ $transaction['TransactionUniqueIdentifier'] }}</td>
                                            <td>{{ $transaction['OrderUniqueIdentifier'] }}</td>
                                            <td>{{ $transaction['UserUniqueIdentifier'] }}</td>
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

            document.getElementById("loyaltycardID").value = barcode;
            document.getElementById("viewPointsForm").submit();
        });
    </script>
</body>
</html>
