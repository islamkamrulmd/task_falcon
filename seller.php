<?php
include 'db.php';

// Handle Bidding Action
if (isset($_GET['action']) && $_GET['action'] == 'bid') {
    $taskId = $_GET['task_id'];
    $sellerId = 1; // Assuming the seller ID is 1 for simplicity. Replace with logged-in user ID.
    $query = "INSERT INTO bids (task_id, seller_id) VALUES ('$taskId', '$sellerId')";
    if ($conn->query($query)) {
        $message = "Bid placed successfully!";
    } else {
        $message = "Error placing bid.";
    }
}

// Handle Reporting Action
if (isset($_GET['action']) && $_GET['action'] == 'report') {
    $taskId = $_GET['task_id'];
    $reportDescription = $_GET['description']; // From the report form (to be passed via GET)
    $query = "INSERT INTO reports (task_id, description) VALUES ('$taskId', '$reportDescription')";
    if ($conn->query($query)) {
        $message = "Task reported successfully!";
    } else {
        $message = "Error reporting task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Falcon - Seller Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">
            <h1>TaskFalcon</h1>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="#">About</a>
            <a href="#">Tasks</a>
            <a href="buyer.php" class="btn-switch">Switch to Buyer</a>
        </nav>
    </header>

    <!-- Banner -->
    <section class="banner">
        <h2>Welcome to the Seller Dashboard</h2>
        <p>Browse tasks and place your bids or report tasks if necessary.</p>
    </section>

    <!-- Message Display -->
    <?php if (isset($message)): ?>
        <div class="alert"><?= $message ?></div>
    <?php endif; ?>

    <!-- Task Listings -->
    <section class="task-section">
        <h3>Available Tasks</h3>
        <div class="task-list">
            <?php
            $result = $conn->query("SELECT * FROM task_list");
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="task-card">
                    <p><strong>Title:</strong> <?= $row['title'] ?></p>
                    <p><strong>Description:</strong> <?= $row['description'] ?></p>
                    <p><strong>Budget:</strong> $<?= $row['budget'] ?></p>
                    <p><strong>Location:</strong> <?= $row['location'] ?></p>
                    <a href="seller.php?action=bid&task_id=<?= $row['id'] ?>" class="btn bid-btn">Place Bid</a>
                    <a href="seller.php?action=report&task_id=<?= $row['id'] ?>&description=Inappropriate task" class="btn report-btn">Report Task</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>
</body>
</html>
