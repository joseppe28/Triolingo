<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-danger bg-opacity-10">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-50 p-4 shadow-lg border-danger">
            <h2 class="text-center text-danger mb-4">Ein Fehler ist aufgetreten</h2>
            <p class="text-center text-muted mb-4">
                <?php
                // Überprüfen, ob ein Fehler in der Session gespeichert ist
                if (isset($_SESSION['err'])) {
                    echo htmlspecialchars($_SESSION['err']); // Fehlergrund anzeigen
                } else {
                    echo "Ein unbekannter Fehler ist aufgetreten.";
                }
                ?>
            </p>
            <div class="text-center">
                <a href="Login.php" class="btn btn-primary w-100">Zurück zur Login-Seite</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>