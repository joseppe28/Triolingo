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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einheiten Liste</title>
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
        .main-content-center {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
        .einheit-card {
            width: 420px;
            min-height: 90px;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            color: #222;
            font-weight: 500;
            border: none;
            margin-bottom: 22px;
            transition: transform 0.13s, box-shadow 0.13s, background 0.13s;
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            padding: 1.5rem 2rem;
            text-align: left;
            text-decoration: none;
        }
        .einheit-card:hover, .einheit-card:focus {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            background: linear-gradient(90deg, #fed6e3 0%, #a8edea 100%);
            color: #0d6efd;
            text-decoration: none;
        }
        .einheit-card .bi {
            font-size: 2.2rem;
            margin-top: 0.2rem;
        }
        .einheit-info {
            flex: 1;
        }
        .einheit-title {
            font-size: 1.4rem;
            font-family: 'Pacifico', cursive;
            margin-bottom: 0.2rem;
        }
        .einheit-desc {
            font-size: 1.05rem;
            color: #555;
        }
        @media (max-width: 600px) {
            .einheit-card { width: 100%; min-height: 70px; font-size: 1.1rem; padding: 1rem 1rem;}
            .sidebar-toggle-btn { top: 12px; left: 12px; width: 44px; height: 44px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button (außerhalb der Sidebar, überlappt nichts) -->
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

        <!-- Hauptinhalt -->
        <div class="main-content-center">
            <div class="mb-5">
                <h2 class="fw-bold mt-2 mb-4 text-center" style="letter-spacing:1px; font-family: 'Pacifico', cursive; font-size:2.5rem;">
                    Einheiten
                </h2>
            </div>
            <div class="d-flex flex-column align-items-center gap-2 w-100">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="karteikartenListe.php?einheit_id=' . $row['EinID'] . '" class="einheit-card">';
                        echo '<i class="bi bi-collection"></i>';
                        echo '<div class="einheit-info">';
                        echo '<div class="einheit-title">' . htmlspecialchars($row['Thema']) . '</div>';
                        echo '<div class="einheit-desc">' . htmlspecialchars($row['Beschreibung']) . '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                } else {
                    echo '<div class="alert alert-info text-center w-100">Keine Einheiten gefunden.</div>';
                }
                ?>
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
$conn->close();
?>