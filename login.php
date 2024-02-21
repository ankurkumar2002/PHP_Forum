<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT id, password FROM users WHERE username = '$username'");
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0 && password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        header("Location: dashboard.php"); 
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

$conn->close();
?>
