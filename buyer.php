<?php
include 'db.php';

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

if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $delete_query = "DELETE FROM task_list WHERE id = $task_id";
    if ($conn->query($delete_query)) {
        $message = "Task deleted successfully!";
    } else {
        $message = "Error deleting task.";
    }
}

if (isset($_GET['edit'])) {
    $task_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM task_list WHERE id = $task_id");
    $task = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Falcon - Buyer Page</title>
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
            <a href="#">Tasks</a>
            <a href="seller.php" class="btn-switch">Switch to Seller</a>
        </nav>
    </div>
</header>

    <section class="hero-section buyer">
        <div class="hero-text">
            <h2>Post Your Task and Find Expert Sellers</h2>
            <p>Fill out the form below to quickly post a task and connect with talented professionals who can help you get the job done.</p>
            <a href="#post-task" class="hero-button">Post Your Task</a>
        </div>
    </section>

    <section id="post-task" class="post-task-section">
        <h3>Post a New Task</h3>
        <?php if (isset($message)): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>
        <form action="buyer.php" method="POST">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" placeholder="Enter task title" value="<?= isset($task) ? $task['title'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Provide a detailed description" required><?= isset($task) ? $task['description'] : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="budget">Budget ($)</label>
                <input type="number" id="budget" name="budget" placeholder="Enter your budget" value="<?= isset($task) ? $task['budget'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter task location" value="<?= isset($task) ? $task['location'] : '' ?>" required>
            </div>
            <button type="submit" class="btn-submit"><?= isset($task) ? 'Update Task' : 'Post Task' ?></button>
        </form>
    </section>

    <section class="task-section">
        <h3>Your Posted Tasks</h3>
        <div class="task-list">
            <?php
            $result = $conn->query("SELECT * FROM task_list ORDER BY created_at DESC");
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="task-card">
                    <p><strong>Title:</strong> <?= $row['title'] ?></p>
                    <p><strong>Description:</strong> <?= $row['description'] ?></p>
                    <p><strong>Budget:</strong> $<?= $row['budget'] ?></p>
                    <p><strong>Location:</strong> <?= $row['location'] ?></p>
                    <a href="buyer.php?edit=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="buyer.php?delete=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>
<?php include 'footer.php'; ?>

</body>
</html>

<script>
    document.getElementById('hamburger-menu').addEventListener('click', function() {
    document.querySelector('.navbar').classList.toggle('active');
});
</script>
