<?php
include 'db.php';

$query = "
    SELECT 
        b.id AS bid_id, 
        t.title AS task_title, 
        b.amount AS bid_amount, 
        s.id AS seller_id
    FROM 
        bids b
    JOIN 
        task_list t ON b.task_id = t.id
    JOIN 
        sellers s ON b.seller_id = s.id
    ORDER BY 
        b.created_at DESC";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="navbar">
        <div class="logo">
            <h1>TaskFalcon</h1>
        </div>
        <div class="navbar-links">
            <nav>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="buyer.php" class="btn-switch">Switch to Buyer</a>
                <a href="seller.php" class="btn-switch">Switch to Seller</a>
            </nav>
        </div>
    </header>

    <section class="notifications-section">
        <h3>Notifications</h3>
        <div class="notifications-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification-card">
                    <p><strong>Task:</strong> <?= $row['task_title'] ?></p>
                    <p><strong>Bid Amount:</strong> $<?= $row['bid_amount'] ?></p>
                    <p><strong>Seller ID:</strong> <?= $row['seller_id'] ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>
</body>
</html>
