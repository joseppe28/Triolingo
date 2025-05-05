<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e3f2fd; /* Heller Blauton */
        }
        h2 {
            color: #0d6efd; /* Bootstrap Primärfarbe */
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card w-50 p-4 shadow"> <!-- Breite über Bootstrap-Klasse w-75 -->
            <h2 class="text-center mb-4">Registrieren</h2>
            <form id="registerForm" action="processRegister.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Benutzername</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Geben Sie Ihren Benutzernamen ein" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail-Adresse</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Geben Sie Ihre E-Mail-Adresse ein" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Passwort</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Geben Sie Ihr Passwort ein" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Passwort bestätigen</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Passwort erneut eingeben" required>
                </div>
                <div id="passwordError" class="text-danger mb-3" style="display: none;">Die Passwörter stimmen nicht überein.</div>
                <button type="submit" class="btn btn-primary w-100">Registrieren</button>
            </form>
            <div class="text-center mt-3">
                <a href="Login.php" class="text-decoration-none">Bereits ein Konto? Login</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                event.preventDefault(); // Verhindert das Absenden des Formulars
                passwordError.style.display = 'block'; // Zeigt die Fehlermeldung an
            } else {
                passwordError.style.display = 'none'; // Versteckt die Fehlermeldung
            }
        });
    </script>
</body>
</html>