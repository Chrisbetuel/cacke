<?php
// ✅ DATABASE CONNECTION
$host = "localhost";
$user = "root";
$password = "";
$database = "cake_ordering_system";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// HANDLE ORDER SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cake_id'])) {
    $cake_id = (int) $_POST['cake_id'];
    $customer_name = $conn->real_escape_string(trim($_POST['customer_name']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $required_date = $conn->real_escape_string(trim($_POST['required_date']));
    $payment_method = $conn->real_escape_string(trim($_POST['payment_method']));

    // Fetch cake_name and price from cake_gallery
    $cakeQuery = $conn->prepare("SELECT cake_name, price FROM cake_gallery WHERE id = ?");
    $cakeQuery->bind_param("i", $cake_id);
    $cakeQuery->execute();
    $cakeQuery->bind_result($cake_name, $price);
    if ($cakeQuery->fetch()) {
        $cakeQuery->close();

        $created_at = date('Y-m-d H:i:s');
        $status = 'Pending';
        $status_updated_at = $created_at;

        $insertStmt = $conn->prepare("INSERT INTO orders (cake_name, customer_name, price, phone, payment_method, created_at, required_date, status, status_updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($insertStmt) {
            $insertStmt->bind_param("ssissssss", $cake_name, $customer_name, $price, $phone, $payment_method, $created_at, $required_date, $status, $status_updated_at);
            if ($insertStmt->execute()) {
                $order_id = $conn->insert_id;
                echo "<script>alert('🎉 Order placed successfully! Your Order ID is: $order_id');</script>";
            } else {
                echo "<script>alert('❌ Order failed: " . $insertStmt->error . "');</script>";
            }
            $insertStmt->close();
        } else {
            echo "<script>alert('❌ Prepare failed: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('❌ Cake not found');</script>";
        $cakeQuery->close();
    }
}

// Fetch cakes for display
$galleryQuery = "SELECT * FROM cake_gallery ORDER BY uploaded_at DESC";
$galleryResult = $conn->query($galleryQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>🎂 Featured Cakes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #e91e63;
        }
        .cake-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            width: 250px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .cake-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }
        .cake-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .order-form {
            margin-top: 10px;
        }
        .order-form input, .order-form select {
            width: 100%;
            margin: 5px 0 10px 0;
            padding: 7px;
            box-sizing: border-box;
        }
        .order-form button {
            background-color: #e91e63;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .order-form button:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>

<h2>🍰 Featured Cake Products</h2>

<div class="cake-container">
    <?php if ($galleryResult && $galleryResult->num_rows > 0): ?>
        <?php while ($cake = $galleryResult->fetch_assoc()): ?>
            <div class="cake-card">
                <img src="<?= htmlspecialchars($cake['image_path']) ?>" alt="<?= htmlspecialchars($cake['cake_name']) ?>">
                <h3><?= htmlspecialchars($cake['cake_name']) ?></h3>
                <p>Flavor: <?= htmlspecialchars($cake['flavor']) ?></p>
                <p>Price: <?= number_format($cake['price']) ?> TSh</p>

                <form class="order-form" method="POST" action="">
                    <input type="hidden" name="cake_id" value="<?= $cake['id'] ?>">
                    <input type="text" name="customer_name" placeholder="Your Name" required>
                    <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9+ ]{7,15}">
                    <input type="date" name="required_date" required min="<?= date('Y-m-d') ?>">
                    <select name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="M-Pesa">M-Pesa</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                    <button type="submit">Add Order</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No cakes available right now.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
