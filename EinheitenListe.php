<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Datenbankverbindung
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Triolingo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Einheiten aus der Datenbank abrufen
$sql = "SELECT EinID, Thema, Beschreibung FROM Einheit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einheiten Liste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: #F8F9FA; /* Heller Blauton */
        }
    </style>
</head>
<body>
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
                <li class="list-group-item"><a href="EinheitenListe.php" class="text-decoration-none">Karteikarten Liste</a></li>
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
    <div class="container mt-5">
        <h2 class="text-center mb-4">Einheiten</h2>
        <div class="list-group">
            <?php
            if ($result->num_rows > 0) {
                // Einheiten anzeigen
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="karteikartenListe.php?einheit_id=' . $row['EinID'] . '" class="list-group-item list-group-item-action">';
                    echo '<strong>' . htmlspecialchars($row['Thema']) . '</strong><br>';
                    echo '<small>' . htmlspecialchars($row['Beschreibung']) . '</small>';
                    echo '</a>';
                }
            } else {
                echo '<p class="text-center">Keine Einheiten gefunden.</p>';
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>