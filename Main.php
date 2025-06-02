<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['UserID'])) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo</title>
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
        /* Stylische Lesson-Karten */
        .lesson-card-btn {
            width: 320px;
            min-height: 90px;
            font-size: 1.3rem;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            color: #222;
            font-weight: 600;
            border: none;
            margin-bottom: 18px;
            transition: transform 0.13s, box-shadow 0.13s, background 0.13s;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 1rem;
            padding-left: 2rem;
        }
        .lesson-card-btn:hover, .lesson-card-btn:focus {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            background: linear-gradient(90deg, #fed6e3 0%, #a8edea 100%);
            color: #0d6efd;
        }
        .lesson-card-btn.completed {
            background: linear-gradient(90deg, #b2f7c1 0%, #e0eafc 100%);
            color: #198754;
        }
        .lesson-card-btn.completed:hover, .lesson-card-btn.completed:focus {
            background: linear-gradient(90deg, #e0eafc 0%, #b2f7c1 100%);
            color: #198754;
        }
        .lesson-card-btn.locked {
            background: linear-gradient(90deg, #e0e0e0 0%, #c0c0c0 100%);
            color: #777;
            cursor: not-allowed;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }
        .lesson-card-btn.locked:hover, .lesson-card-btn.locked:focus {
            transform: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            background: linear-gradient(90deg, #e0e0e0 0%, #c0c0c0 100%);
        }
        .tooltip-custom {
            position: relative;
        }
        .tooltip-custom .tooltip-text {
            visibility: hidden;
            background-color: rgba(0,0,0,0.8);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 12px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            width: 200px;
            font-size: 0.9rem;
        }
        .tooltip-custom:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        @media (max-width: 600px) {
            .lesson-card-btn { width: 100%; min-height: 70px; font-size: 1.1rem; padding-left: 1rem;}
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

        <!-- Center Buttons -->
        <?php
        $mysqli = new mysqli("localhost", "root", "root", "triolingo");
        if ($mysqli->connect_errno) {
            echo "<div class='alert alert-danger'>Fehler bei der DB-Verbindung: " . $mysqli->connect_error . "</div>";
            exit;
        }

        $userId = $_SESSION['UserID'];
        $completedLessons = [];
        $res = $mysqli->prepare("SELECT BID FROM lesson WHERE UID = ?");
        $res->bind_param("i", $userId);
        $res->execute();
        $res->bind_result($lessonId);
        while ($res->fetch()) {
            $completedLessons[] = $lessonId;
        }
        $res->close();

        $lessons = [
            ['vocab_count' => 5, 'einheit' => 1, 'lesson' => 1, 'label' => 'Lesson 1'],
            ['vocab_count' => 3, 'einheit' => 2, 'lesson' => 2, 'label' => 'Lesson 2'],
            ['vocab_count' => 3, 'einheit' => 2, 'lesson' => 3, 'label' => 'Lesson 3'],
            ['vocab_count' => 4, 'einheit' => 1, 'lesson' => 4, 'label' => 'Lesson 4'],
            ['vocab_count' => 5, 'einheit' => 1, 'lesson' => 5, 'label' => 'Lesson 5'],
            ['vocab_count' => 6, 'einheit' => 1, 'lesson' => 6, 'label' => 'Lesson 6'],
            ['vocab_count' => 7, 'einheit' => 1, 'lesson' => 7, 'label' => 'Lesson 7'],
            ['vocab_count' => 8, 'einheit' => 1, 'lesson' => 8, 'label' => 'Lesson 8'],
            ['vocab_count' => 9, 'einheit' => 1, 'lesson' => 9, 'label' => 'Lesson 9'],
            ['vocab_count' => 10, 'einheit' => 1, 'lesson' => 10, 'label' => 'Lesson 10']
        ];
        ?>
        
        <div class="main-content-center">
            <div class="mb-5">
                <h2 class="fw-bold mt-2 mb-4"
                    style="letter-spacing:1px; font-family: 'Pacifico', cursive; font-size:2.5rem;">
                    Guten Tag <?= htmlspecialchars($_SESSION['username']); ?>
                </h2>
            </div>
            
            <div class="d-flex flex-column align-items-center gap-3">
                <?php 
                // First lesson should always be accessible
                $previousLessonCompleted = true;
                
                foreach ($lessons as $index => $l): 
                    $isCompleted = in_array($l['lesson'], $completedLessons);
                    $isLocked = !$previousLessonCompleted;
                    
                    // Set button class based on status
                    if ($isCompleted) {
                        $btnClass = 'lesson-card-btn completed';
                        $icon = 'bi-check-circle-fill';
                        $tooltipText = 'You have already completed this lesson';
                    } elseif ($isLocked) {
                        $btnClass = 'lesson-card-btn locked';
                        $icon = 'bi-lock-fill';
                        $tooltipText = 'Complete the previous lesson first';
                    } else {
                        $btnClass = 'lesson-card-btn';
                        $icon = 'bi-pencil-fill';
                        $tooltipText = '';
                    }
                    
                    // Update for the next iteration
                    $previousLessonCompleted = $isCompleted;
                ?>
                    <div class="position-relative tooltip-custom">
                        <button 
                            vocab_count="<?= $l['vocab_count']; ?>" 
                            einheit="<?= $l['einheit']; ?>" 
                            lesson="<?= $l['lesson']; ?>" 
                            class="btn <?= $btnClass; ?> d-flex align-items-center"
                            style="font-size:1.2rem;"
                            <?= ($isCompleted || $isLocked) ? 'disabled' : ''; ?>>
                            <i class="bi <?= $icon ?> me-3 fs-3"></i>
                            <?= htmlspecialchars($l['label']); ?>
                        </button>
                        <?php if ($isCompleted || $isLocked): ?>
                        <span class="tooltip-text"><?= $tooltipText ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php $mysqli->close(); ?>
    </div>

    <script>
        function redirectToKarteiKarten(einheit, vocab_count, lesson) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'karteiKarten.php';
            form.style.display = 'none';
            const einheitInput = document.createElement('input');
            einheitInput.type = 'hidden';
            einheitInput.name = 'einheit';
            einheitInput.value = einheit;
            form.appendChild(einheitInput);
            const vocabCountInput = document.createElement('input');
            vocabCountInput.type = 'hidden';
            vocabCountInput.name = 'vocab_count';
            vocabCountInput.value = vocab_count;
            form.appendChild(vocabCountInput);
            const lessonInput = document.createElement('input');
            lessonInput.type = 'hidden';
            lessonInput.name = 'lesson';
            lessonInput.value = lesson;
            form.appendChild(lessonInput);
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');

            sidebar.addEventListener('show.bs.offcanvas', function () {
                toggleBtn.style.display = 'none';
            });
            sidebar.addEventListener('hidden.bs.offcanvas', function () {
                toggleBtn.style.display = '';
            });

            const buttons = document.querySelectorAll('.lesson-card-btn:not(.completed):not(.locked)');
            buttons.forEach(button => {
                button.onclick = function(e) {
                    e.preventDefault();
                    const vocabCount = button.getAttribute('vocab_count');
                    const einheit = button.getAttribute('einheit');
                    const lesson = button.getAttribute('lesson');
                    redirectToKarteiKarten(einheit, vocabCount, lesson);
                };
            });
        });
    </script>
</body>
</html>