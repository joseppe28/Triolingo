<?php
session_start();	
$mysqli = new mysqli("localhost", "root", "root", "triolingo");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$userID = $_SESSION['UserID'] ?? null;
$lesson = $_SESSION['lesson'] ?? null;

if ($userID && $lesson) {
    $stmt = $mysqli->prepare("SELECT 1 FROM Lesson WHERE UID = ? AND BID = ?");
    $stmt->bind_param("ii", $userID, $lesson);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        $insert = $mysqli->prepare("INSERT INTO Lesson (UID, BID) VALUES (?, ?)");
        $insert->bind_param("ii", $userID, $lesson);
        $insert->execute();
        $insert->close();
    } else {
        $stmt->close();
    }

    // --- Neue Lesson anhand niedrigstem Durchschnittslevel der Einheiten ---
    // Hole alle Einheiten und berechne deren durchschnittliches Level für diesen User
    $sql = "
        SELECT v.EinID, AVG(IFNULL(l.Level, 0)) as avg_level
        FROM vocab v
        LEFT JOIN level l ON v.VID = l.VID AND l.UID = ?
        GROUP BY v.EinID
        ORDER BY avg_level ASC
        LIMIT 1
    ";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($minEinheit, $minAvgLevel);
    $stmt->fetch();
    $stmt->close();

    // Ermittle neue Lesson-Nummer
    if (isset($_SESSION['lessons']) && is_array($_SESSION['lessons'])) {
        $lessons = $_SESSION['lessons'];
        $newLessonNum = end($lessons)['lesson'] + 1;
        $lessons[] = [
            'vocab_count' => 8,
            'einheit' => $minEinheit,
            'lesson' => $newLessonNum,
            'label' => 'Lesson ' . $newLessonNum
        ];
        $_SESSION['lessons'] = $lessons;
    }

    $mysqli->close();
    header("Location: main.php");
    exit();
}
?>