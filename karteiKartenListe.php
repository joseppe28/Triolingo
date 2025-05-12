<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Überprüfen, ob die Einheit-ID übergeben wurde
if (!isset($_GET['einheit_id'])) {
    header("Location: EinheitenListe.php");
    exit();
}

$einheit_id = intval($_GET['einheit_id']);

// Datenbankverbindung
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Triolingo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Vokabeln für die Einheit abrufen
$sql = "SELECT d.Wort AS Deutsch, e.Wort AS Englisch 
        FROM Vocab v
        JOIN Deutsch_Vocab d ON v.DID = d.DID
        JOIN Englisch_Vocab e ON v.EID = e.EID
        WHERE v.EinID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $einheit_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karteikarten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e3f2fd; /* Heller Blauton */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Karteikarten</h2>
        <?php
        if ($result->num_rows > 0) {
            // Karteikarten anzeigen
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">Deutsch:</h5>';
                echo '<p class="card-text">' . htmlspecialchars($row['Deutsch']) . '</p>';
                echo '<h5 class="card-title">Englisch:</h5>';
                echo '<p class="card-text">' . htmlspecialchars($row['Englisch']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-center">Keine Karteikarten gefunden.</p>';
        }
        ?>
        <div class="text-center mt-4">
            <a href="EinheitenListe.php" class="btn btn-primary">Zurück zur Einheitenliste</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>