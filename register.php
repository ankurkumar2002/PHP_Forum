<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    
    $conn->query("INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')");

    
    header("Location: login.html");
    exit();
}

$conn->close();
?>
