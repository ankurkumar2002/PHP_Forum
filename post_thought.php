<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT username FROM users WHERE id = '$user_id'");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $conn->query("INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')");

    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Your Thought</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #34495e;
            color: #ecf0f1;
        }

        .navbar h3, .navbar p, .navbar a {
            margin: 0;
            color: #ecf0f1;
            text-decoration: none;
        }

        .content {
            margin: 20px;
            max-width: 800px;
            margin: auto;
        }

        .form-container {
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .form-container:hover {
            transform: scale(1.02);
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            margin: auto;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, textarea, button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: #ecf0f1;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div>
        <h3>Post Your Thought</h3>
    </div>
    <div>
        <p>Welcome, <?php echo $user['username']; ?>!</p>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="content">
    <div class="form-container">
        <h2>Share Your Thought</h2>
        <form action="post_thought.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="6" required></textarea>

            <button type="submit">Post</button>
        </form>
    </div>
</div>

</body>
</html>
