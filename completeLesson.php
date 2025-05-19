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
    $mysqli->close();
    header("Location: main.php");
    exit();
}


?>