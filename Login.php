<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        <div class="card w-50 p-4 shadow"> <!-- Breite über Bootstrap-Klasse w-50 -->
            <h2 class="text-center mb-4">Login</h2>
            <form action="processLogin.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Benutzername</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Geben Sie Ihren Benutzernamen ein" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Passwort</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Geben Sie Ihr Passwort ein" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="forgotPassword.php" class="text-decoration-none">Passwort vergessen?</a>
            </div>
            <div class="text-center mt-2">
                <a href="register.php" class="text-decoration-none">Noch kein Konto? Registrieren</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>