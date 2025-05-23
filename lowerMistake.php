<?php
session_start();

$servername = "localhost"; // Replace with your server name
$username = "root"; //
$password = "root"; // Replace with your database username
$dbname = "Triolingo"; // Replace with your database name

$UID = $_SESSION['UserID']; // Get user ID from session or default to 0

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
    $_SESSION['err'] = $conn->connect_error;
    header("Location: error.php");
    exit();
}
$sql = "Select Fehleranzahl from FehlerStatistik where UID = ? and VID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $UID, $_POST['VID']);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows == 0 || $result->fetch_assoc()['FehlerAnzahl'] < 1) {
    exit();
} else {
    $sql = "Update FehlerStatistik set FehlerAnzahl = FehlerAnzahl - 1 where UID = ? and VID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $UID, $_POST['VID']);
    $stmt->execute();

    if($stmt->error) {
        $_SESSION['err'] = $stmt->error;
        header("Location: error.php");
        $conn->close();
        exit();
    }
    $stmt->close();
    $conn->close();
}