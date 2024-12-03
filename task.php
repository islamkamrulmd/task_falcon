<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Falcon - Tasks</title>
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
                <a href="task.php">Tasks</a>

                <a href="about.php">About</a>
                <a href="seller.php" class="btn-switch">Switch to Seller</a>
                <a href="notifications.php"><i class="fas fa-bell"></i></a>
            </nav>
        </div>
    </header>

    <section class="task-section">
        <h2>All Tasks</h2>
        <?php
        // Fetch tasks categorized
        $categories = ['Electronics', 'Gadget', 'Device', 'Tools', 'Grocery', 'Home Service', 'Repairing', 'Others'];

        foreach ($categories as $category) {
            $query = "SELECT * FROM task_list WHERE category = '" . strtolower($category) . "' ORDER BY created_at DESC";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0): ?>
                <div class="category-section">
                    <h3><?= $category ?></h3>
                    <div class="task-list">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="task-card">
                                <p><strong>Title:</strong> <?= $row['title'] ?></p>
                                <p><strong>Description:</strong> <?= $row['description'] ?></p>
                                <p><strong>Budget:</strong> $<?= $row['budget'] ?></p>
                                <p><strong>Location:</strong> <?= $row['location'] ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif;
        }
        ?>
    </section>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
