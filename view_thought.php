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

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $thought_result = $conn->query("SELECT posts.title, posts.content, posts.created_at, users.username 
                                    FROM posts 
                                    JOIN users ON posts.user_id = users.id 
                                    WHERE posts.post_id = '$post_id'");
    $thought = $thought_result->fetch_assoc();
} else {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = $_POST['comment'];
    $conn->query("INSERT INTO comments (user_id, post_id, comment) VALUES ('$user_id', '$post_id', '$comment')");
    header("Location: view_thought.php?post_id=$post_id");
    exit();
}

$limit = 3;
$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
$comments_result = $conn->query("SELECT comments.comment, comments.created_at, users.username 
                                FROM comments 
                                JOIN users ON comments.user_id = users.id 
                                WHERE comments.post_id = '$post_id' 
                                ORDER BY comments.created_at DESC 
                                LIMIT $limit OFFSET $offset");

$moreComments = $conn->query("SELECT COUNT(*) AS total FROM comments WHERE post_id = '$post_id'")->fetch_assoc()['total'] > $offset + $limit;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Thought</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
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

        .navbar h3, .navbar p {
            margin: 0;
            color: #ecf0f1;
        }

        .thought-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .user-info {
            font-style: italic;
            font-size: 14px;
            color: #7f8c8d;
        }

        .comment-form {
            margin-top: 20px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            resize: none;
        }

        .comment-form button {
            background-color: #3498db;
            color: #ecf0f1;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .comment-form button:hover {
            background-color: #2980b9;
        }

        .comments-container {
            margin-top: 20px;
        }

        .comment {
            border-bottom: 1px solid #ecf0f1;
            padding: 10px 0;
            animation: fadeIn 0.5s ease-in-out;
        }

        .load-more-btn {
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #3498db;
            color: #ecf0f1;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
        }

        .load-more-btn:hover {
            background-color: #2980b9;
        }

        .disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h3>View Thought</h3>
    <div>
        <p>Welcome, <?php echo $user['username']; ?>!</p>
    </div>
</div>

<div class="thought-container">
    <h2><?php echo $thought['title']; ?></h2>
    <p><?php echo $thought['content']; ?></p>
    <p class="user-info">Posted by <?php echo $user['username']; ?> on <?php echo $thought['created_at']; ?></p>

    <div class="comment-form">
        <form action="" method="post">
            <label for="comment">Add your comment:</label>
            <textarea name="comment" id="comment" rows="4" required></textarea>
            <button type="submit">Post Comment</button>
        </form>
    </div>

    <div class="comments-container">
        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div class="comment">
                <p><?php echo $comment['comment']; ?></p>
                <p class="user-info">Comment by <?php echo $comment['username']; ?> on <?php echo $comment['created_at']; ?></p>
            </div>
        <?php endwhile; ?>

        <?php if ($moreComments): ?>
            <button class="load-more-btn" onclick="loadMoreComments()">Load More Comments</button>
        <?php else: ?>
            <button class="load-more-btn disabled" disabled>No More Comments</button>
        <?php endif; ?>
    </div>
</div>

<script>
    function loadMoreComments() {
        var currentOffset = <?php echo $offset; ?>;
        window.location.href = 'view_thought.php?post_id=<?php echo $post_id; ?>&offset=' + (currentOffset + <?php echo $limit; ?>);
    }
</script>

</body>
</html>
