<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo Login</title>
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
        .login-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
        }
        .login-title {
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
        .login-links a {
            color:rgb(0, 0, 0);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.13s;
            font-family: Arial, 'Segoe UI', 'Roboto', sans-serif;
        }
        .login-links a:hover {
            color:rgb(0, 0, 0);
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            .login-card { padding: 1.2rem 0.5rem; }
            .login-title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-card shadow">
            <div class="text-center mb-4">
                <span class="login-title">Triolingo Login</span>
            </div>
            <form action="processLogin.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Benutzername</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Geben Sie Ihren Benutzernamen ein" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Passwort</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Geben Sie Ihr Passwort ein" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
            </form>
            <div class="login-links text-center mt-3">
                <a href="forgotPassword.php">Passwort vergessen?</a>
            </div>
            <div class="login-links text-center mt-2">
                <a href="register.php">Noch kein Konto? Registrieren</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>