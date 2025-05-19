<?php
session_start();

$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = "root"; // Replace with your database password
$dbname = "Triolingo"; // Replace with your database name

$UID = $_SESSION['UserID']; // Get user ID from session or default to 0

$conn = new mysqli($servername, $username, $password, $dbname);


if($conn->connect_error) {
    $_SESSION['err'] = $conn->connect_error;
    header("Location: error.php");
    exit();
}

$sql = "Update FehlerStatistik set FehlerAnzahl = FehlerAnzahl + 1 where UID = ? and VID = ?";
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
?>