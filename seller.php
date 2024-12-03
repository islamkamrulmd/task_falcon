<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['bid'])) {
        $task_id = $_POST['task_id'];
        $seller_id = 1; // Replace with actual logged-in seller ID
        $amount = $_POST['bid_amount'];

        if (is_numeric($amount) && $amount > 0) {
            $bid_query = "INSERT INTO bids (task_id, seller_id, amount) VALUES ('$task_id', '$seller_id', '$amount')";
            if ($conn->query($bid_query)) {
                $message_query = "INSERT INTO messages (sender_id, receiver_id, task_id, message) VALUES ('$seller_id', (SELECT id FROM buyers WHERE id = (SELECT buyer_id FROM task_list WHERE id = '$task_id')), '$task_id', 'Seller has placed a bid of $$amount. Accept or Deny.')";
                $conn->query($message_query);
                $message = "Bid placed successfully!";
            } else {
                $message = "Error placing bid.";
            }
        } else {
            $message = "Please enter a valid bid amount.";
        }
    }

    if (isset($_POST['report_task'])) {
        $task_id = $_POST['task_id'];
        $report_reason = $_POST['report_reason'];
        $custom_message = $_POST['custom_message'];

        $full_report = $report_reason;
        if (!empty($custom_message)) {
            $full_report .= " | Additional message: " . $conn->real_escape_string($custom_message);
        }

        $report_query = "INSERT INTO reports (task_id, reason) VALUES ('$task_id', '$full_report')";
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
    <script>
        function toggleReportForm(taskId) {
            const form = document.getElementById('report-form-' + taskId);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        }
    </script>
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
                <a href="buyer.php" class="btn-switch">Switch to Buyer</a>
                <a href="notifications.php" class="btn-notification">
    <i class="fas fa-bell"></i>
</a>
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
                    <button class="btn btn-report" onclick="toggleReportForm(<?= $row['id'] ?>)">Report</button>
                    <form method="POST" id="report-form-<?= $row['id'] ?>" class="task-form report-form" style="display: none;">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <label>Reason for Reporting:</label><br>
                        <input type="radio" name="report_reason" value="Spam" required> Spam<br>
                        <input type="radio" name="report_reason" value="Offensive Content" required> Offensive Content<br>
                        <input type="radio" name="report_reason" value="Fraudulent Task" required> Fraudulent Task<br>
                        <input type="radio" name="report_reason" value="Other" required> Other<br>
                        <label>Custom Message (Optional):</label>
                        <textarea name="custom_message" placeholder="Provide additional details"></textarea>
                        <button type="submit" name="report_task" class="btn btn-report-submit">Submit Report</button>
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
