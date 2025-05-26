<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: Login.php");
    exit();
}

// DB-Verbindung
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Triolingo";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("DB-Fehler: " . $conn->connect_error);

// UID holen
$stmt = $conn->prepare("SELECT UID FROM User WHERE Email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$uid = $user['UID'] ?? null;
$stmt->close();

$fehler = [];
if ($uid) {
    $sql = "
        SELECT fs.FehlerAnzahl, d.Wort AS Deutsch, e.Wort AS Englisch
        FROM FehlerStatistik fs
        JOIN Vocab v ON fs.VID = v.VID
        JOIN Deutsch_Vocab d ON v.DID = d.DID
        JOIN Englisch_Vocab e ON v.EID = e.EID
        WHERE fs.UID = ? AND fs.FehlerAnzahl >= 3
        ORDER BY fs.FehlerAnzahl DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $fehler[] = $row;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fehlerstatistik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            padding-top: 110px;
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
        .error-list {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .error-word-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 1.2rem 1.5rem;
            transition: box-shadow 0.15s;
            border-left: 8px solid #ff7b7b;
            gap: 1.2rem;
        }
        .error-word-card:hover {
            box-shadow: 0 6px 24px rgba(220,53,69,0.13);
            border-left: 8px solid #dc3545;
        }
        .error-word-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }
        .error-word-de {
            font-size: 1.2rem;
            font-weight: 600;
            color: #dc3545;
        }
        .error-word-en {
            font-size: 1.1rem;
            color: #0d6efd;
        }
        .error-word-count {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
            min-width: 48px;
            text-align: center;
            background: #ffeaea;
            border-radius: 1rem;
            padding: 0.4rem 1rem;
            margin-left: 1rem;
            box-shadow: 0 1px 4px rgba(220,53,69,0.07);
        }
        .no-errors-card {
            background: linear-gradient(90deg, #b2f7c1 0%, #e0eafc 100%);
            border-radius: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 2rem 1rem;
            margin-top: 2rem;
            font-size: 1.2rem;
        }
        .error-icon {
            font-size: 2.2rem;
            color: #dc3545;
            vertical-align: middle;
        }
        @media (max-width: 600px) {
            .sidebar-toggle-btn { top: 12px; left: 12px; width: 44px; height: 44px; }
            .sticky-header h2 { margin-top: 18px; font-size: 1.4rem; }
            .main-content-center { padding-top: 70px; }
            .error-list { padding: 0 0.5rem; }
            .error-word-card { flex-direction: column; align-items: flex-start; gap: 0.5rem; padding: 1rem 0.7rem; }
            .error-word-count { margin-left: 0; }
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
                    <div class="fw-bold mb-1"><?= htmlspecialchars($_SESSION['username'] ?? ''); ?></div>
                    <a href="benutzerInfo.php" class="btn btn-outline-primary btn-sm w-100 mt-2">Profil anzeigen</a>
                </div>
            </div>
        </div>

        <!-- Sticky Überschrift -->
        <div class="sticky-header">
            <h2><i class="bi me-2"></i>Fehlerstatistik</h2>
        </div>

        <!-- Hauptinhalt -->
        <div class="main-content-center">
            <div style="height: 70px;"></div> <!-- Platzhalter für sticky header -->
            <?php if (count($fehler) > 0): ?>
                <div class="mb-3 text-center">
                    <span class="fw-bold text-danger"><i class="bi bi-bug-fill"></i> Wörter mit mindestens 3 Fehlerpunkten</span>
                </div>
                <div class="error-list">
                    <?php foreach ($fehler as $f): ?>
                        <div class="error-word-card">
                            <div class="error-word-info">
                                <span class="error-word-de"><?= htmlspecialchars($f['Deutsch']) ?></span>
                                <span class="error-word-en"><?= htmlspecialchars($f['Englisch']) ?></span>
                            </div>
                            <span class="error-word-count"><?= (int)$f['FehlerAnzahl'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-errors-card text-center mx-auto">
                    <i class="bi bi-emoji-laughing fs-1 text-success mb-2"></i><br>
                    <span class="fw-bold">Super! Du hast keine Wörter mit 3 oder mehr Fehlerpunkten.</span>
                </div>
            <?php endif; ?>
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