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
    // Get the last inserted ID
    $lastInsertId = $conn->insert_id;
    if ($lastInsertId) {
        $_SESSION['UserID'] = $lastInsertId;
    } else {
        $_SESSION['err'] = "Failed to retrieve user ID";
    }
    header("Location: error.php");
    $conn->close();
    exit();
}

if ($stmt->affected_rows > 0) {
    // Save the username from the request in the session
    $_SESSION['username'] = $user;
    $_SESSION['email'] = $email;
    $lastInsertId = $conn->insert_id;
    if ($lastInsertId) {
        $_SESSION['UserID'] = $lastInsertId;
    } else {
        $_SESSION['err'] = "Failed to retrieve user ID";
    }
} else {
    $_SESSION['err'] = "Registration failed";
    header("Location: error.php");
    exit();
}

$sql = "Insert Into User_Stats (UID, Lessons_Completed, Words_Learned) VALUES (?, 0, 0)";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("i", $_SESSION['UserID']);
$stmt2->execute();
if ($stmt2->error) {
    $_SESSION['err'] = $stmt2->error;
    header("Location: error.php");
} else {
    $_SESSION['UserID'] = $conn->insert_id; // Save the user ID in the session
}
$stmt2->close();
$conn->close();

header("Location: Main.php");
?>