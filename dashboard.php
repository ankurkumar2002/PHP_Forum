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

$thoughts_result = $conn->query("SELECT post_id, title, content, created_at FROM posts ORDER BY created_at DESC");

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
    <title>Forum Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            min-height: 100vh;
            position: relative;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #34495e;
            color: #ecf0f1;
        }

        .navbar h3, .navbar p {
            margin: 0;
        }

        .navbar a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        .navbar a.logout-btn {
            background-color: #c0392b;
            color: #ecf0f1;
            margin-left: 10px;
        }

        .navbar a.post-thought-btn {
            background-color: #2ecc71;
            color: #ecf0f1;
        }

        .navbar a:hover {
            background-color: #2c3e50;
        }

        .content {
            margin: 20px;
            padding-bottom: 60px;
            max-width: 800px;
            margin: auto;
        }

        .thought {
            border: 1px solid #ecf0f1;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .thought:hover {
            transform: scale(1.02);
        }

        .view-comment-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #3498db;
            color: #ecf0f1;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        .view-comment-btn:hover {
            background-color: #2980b9;
        }

        .form-container {
            padding: 15px;
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

        footer {
            background-color: #34495e;
            color: #ecf0f1;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .footer-content {
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div>
        <h3>Forum Dashboard</h3>
    </div>
    <div>
        <a href="post_thought.php" class="post-thought-btn">Post Your Thought</a>
    </div>
    <div>
        <p>Welcome, <?php echo $user['username']; ?>!</p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="content">
    <h2>Latest Thoughts</h2>
    <?php
    while ($thought = $thoughts_result->fetch_assoc()) {
        echo "<div class='thought'>";
        echo "<h4>{$thought['title']}</h4>";
        echo "<p>{$thought['content']}</p>";
        echo "<p>Posted by {$user['username']} on {$thought['created_at']}</p>";
        echo "<a class='view-comment-btn' href='view_thought.php?post_id={$thought['post_id']}'>View and Comment</a>";
        echo "</div>";
    }
    ?>
</div>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 Forum Website. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
