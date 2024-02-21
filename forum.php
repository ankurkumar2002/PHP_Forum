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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f0f0f0;
        }
        .content {
            margin: 20px;
        }
        .thought {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div>
        <h3>Forum Dashboard</h3>
    </div>
    <div>
        <p>Welcome, <?php echo $user['username']; ?>!</p>
        <a href="logout.php">Logout</a>
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
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
