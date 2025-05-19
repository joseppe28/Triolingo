<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    echo "Error: No user logged in";
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Triolingo";

// Get the parameters
$germanWord = $_POST['word'] ?? '';
$isCorrect = isset($_POST['correct']) && $_POST['correct'] == 1;
$userId = $_SESSION['UserID'];

if (empty($germanWord)) {
    echo "Error: No word provided";
    exit;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    exit;
}

// First, get the vocabulary ID and current level
$sql = "SELECT v.VID, IFNULL(l.Level, 1) as CurrentLevel 
        FROM Vocab v
        JOIN Deutsch_Vocab d ON v.DID = d.DID
        LEFT JOIN Level l ON v.VID = l.VID AND l.UID = ?
        WHERE d.Wort = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $germanWord);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Word exists
    $row = $result->fetch_assoc();
    $vocabId = $row['VID'];
    $currentLevel = $row['CurrentLevel'];
    
    // Calculate new level
    if ($isCorrect) {
        $newLevel = $currentLevel + 1;
    } else {
        $newLevel = max(1, $currentLevel - 1); // Ensure level doesn't go below 1
    }
    
    // Check if a record exists for this user and vocab
    $checkSql = "SELECT LID FROM Level WHERE UID = ? AND VID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $userId, $vocabId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        // Update existing record
        $updateSql = "UPDATE Level SET Level = ? WHERE UID = ? AND VID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iii", $newLevel, $userId, $vocabId);
        $updateStmt->execute();
        echo "Level updated to $newLevel";
    } else {
        // Insert new record
        $insertSql = "INSERT INTO Level (UID, VID, Level) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iii", $userId, $vocabId, $newLevel);
        $insertStmt->execute();
        echo "Level record created with level $newLevel";
    }
} else {
    echo "Error: Word not found in database";
}

$conn->close();
?>