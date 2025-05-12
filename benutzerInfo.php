<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Beispiel-Daten (normalerweise aus der Datenbank abrufen)
$username = $_SESSION['username'];
$email = $_SESSION['email'];	

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
<!-- Sidebar -->
    <div class="container-fluid bg-light" style="height: 100vh; position: relative;">
        <!-- Sidebar Toggle Button -->
        <div class="position-absolute" style="top: 10px; left: 10px;">
            <button class="btn btn-light border" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                <i class="bi bi-list"></i> <!-- Bootstrap Icon -->
            </button>
        </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <ul class="list-group">
                <li class="list-group-item"><a href="Main.php" class="text-decoration-none">Hauptseite</a></li>
                <li class="list-group-item"><a href="page2.php" class="text-decoration-none">Page 2</a></li>
                <li class="list-group-item"><a href="page3.php" class="text-decoration-none">Page 3</a></li>
            </ul>
            <!-- Benutzerinfo Button -->
            <div class="mt-3">
                <a href="benutzerInfo.php" class="btn btn-primary w-100 text-decoration-none text-white">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-50 p-4 shadow">
            <h2 class="text-center mb-4">Benutzerinfo</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Benutzername:</strong> <?php echo htmlspecialchars($username); ?></li>
                <li class="list-group-item"><strong>E-Mail:</strong> <?php echo htmlspecialchars($email); ?></li>
            </ul>
            <div class="d-flex justify-content-between mt-4">
            <a href="Main.php" class="btn btn-primary">Zurück</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>