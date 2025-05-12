<?php
session_start();

// Destroy the session
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e3f2fd; /* Heller Blauton */
        }
        h2 {
            color: #0d6efd; /* Bootstrap Primärfarbe */
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-50 p-4 shadow">
            <h2 class="text-center mb-4">Erfolgreich ausgeloggt</h2>
            <p class="text-center">Sie wurden erfolgreich ausgeloggt. Klicken Sie unten, um zur Login-Seite zurückzukehren.</p>
            <div class="text-center mt-4">
                <a href="Login.php" class="btn btn-primary w-100">Zur Login-Seite</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>