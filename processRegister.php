<?php
session_start();

if (!isset($_REQUEST['username']) || !isset($_REQUEST['email']) || !isset($_REQUEST['password'])) {
    $_SESSION['err'] = "Register: Username, email or password is empty";
    header("Location: error.php");
    exit();
}

$user = $_REQUEST['username'];
$email = $_REQUEST['email'];
$pass = $_REQUEST['password'];

if (empty($user) || empty($email) || empty($pass)) {
    $_SESSION['err'] = "Register: Username, email or password is empty";
    header("Location: error.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['err'] = "Register: Invalid email format";
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

$sql1 = "SELECT email FROM User WHERE email = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $email);
$stmt1->execute();

$result = $stmt1->get_result();
if ($result->num_rows > 0) {
    $_SESSION['err'] = "Email already exists";
    header("Location: error.php");
    exit();
}
$stmt1->close();

$sql = "INSERT INTO User (name, email, passwort) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user, $email, $pass);
$stmt->execute();

if ($stmt->error) {
    $_SESSION['err'] = $stmt->error;
    header("Location: error.php");
    $conn->close();
    exit();
}

if ($stmt->affected_rows > 0) {
    // Save the username from the request in the session
    $_SESSION['username'] = $user;
    $_SESSION['email'] = $email;
    header("Location: Main.php");
} else {
    $_SESSION['err'] = "Registration failed";
    header("Location: error.php");
    exit();
}
?>