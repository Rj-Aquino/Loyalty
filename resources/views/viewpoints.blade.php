<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Loyalty Card Points</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            <div id="scanner-container">
                <div id="video-preview"></div> <!-- Quagga will insert the video feed here -->
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
    </div>

    {{-- Success Modal --}}
    @if (session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Error Alert --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show the success modal if there's a success message
        @if (session('success'))
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        @endif

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
