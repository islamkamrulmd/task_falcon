<?php
include 'db.php';

$user_id = 1; // Replace with dynamic user ID
$messages_query = "SELECT * FROM messages WHERE receiver_id = $user_id ORDER BY created_at DESC";
$messages_result = $conn->query($messages_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Messages</h2>
    <div class="messages-list">
        <?php while ($row = $messages_result->fetch_assoc()): ?>
            <div class="message <?= $row['status'] === 'unread' ? 'unread' : '' ?>">
                <p><strong>Task ID:</strong> <?= $row['task_id'] ?></p>
                <p><?= $row['message_text'] ?></p>
                <small>Received: <?= $row['created_at'] ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
