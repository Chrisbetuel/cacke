<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "cake_ordering_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get submitted form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$flavor = $_POST['flavor'];
$instructions = $_POST['instructions'];
$delivery_date = $_POST['delivery_date'];
$delivery_type = $_POST['delivery_type'];
$status = "Ordered";

// Save to database
$sql = "INSERT INTO orders (customer_name, customer_phone, customer_email, flavor, special_instructions, delivery_date, delivery_type, order_status) 
        VALUES ('$name', '$phone', '$email', '$flavor', '$instructions', '$delivery_date', '$delivery_type', '$status')";

if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id; // Get the last inserted ID
    echo "
        <h2>🎉 Order Placed Successfully!</h2>
        <p>Thank you <strong>$name</strong> for your order.</p>
        <p>Your Order ID is: <strong>$order_id</strong></p>
        <p>You can track your order here:</p>
        <a href='track-orders.php?order_id=$order_id'>Track My Order</a>
    ";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
