<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort vergessen</title>
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
            <h2 class="text-center mb-4">Passwort vergessen</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail-Adresse</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Geben Sie Ihre E-Mail-Adresse ein" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Passwort abrufen</button>
            </form>
            <div class="text-center mt-3">
                <a href="Login.php" class="text-decoration-none">Zurück zur Login-Seite</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Database connection
        $conn = new mysqli('localhost', 'root', 'root', 'triolingo');

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

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
    } else {
        echo '<div class="text-center text-danger mt-3">Ungültige E-Mail-Adresse.</div>';
    }
}
?>