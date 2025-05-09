<?php
    session_start();
?>


<?php
    if(!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
        // $_SESSION: enthält die Session-Variablen:
        // Diese existieren  für die Dauer der Session: 
        // Bis die Session beendet wird oder der Browser geschlossen wird. 
        $_SESSION['err']="Login: Username or password is empty";
        header("Location: error.php");
        exit();
    }
    $user = $_REQUEST['username'];
    $pass = $_REQUEST['password'];
    if (empty($user) || empty($pass)) {
        $_SESSION['err']="Login: Username or password is empty";
        header("Location: error.php");
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "triolingo";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $_SESSION['err']=$conn->connect_error;
        header("Location: error.php");
        exit(); 
    }

    $sql = "SELECT name, passwort FROM User WHERE name = ? AND passwort = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    
    if ($stmt->error) {
        $_SESSION['err']= $stmt->error;
        header("Location: error.php");
        $conn->close();
        exit(); 
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Save the username in the session
        $_SESSION['username']=$username;
        header("Location: Main.php");
    } else {
        $_SESSION['err']="Login failed";
        header("Location: Error.php");
        exit();
    }

    $conn->close();
    $stmt->close();
?>