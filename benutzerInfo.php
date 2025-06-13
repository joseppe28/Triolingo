<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerinfo</title>
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
            justify-content: center;
        }
        .user-info-card {
            width: 350px;
            max-width: 95vw;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: #fff;
            padding: 2.2rem 2rem 1.5rem 2rem;
            margin-top: 2rem;
        }
        .user-info-card h2 {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .user-info-list .list-group-item {
            background: transparent;
            border: none;
            font-size: 1.13rem;
            padding-left: 0;
            padding-right: 0;
        }
        .user-info-list .list-group-item strong {
            color: #0d6efd;
        }
        .user-info-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        @media (max-width: 600px) {
            .user-info-card { padding: 1.2rem 0.5rem 1rem 0.5rem; }
            .sidebar-toggle-btn { top: 12px; left: 12px; width: 44px; height: 44px; }
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

        <!-- Hauptinhalt -->
        <div class="main-content-center">
            <div class="user-info-card">
                <h2>Benutzerinfo</h2>
                <div class="text-center mb-4">
                    <div class="row g-3 justify-content-center">
                        <div class="col-12">
                            <div class="p-3 rounded-4 shadow-sm" style="background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);">
                                <span class="fw-bold text-primary"><i class="bi bi-person"></i> Benutzername:</span><br>
                                <span class="fs-5"><?= htmlspecialchars($username); ?></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-4 shadow-sm" style="background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);">
                                <span class="fw-bold text-primary"><i class="bi bi-envelope"></i> E-Mail:</span><br>
                                <span class="fs-5"><?= htmlspecialchars($email); ?></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-4 shadow-sm d-flex flex-column align-items-center" style="background: linear-gradient(90deg, #b2f7c1 0%, #e0eafc 100%);">
                                <i class="bi bi-check-circle text-success fs-1 mb-2"></i>
                                <span class="fw-bold text-success d-block text-center">Abgeschlossene Lektionen</span>
                                <span class="fs-4 text-center">
                                    <?php 
                                        $servername = "localhost";
                                        $username = "root";
                                        $password = "root";
                                        $dbname = "Triolingo";

                                        $conn = new mysqli($servername, $username, $password, $dbname);
                                        if ($conn->connect_error) {
                                            die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
                                        }

                                        // Einheiten aus der Datenbank abrufen
                                        $sql = "SELECT count(LID) FROM Lesson Where UID = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $_SESSION['UserID']);

                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_row();
                                            echo $row[0];
                                        } else {
                                            echo 0;
                                        }

                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-4 shadow-sm d-flex flex-column align-items-center" style="background: linear-gradient(90deg, #b2f7c1 0%, #e0eafc 100%);">
                                <i class="bi bi-book text-success fs-1 mb-2"></i>
                                <span class="fw-bold text-success d-block text-center">Gelernte Vokabeln</span>
                                <span class="fs-4 text-center">
                                    <?php 
                                        $servername = "localhost";
                                        $username = "root";
                                        $password = "root";
                                        $dbname = "Triolingo";

                                        $conn = new mysqli($servername, $username, $password, $dbname);
                                        if ($conn->connect_error) {
                                            die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
                                        }

                                        // Einheiten aus der Datenbank abrufen
                                        $sql = "SELECT count(LID) FROM Level Where UID = ? and Level > 0";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $_SESSION['UserID']);

                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_row();
                                            echo $row[0];
                                        } else {
                                            echo 0;
                                        }

                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-info-actions">
                    <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    <a href="credits.php" class="btn btn-primary ms-2"><i class="bi bi-people"></i> Credits</a>
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
<?php $conn->close(); ?>