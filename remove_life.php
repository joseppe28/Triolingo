<?php
// filepath: c:\xampp\htdocs\Triolingo\Triolingo\remove_life.php
session_start();

header('Content-Type: application/json');

// Check if Lives exists in session
if (!isset($_SESSION['Lives'])) {
    $_SESSION['Lives'] = 3;
}

// Reduce lives by 1
$_SESSION['Lives'] = max(0, $_SESSION['Lives'] - 1);

// Return the current lives count
echo json_encode([
    'success' => true,
    'lives' => $_SESSION['Lives']
]);
?>