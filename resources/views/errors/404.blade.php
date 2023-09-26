<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add your custom CSS styles here, if needed -->
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="display-4">404 - Page Not Found</h1>
                <p class="lead">Sorry, the page you are looking for does not exist.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Go to Home</a>
                <!-- Add any additional content or Bootstrap components here -->
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and any custom JavaScript here, if needed -->
</body>

</html>
