<?php
include 'db.php';

// Handling form submission (if any)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $budget = $_POST['budget'];
    $location = $_POST['location'];

    $query = "INSERT INTO task_list (title, description, budget, location) VALUES ('$title', '$description', '$budget', '$location')";
    if ($conn->query($query)) {
        $message = "Task posted successfully!";
    } else {
        $message = "Error posting task.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Falcon - Buyer Page</title>
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
            <a href="seller.php" class="btn-switch">Switch to Seller</a>
        </nav>
    </header>

    <!-- Banner -->
    <section class="banner">
        <h2>Post a New Task and Find a Seller</h2>
        <p>Fill out the form below to post a task and find skilled sellers to complete your task.</p>
    </section>

    <!-- Post Task Form Section -->
    <section class="post-task-section">
        <h3>Post a New Task</h3>
        <?php if (isset($message)): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>
        <form action="buyer.php" method="POST" class="post-task-form">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" placeholder="Enter task title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Provide a detailed description" required></textarea>
            </div>
            <div class="form-group">
                <label for="budget">Budget ($)</label>
                <input type="number" id="budget" name="budget" placeholder="Enter your budget" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter task location" required>
            </div>
            <button type="submit" class="btn-submit">Post Task</button>
        </form>
    </section>

    <!-- Task Listing Section (Existing Tasks) -->
    <section class="task-section">
        <h3>All Posted Tasks</h3>
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
                    <button class="btn bid-btn">View Task</button>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>
</body>
</html>
