<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Loyalty Card</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    {{-- Header --}}
    <div class="main_header">
        <a href="/">
            <button class="back_button">Back</button>
        </a>
        <h1 class="main_header_text">Dipensa Teknolohiya Grocery</h1>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                Add New Loyalty Card
            </div>
            <form action="/apply" method="post">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" value="{{ old('firstname') }}">
                        @error('firstname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" value="{{ old('lastname') }}">
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="middleinitial" class="form-label">Middle Initial (Optional)</label>
                        <input type="text" class="form-control" id="middleinitial" name="middleinitial" placeholder="Enter Middle Initial" value="{{ old('middleinitial') }}">
                        @error('middleinitial')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="suffix" class="form-label">Suffix (Optional)</label>
                        <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Enter Suffix" value="{{ old('suffix') }}">
                        @error('suffix')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter Contact Number" value="{{ old('contact_number') }}">
                        @error('contact_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary submit-button">Add Loyalty Card</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal for success and barcode --}}
    @if (session('success') || session('barcodePath'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Display the success message --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Display the barcode --}}
                        @if (session('barcodePath'))
                            <div class="text-center mt-4">
                                <h5>Generated Barcode:</h5>
                                <img src="{{ asset(session('barcodePath')) }}" alt="Generated Barcode" class="img-fluid">
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Automatically show the modal if success or barcode exists
        @if (session('success') || session('barcodePath'))
            var myModal = new bootstrap.Modal(document.getElementById('successModal'), {
                keyboard: false
            });
            myModal.show();
        @endif
    </script>
</body>
</html>
