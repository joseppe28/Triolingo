<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Beispiel-Daten (normalerweise aus der Datenbank abrufen)
$username = $_SESSION['username'];
$email = "example@example.com"; // Dies sollte aus der Datenbank kommen
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerinfo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-50 p-4 shadow">
            <h2 class="text-center mb-4">Benutzerinfo</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Benutzername:</strong> <?php echo htmlspecialchars($username); ?></li>
                <li class="list-group-item"><strong>E-Mail:</strong> <?php echo htmlspecialchars($email); ?></li>
            </ul>
            <div class="text-center mt-4">
                <a href="Main.php" class="btn btn-primary">Zurück</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>