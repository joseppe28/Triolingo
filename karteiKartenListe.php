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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karteikarten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }
        .sidebar-custom {
            border-radius: 1rem 0 0 1rem;
            box-shadow: 2px 0 20px rgba(0,0,0,0.08);
            background: #fff;
        }
        .profile-card {
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            border-radius: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 1.2rem 1rem;
            text-align: center;
        }
        .profile-card .bi-person-circle {
            font-size: 2.5rem;
            color: #0d6efd;
        }
        .offcanvas-header {
            border-bottom: 1px solid #eee;
        }
        .list-group-item {
            border: none;
            font-size: 1.1rem;
            background: transparent;
            transition: background 0.15s;
        }
        .list-group-item:hover {
            background: #f0f4f8;
        }
        .sidebar-toggle-btn {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 2001;
            background: #fff;
            border-radius: 50%;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        }
        .sidebar-toggle-btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px #0d6efd33;
        }
        .main-content-center {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 80px;
            padding-bottom: 40px;
        }
        .sticky-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            z-index: 1050;
            background: transparent;
            box-shadow: none;
            text-align: center;
            padding: 0;
        }
        .sticky-header h2 {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            letter-spacing: 1px;
            margin-bottom: 0;
            margin-top: 32px;
            color: #222;
            background: transparent;
            padding: 0;
        }
        .flashcard-list {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .flashcard {
            background: linear-gradient(90deg, #f8ffae 0%, #43cea2 100%);
            border-radius: 1.2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            padding: 1rem 1.2rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            transition: transform 0.13s, box-shadow 0.13s;
            position: relative;
            border: none;
            min-width: 180px;
            max-width: 100%;
            gap: 1.2rem;
            text-align: center;
        }
        .flashcard:hover, .flashcard:focus {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .flashcard .icon {
            font-size: 1.5rem;
            color: #0d6efd;
            margin-bottom: 0;
            margin-right: 1rem;
            margin-left: 1rem;
        }
        .flashcard .lang-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 0;
            margin-right: 0.3rem;
        }
        .flashcard .word {
            font-size: 1.1rem;
            font-family: 'Segoe UI', 'Pacifico', cursive;
            color: #222;
            margin-bottom: 0;
            margin-right: 0.7rem;
            margin-left: 0.7rem;
        }
        .flashcard .divider {
            display: none;
        }
        .flashcard-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            width: 100%;
        }
        @media (max-width: 600px) {
            .flashcard-list { padding: 0 0.2rem; }
            .flashcard { flex-direction: column; align-items: center; gap: 0.5rem; padding: 0.7rem 0.4rem; }
            .flashcard-content { flex-direction: column; align-items: center; gap: 0.2rem; }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button id="sidebarToggleBtn" class="btn sidebar-toggle-btn border shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Menü öffnen">
        <i class="bi bi-list fs-2"></i>
    </button>

    <div class="container-fluid" style="height: 100vh; position: relative;">
        <!-- Sidebar -->
        <div class="offcanvas offcanvas-start sidebar-custom" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarLabel"><i class="bi bi-house-door-fill me-2"></i>Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column justify-content-between">
                <ul class="list-group mb-4">
                    <li class="list-group-item"><a href="Main.php" class="text-decoration-none text-dark"><i class="bi bi-house-door me-2"></i>Hauptseite</a></li>
                    <li class="list-group-item"><a href="EinheitenListe.php" class="text-decoration-none text-dark"><i class="bi bi-journal-text me-2"></i>Karteikarten Liste</a></li>
                    <li class="list-group-item"><a href="FehlerStatistik.php" class="text-decoration-none text-dark"><i class="bi bi-exclamation-triangle me-2"></i>Fehler Statistik</a></li>
                </ul>
                <!-- Benutzerinfo Card -->
                <div class="profile-card mt-3">
                    <i class="bi bi-person-circle mb-2"></i>
                    <div class="fw-bold mb-1"><?= htmlspecialchars($_SESSION['username']); ?></div>
                    <a href="benutzerInfo.php" class="btn btn-outline-primary btn-sm w-100 mt-2">Profil anzeigen</a>
                </div>
            </div>
        </div>



        <div class="main-content-center">
            <h2 class="fw-bold"
                style="letter-spacing:1px; font-family: 'Pacifico', cursive; font-size:2.5rem;">
                Karteikarten
            </h2>

            <!-- Hauptinhalt -->
            <div class="main-content-center">
                <div class="flashcard-list">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="flashcard">';
                            echo '<div class="flashcard-content">';
                            echo '<span class="word">' . htmlspecialchars($row['Deutsch']) . '</span>';
                            echo '<span class="icon"><i class="bi bi-card-text"></i></span>';
                            echo '<span class="word">' . htmlspecialchars($row['Englisch']) . '</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-info text-center w-100">Keine Karteikarten gefunden.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');

            sidebar.addEventListener('show.bs.offcanvas', function () {
                toggleBtn.style.display = 'none';
            });
            sidebar.addEventListener('hidden.bs.offcanvas', function () {
                toggleBtn.style.display = '';
            });
        });
    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>