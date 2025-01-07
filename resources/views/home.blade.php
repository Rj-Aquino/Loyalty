<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home Page</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    
    <div class="main_header">
        <h1 class="main_header_text">Dipensa Teknolohiya Grocery</h1>
    </div>

    <div id="main_container" class="d-flex flex-column justify-content-center align-items-center vh-100">

        {{-- div for "Welcome Customers" --}}
        <div class="secondary_header mb-4">
            <h2>Welcome Loyal Customers</h2>
        </div>
    
        {{-- div for "Apply for a Membership" button --}}
        <div class="container mb-3">
            <a href="/apply">
                <button class="btn btn-success btn-lg rounded-pill px-5 py-3">APPLY FOR A MEMBERSHIP</button>
            </a>
        </div>
    
        {{-- div for "Check Points" button --}}
        <div class="container">
            <a href="/viewpoints">
                <button class="btn btn-success btn-lg rounded-pill px-5 py-3">VIEW POINTS</button>
            </a>
        </div>
    </div>

</body>
</html>