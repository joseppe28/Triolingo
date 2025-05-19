<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: Login.php");
    exit();
}

// DB-Verbindung
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Triolingo";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("DB-Fehler: " . $conn->connect_error);

// UID holen
$stmt = $conn->prepare("SELECT UID FROM User WHERE Email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$uid = $user['UID'] ?? null;
$stmt->close();

$fehler = [];
if ($uid) {
    $sql = "
        SELECT fs.FehlerAnzahl, d.Wort AS Deutsch, e.Wort AS Englisch
        FROM FehlerStatistik fs
        JOIN Vocab v ON fs.VID = v.VID
        JOIN Deutsch_Vocab d ON v.DID = d.DID
        JOIN Englisch_Vocab e ON v.EID = e.EID
        WHERE fs.UID = ? AND fs.FehlerAnzahl >= 3
        ORDER BY fs.FehlerAnzahl DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $fehler[] = $row;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fehlerstatistik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#e3f2fd;}</style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow w-75 mx-auto">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Deine häufigsten Fehler</h2>
            <?php if (count($fehler) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Deutsch</th>
                            <th>Englisch</th>
                            <th>Fehlerpunkte</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fehler as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['Deutsch']) ?></td>
                                <td><?= htmlspecialchars($f['Englisch']) ?></td>
                                <td><?= (int)$f['FehlerAnzahl'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-success text-center">
                    Super! Du hast keine Wörter mit 3 oder mehr Fehlerpunkten.
                </div>
            <?php endif; ?>
            <div class="text-center mt-4">
                <a href="Main.php" class="btn btn-primary">Zurück zur Hauptseite</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>