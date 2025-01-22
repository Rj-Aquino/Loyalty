<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Loyalty Card</title>
    <link rel="stylesheet" href="/css/addloyalty.css">
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

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card" style="width: 30rem;">
            <div class="card-header text-center">
                Add New Loyalty Card
            </div>
            <form action="/apply" method="post">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" value="{{ old('firstname') }}" maxlength="50" required>
                        @error('firstname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" value="{{ old('lastname') }}" maxlength="50" required>
                        @error('lastname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="middleinitial" class="form-label">Middle Initial (Optional)</label>
                        <input type="text" class="form-control" id="middleinitial" name="middleinitial" placeholder="Enter Middle Initial" value="{{ old('middleinitial') }}" maxlength="1" pattern="[A-Za-z]">
                        @error('middleinitial')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="suffix" class="form-label">Suffix (Optional)</label>
                        <select name="suffix" id="suffix" class="form-control">
                            <option value="" style="color:gray;">None</option>
                            <option value="Sr.">Sr.</option>
                            <option value="Jr.">Jr.</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                            <option value="VI">VI</option>
                        </select>
                        @error('suffix')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label mb-0">Contact Number <span class="text-danger">*</span></label><br>
                        <small style="color:gray;">(09XXXXXXXXX)</small>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter Contact Number" value="{{ old('contact_number') }}" pattern="^09\d{9}$" maxlength="11" required>
                        @error('contact_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary submit-button">Add Loyalty Card</button>
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
        
        document.getElementById('suffix').addEventListener('change', dropdownColor);
        dropdownColor();

        function dropdownColor() {
        var suffixDropdown = document.getElementById('suffix');
        var options = suffixDropdown.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value === '') {
                options[i].style.color = 'gray';
            } else {
                options[i].style.color = 'black';
            }
        }
        if (suffixDropdown.value === '') {
            suffixDropdown.style.color = 'gray';
        } else {
            suffixDropdown.style.color = 'black';
        }
    }
    </script>
</body>
</html>
