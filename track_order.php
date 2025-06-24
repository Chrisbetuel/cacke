<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "cake_ordering_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$orderInfo = "";
$error = "";

if (isset($_POST['track'])) {
    $order_id = $_POST['order_id'];

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $order = $result->fetch_assoc();
            $orderInfo = "
                <div class='result'>
                  <strong>Order ID:</strong> {$order['order_id']}<br>
                  <strong>Cake:</strong> {$order['cake_name']}<br>
                  <strong>Customer:</strong> {$order['customer_name']}<br>
                  <strong>Price:</strong> {$order['price']} TSh<br>
                  <strong>Phone:</strong> {$order['phone']}<br>
                  <strong>Payment Method:</strong> {$order['payment_method']}<br>
                  <strong>Required Date:</strong> {$order['required_date']}<br>
                  <strong>Status:</strong> <strong style='color: green'>{$order['status']}</strong>
                </div>";
        } else {
            $error = "Order not found. Please check your Order ID.";
        }

        $stmt->close();
    } else {
        $error = "SQL Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Track Your Cake Order</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f0f0;
      padding: 40px;
    }
    .container {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input[type="number"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #2196F3;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }
    .result, .error {
      margin-top: 20px;
      background: #f9f9f9;
      padding: 15px;
      border-radius: 6px;
      border-left: 4px solid #2196F3;
    }
    .error {
      border-left: 4px solid red;
      color: red;
    }
    a {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #2196F3;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Track Your Order</h2>
  <form method="POST" action="">
    <input type="number" name="order_id" placeholder="Enter your Order ID" required>
    <button type="submit" name="track">Track Order</button>
  </form>

  <?php
    echo $orderInfo;
    if (!empty($error)) {
        echo "<div class='error'>$error</div>";
    }
  ?>

  <a href="customerr.php">← Back to Dashboard</a>
</div>

</body>
</html>
