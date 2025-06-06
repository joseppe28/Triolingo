<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo Registrierung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            background-attachment: fixed;
            font-family: Arial, 'Segoe UI', 'Roboto', sans-serif;
            color: #222;
        }
        .register-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
        }
        .register-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            color: #0d6efd;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color:rgb(0, 0, 0);
            font-family: 'Arial', 'Segoe UI', 'Roboto', sans-serif;
        }
        .form-control {
            font-family: 'Arial', 'Segoe UI', 'Roboto', sans-serif;
            color: #222;
        }
        .form-control:focus {
            border-color: #a8edea;
            box-shadow: 0 0 0 2px #a8edea55;
        }
        .btn-primary {
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            color: #0d6efd;
            border: none;
            font-weight: 600;
            transition: background 0.13s, color 0.13s;
            font-family: Arial, 'Segoe UI', 'Roboto', sans-serif;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(90deg, #fed6e3 0%, #a8edea 100%);
            color: #fff;
        }
        .register-links a {
            color:rgb(0, 0, 0);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.13s;
            font-family: Arial, 'Segoe UI', 'Roboto', sans-serif;
        }
        .register-links a:hover {
            color:rgb(0, 0, 0);
            text-decoration: underline;
        }
        #passwordError {
            font-size: 0.95rem;
        }
        @media (max-width: 500px) {
            .register-card { padding: 1.2rem 0.5rem; }
            .register-title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="register-card shadow">
            <div class="text-center mb-4">
                <span class="register-title">Triolingo Registrierung</span>
            </div>
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
            <div class="register-links text-center mt-3">
                <a href="Login.php">Bereits ein Konto? Login</a>
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
                event.preventDefault();
                passwordError.style.display = 'block';
            } else {
                passwordError.style.display = 'none';
            }
        });
    </script>
</body>
</html>