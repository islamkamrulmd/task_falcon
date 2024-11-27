<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['bid'])) {
        $task_id = $_POST['task_id'];
        $seller_id = 1;
        $amount = $_POST['bid_amount'];

        if (is_numeric($amount) && $amount > 0) {
            $bid_query = "INSERT INTO bids (task_id, seller_id, amount) VALUES ('$task_id', '$seller_id', '$amount')";
            if ($conn->query($bid_query)) {
                $message = "Bid placed successfully!";
            } else {
                $message = "Error placing bid.";
            }
        } else {
            $message = "Please enter a valid bid amount.";
        }
    }

    if (isset($_POST['report'])) {
        $task_id = $_POST['task_id'];
        $report_query = "INSERT INTO reports (task_id) VALUES ('$task_id')";
        if ($conn->query($report_query)) {
            $message = "Task reported successfully!";
        } else {
            $message = "Error reporting task.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Falcon - Seller Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 100;
        }
        .navbar .logo h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .navbar .navbar-links {
            display: flex;
            align-items: center;
        }
        .navbar nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 1rem;
            position: relative;
            transition: color 0.3s;
        }
        .navbar nav a:hover {
            color: #f39c12;
        }
        .hero-section {
            padding: 3rem 2rem;
            text-align: center;
            background-color: #222;
            color: white;
        }
        .hero-button {
            background-color: #f39c12;
            color: white;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .hero-button:hover {
            background-color: #e67e22;
        }
        .task-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            padding: 2rem;
        }
        .task-card {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .task-card p {
            margin: 0.5rem 0;
        }
        .task-card form {
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .task-card input, .task-card button {
            padding: 0.5rem;
            border-radius: 4px;
        }
        .task-card .btn-bid {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
        .task-card .btn-bid:hover {
            background-color: #2980b9;
        }
        .task-card .btn-report {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }
        .task-card .btn-report:hover {
            background-color: #c0392b;
        }
    </style>
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
                <a href="buyer.php" class="btn-switch">Switch to Buyer</a>
            </nav>
        </div>
    </header>

    <section class="hero-section seller">
        <div class="hero-text">
            <h2>Find Exciting Tasks and Start Earning</h2>
            <p>Sign up today to start offering your services and completing tasks for buyers, making money while doing what you love.</p>
            <a href="#start-selling" class="hero-button">Start Selling Now</a>
        </div>
    </section>

    <section class="task-section">
        <h3>Available Tasks</h3>
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
                    <form method="POST" class="task-form bid-form">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <label for="bid_amount">Bid Amount ($):</label>
                        <input type="number" id="bid_amount" name="bid_amount" required placeholder="Enter your bid amount">
                        <button type="submit" name="bid" class="btn btn-bid">Place Bid</button>
                    </form>
                    <form method="POST" class="task-form report-form">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="report" class="btn btn-report" onclick="return confirm('Are you sure you want to report this task?');">Report</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <?php if (isset($message)): ?>
        <div class="alert"><?= $message ?></div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
