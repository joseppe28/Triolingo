<?php
// filepath: c:\xampp\htdocs\Triolingo\Triolingo\karteiKartenListe.php
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
$sql = "SELECT v.VID, d.Wort AS Deutsch, e.Wort AS Englisch 
        FROM Vocab v
        JOIN Deutsch_Vocab d ON v.DID = d.DID
        JOIN Englisch_Vocab e ON v.EID = e.EID
        WHERE v.EinID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $einheit_id);
$stmt->execute();
$result = $stmt->get_result();

// Anzahl der Vokabeln zählen
$vocab_count = $result->num_rows;

// Vokabelliste für die Session erstellen
$vocabList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vocabList[] = [
            'vocab' => $row['Deutsch'],
            'translation' => $row['Englisch'],
            'level' => 1, // Default level
            'VID' => $row['VID']
        ];
    }
}

// Vokabelliste in der Session speichern
$_SESSION['vocabList'] = $vocabList;
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
        .main-content-center {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 80px;
            padding-bottom: 40px;
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
        /* Exercise Button Styles - similar to lesson-card-btn in Main.php */
        .exercise-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            width: 100%;
            max-width: 500px;
        }
        .exercise-btn {
            width: 100%;
            min-height: 70px;
            font-size: 1.3rem;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            color: #222;
            font-weight: 600;
            border: none;
            transition: transform 0.13s, box-shadow 0.13s;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 1rem;
            padding-left: 2rem;
            text-decoration: none;
        }
        .exercise-btn:hover, .exercise-btn:focus {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            color: #0d6efd;
            text-decoration: none;
        }
        .exercise-btn-karteikarten {
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
        }
        .exercise-btn-writing {
            background: linear-gradient(90deg, #f6d365 0%, #fda085 100%);
        }
        .exercise-btn-matching {
            background: linear-gradient(90deg, #84fab0 0%, #8fd3f4 100%);
        }
        .exercise-btn-talking {
            background: linear-gradient(90deg, #a1c4fd 0%, #c2e9fb 100%);
        }
        @media (max-width: 600px) {
            .flashcard-list { padding: 0 0.2rem; }
            .flashcard { flex-direction: column; align-items: center; gap: 0.5rem; padding: 0.7rem 0.4rem; }
            .flashcard-content { flex-direction: column; align-items: center; gap: 0.2rem; }
            .exercise-btn { padding-left: 1rem; font-size: 1.1rem; }
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
            <h2 class="fw-bold mb-4"
                style="letter-spacing:1px; font-family: 'Pacifico', cursive; font-size:2.5rem;">
                Übungen für Einheit <?= $einheit_id ?>
            </h2>

            <!-- Exercise Buttons -->
            <div class="exercise-buttons">
                <button onclick="redirectToExercise('karteiKarten')" class="exercise-btn exercise-btn-karteikarten">
                    <i class="bi bi-card-text me-3 fs-3"></i>
                    Karteikarten üben
                </button>
                
                <button onclick="redirectToExercise('writing')" class="exercise-btn exercise-btn-writing">
                    <i class="bi bi-pencil-fill me-3 fs-3"></i>
                    Schreib-Übung
                </button>
                
                <button onclick="redirectToExercise('matching')" class="exercise-btn exercise-btn-matching">
                    <i class="bi bi-grid-3x3-gap me-3 fs-3"></i>
                    Matching-Übung
                </button>
                
                <button onclick="redirectToExercise('talking')" class="exercise-btn exercise-btn-talking">
                    <i class="bi bi-mic-fill me-3 fs-3"></i>
                    Aussprache-Übung
                </button>
            </div>

            <!-- Vokabeln Liste -->
            <h3 class="mb-3 mt-4">Vokabelliste (<?= $vocab_count ?> Einträge)</h3>
            <div class="flashcard-list">
                <?php
                if ($result->num_rows > 0) {
                    // Reset result pointer
                    mysqli_data_seek($result, 0);
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

        // Function to redirect to exercise pages with POST data
        function redirectToExercise(type) {
            const form = document.createElement('form');
            form.method = 'POST';
            
            // Set the appropriate target page based on exercise type
            switch(type) {
                case 'karteiKarten':
                    form.action = 'karteiKarten.php';
                    break;
                case 'writing':
                    form.action = 'Writing_Lesson.php';
                    break;
                case 'matching':
                    form.action = 'Matching_Lesson.php';
                    break;
                case 'talking':
                    form.action = 'talking_lesson.php';
                    break;
                default:
                    form.action = 'karteiKarten.php';
            }
            
            form.style.display = 'none';
            
            // Add einheit parameter
            const einheitInput = document.createElement('input');
            einheitInput.type = 'hidden';
            einheitInput.name = 'einheit';
            einheitInput.value = '<?= $einheit_id ?>';
            form.appendChild(einheitInput);
            
            // Add vocab_count parameter
            const vocabCountInput = document.createElement('input');
            vocabCountInput.type = 'hidden';
            vocabCountInput.name = 'vocab_count';
            vocabCountInput.value = '<?= $vocab_count ?>';
            form.appendChild(vocabCountInput);
            
            // Add a dummy lesson ID parameter (1 for exercise practices)
            const lessonInput = document.createElement('input');
            lessonInput.type = 'hidden';
            lessonInput.name = 'lesson';
            lessonInput.value = '1';
            form.appendChild(lessonInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>