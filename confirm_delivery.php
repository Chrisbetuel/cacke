<?php
$conn = new mysqli("localhost", "root", "", "cake_ordering_system");

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: track-order.php = $order_id");
exit();
?>
<?php $conn->close(); ?>

<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "cake_ordering_system";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle image and form data upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cake_name = $_POST['cake_name'];
    $flavor = $_POST['flavor'];
    $price = $_POST['price'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_path = $target_dir . time() . "_" . $img_name;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $stmt = $conn->prepare("INSERT INTO cake_gallery (cake_name, flavor, price, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $cake_name, $flavor, $price, $target_path);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Cake uploaded successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database insert failed."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to move uploaded file."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid image upload."]);
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Cake</title>
</head>
<body>
    <h2>Upload Cake Product</h2>
    <form action="upload_cake.php" method="post" enctype="multipart/form-data">
        Cake Name: <input type="text" name="cake_name" required><br><br>
        Flavor: <input type="text" name="flavor" required><br><br>
        Price (TSh): <input type="number" name="price" step="0.01" required><br><br>
        Image: <input type="file" name="image" accept="image/*" required><br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
