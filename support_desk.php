<?php
// DB connection (adjust credentials as needed)
$host = "localhost";
$dbname = "cake_ordering_system";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Handle support ticket submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $customer_name = $_POST['customer_name'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if ($customer_name && $subject && $message) {
        $stmt = $pdo->prepare("INSERT INTO support_tickets (customer_name, subject, message) VALUES (?, ?, ?)");
        $stmt->execute([$customer_name, $subject, $message]);
        $success = "Your support ticket has been submitted successfully.";
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Support Desk</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f7f7f7; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { margin-top: 0; }
        form label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 15px; padding: 10px 25px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    <h2>Submit a Support Ticket</h2>

    <?php if (!empty($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="submit_ticket" value="1">

        <label for="customer_name">Your Name *</label>
        <input type="text" id="customer_name" name="customer_name" required>

        <label for="subject">Subject *</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Message *</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Submit Ticket</button>
    </form>
</div>

</body>
</html>
