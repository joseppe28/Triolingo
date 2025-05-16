<?php
session_start();

// Beispiel: Benutzername in der Session speichern (dies sollte normalerweise beim Login gesetzt werden)
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'MaxMustermann'; // Beispiel-Benutzername
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
            <button vocab_count = 5 einheit = 1 class="btn btn-light mb-3 border" style="width: 150px;" ><a href="lesson1.php" class="text-decoration-none text-dark">Lesson 1</a></button>
            <button vocab_count = 3 einheit = 2 class="btn btn-light mb-3 border" style="width: 150px;" ><a href="lesson2.php" class="text-decoration-none text-dark">Lesson 2</a></button>
            <button class="btn btn-light mb-3 border" style="width: 150px;"><a href="lesson3.php" class="text-decoration-none text-dark">Lesson 3</a></button>
        </div>
    </div>


    <script>
        function redirectToKarteiKarten(einheit, vocab_count) {
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
                    redirectToKarteiKarten(einheit, vocabCount);
                };
                // Remove the anchor inside and set text directly on button
                const text = button.querySelector('a').textContent;
                button.textContent = text;
            });
        });
    </script>

</body>
</html>