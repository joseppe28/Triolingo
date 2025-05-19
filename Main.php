<?php
session_start();

// Beispiel: Benutzername in der Session speichern (dies sollte normalerweise beim Login gesetzt werden)
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo</title>
</head>
<body>
    <div class="container-fluid bg-light" style="height: 100vh; position: relative;">
        <!-- Sidebar Toggle Button -->
        <div class="position-absolute" style="top: 10px; left: 10px;">
            <button class="btn btn-light border" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                <i class="bi bi-list"></i> <!-- Bootstrap Icon -->
            </button>
        </div>

        <!-- Sidebar -->
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

        <!-- Center Buttons -->
        <?php
        // DB-Verbindung herstellen (bitte Zugangsdaten anpassen)
        $mysqli = new mysqli("localhost", "root", "root", "triolingo");
        if ($mysqli->connect_errno) {
            echo "<div class='alert alert-danger'>Fehler bei der DB-Verbindung: " . $mysqli->connect_error . "</div>";
            exit;
        }

        // UserID aus Session holen
        $userId = $_SESSION['UserID'];

        // Alle abgeschlossenen Lessons für den User holen
        $completedLessons = [];
        $res = $mysqli->prepare("SELECT BID FROM lesson WHERE UID = ?");
        $res->bind_param("i", $userId);
        $res->execute();
        $res->bind_result($lessonId);
        while ($res->fetch()) {
            $completedLessons[] = $lessonId;
        }
        $res->close();

        // Hier die Buttons definieren (einfach erweiterbar)
        $lessons = [
            ['vocab_count' => 5, 'einheit' => 1, 'lesson' => 1, 'label' => 'Lesson 1'],
            ['vocab_count' => 3, 'einheit' => 2, 'lesson' => 2, 'label' => 'Lesson 2'],
            ['vocab_count' => 3, 'einheit' => 2, 'lesson' => 3, 'label' => 'Lesson 3'],
        ];
        ?>
        
        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
            <?php foreach ($lessons as $l): 
                $isCompleted = in_array($l['lesson'], $completedLessons);
                $btnClass = $isCompleted ? 'btn-success text-white' : 'btn-light text-dark';
            ?>
                <button 
                    vocab_count="<?php echo $l['vocab_count']; ?>" 
                    einheit="<?php echo $l['einheit']; ?>" 
                    lesson="<?php echo $l['lesson']; ?>" 
                    class="btn <?php echo $btnClass; ?> mb-3 border" 
                    style="width: 150px;">
                    <label  class="text-decoration-none <?php echo $isCompleted ? 'text-white' : 'text-dark'; ?>">
                        <?php echo htmlspecialchars($l['label']); ?>
                    </label>
                </button>
            <?php endforeach; ?>
        </div>
        <?php $mysqli->close(); ?>
    </div>


    <script>
        function redirectToKarteiKarten(einheit, vocab_count, lesson) {
            // Create a form element
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'karteiKarten.php';
            form.style.display = 'none';
            
            // Create input for einheit
            const einheitInput = document.createElement('input');
            einheitInput.type = 'hidden';
            einheitInput.name = 'einheit';
            einheitInput.value = einheit;
            form.appendChild(einheitInput);
            
            // Create input for vocab_count
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
            
            // Append form to body, submit it, then remove it
            document.body.appendChild(form);
            form.submit();
        }
    </script>

    <script>
        // Update the lesson buttons to use the function
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn.btn-light.mb-3.border');
            buttons.forEach(button => {
                button.onclick = function(e) {
                    e.preventDefault();
                    const vocabCount = button.getAttribute('vocab_count');
                    const einheit = button.getAttribute('einheit');
                    const lesson = button.getAttribute('lesson');
                    redirectToKarteiKarten(einheit, vocabCount, lesson);
                };
                // Remove the anchor inside and set text directly on button
                const text = button.querySelector('a').textContent;
                button.textContent = text;
            });
        });
    </script>

</body>
</html>