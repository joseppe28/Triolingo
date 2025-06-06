<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort vergessen</title>
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
        .forgot-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
        }
        .forgot-title {
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
        .forgot-links a {
            color:rgb(0, 0, 0);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.13s;
            font-family: Arial, 'Segoe UI', 'Roboto', sans-serif;
        }
        .forgot-links a:hover {
            color:rgb(0, 0, 0);
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            .forgot-card { padding: 1.2rem 0.5rem; }
            .forgot-title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="forgot-card shadow">
            <div class="text-center mb-4">
                <span class="forgot-title">Passwort vergessen</span>
            </div>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail-Adresse</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Geben Sie Ihre E-Mail-Adresse ein" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Passwort abrufen</button>
            </form>
            <div class="forgot-links text-center mt-3">
                <a href="Login.php">Zurück zur Login-Seite</a>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Database connection
                    $conn = new mysqli('localhost', 'root', 'root', 'triolingo');

                    if ($conn->connect_error) {
                        echo '<div class="text-center text-danger mt-3">Verbindungsfehler zur Datenbank.</div>';
                    } else {
                        // Check if email exists
                        $stmt = $conn->prepare('SELECT passwort FROM user WHERE email = ?');
                        $stmt->bind_param('s', $email);
                        $stmt->execute();
                        $stmt->store_result();

                        if ($stmt->num_rows > 0) {
                            $stmt->bind_result($password);
                            $stmt->fetch();
                            echo '<div class="text-center text-success mt-3">Ihr Passwort lautet: ' . htmlspecialchars($password) . '</div>';
                        } else {
                            echo '<div class="text-center text-danger mt-3">E-Mail-Adresse nicht gefunden.</div>';
                        }

                        $stmt->close();
                        $conn->close();
                    }
                } else {
                    echo '<div class="text-center text-danger mt-3">Ungültige E-Mail-Adresse.</div>';
                }
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>