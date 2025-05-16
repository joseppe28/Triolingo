<?php
session_start();

if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
    $_SESSION['err'] = "Login: Username or password is empty";
    header("Location: error.php");
    exit();
}

$user = $_REQUEST['username'];
$pass = $_REQUEST['password'];

if (empty($user) || empty($pass)) {
    $_SESSION['err'] = "Login: Username or password is empty";
    header("Location: error.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "triolingo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION['err'] = $conn->connect_error;
    header("Location: error.php");
    exit();
}

$sql = "SELECT UID, name, passwort, email FROM User WHERE name = ? AND passwort = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();

if ($stmt->error) {
    $_SESSION['err'] = $stmt->error;
    header("Location: error.php");
    $conn->close();
    exit();
}

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Fetch the row once and use it for all values
    $row = $result->fetch_assoc();
    // Save the username from the request in the session
    $_SESSION['username'] = $user;
    $_SESSION['email'] = $row['email'];
    $_SESSION['UserID'] = $row['UID'];
    if($_SESSION['UserID'] == 0) {
        $_SESSION['err'] = "User ID not set.";
        header("Location: error.php");
        exit();
    }
    header("Location: Main.php");
} else {
    $_SESSION['err'] = "Login failed";
    header("Location: error.php");
    exit();
}

$conn->close();
$stmt->close();
?>