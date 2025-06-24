<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "cake_ordering_system";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle ticket resolution
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_ticket'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $update = "UPDATE support_tickets SET status='resolved' WHERE id=$ticket_id";
    if ($conn->query($update)) {
        echo "<script>alert('Ticket marked as resolved.'); location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to update ticket.');</script>";
    }
}

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $conn->real_escape_string($_POST['new_status']);
    $updated_at = date("Y-m-d H:i:s");

    $updateOrder = "UPDATE orders SET status='$new_status', status_updated_at='$updated_at' WHERE order_id=$order_id";
    if ($conn->query($updateOrder)) {
        echo "<script>alert('Order status updated successfully.'); location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to update order status.');</script>";
    }
}

// Handle cake upload
$uploadMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cake_name'])) {
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
                $uploadMessage = '<div class="alert success">Cake uploaded successfully!</div>';
            } else {
                $uploadMessage = '<div class="alert error">Database insert failed.</div>';
            }
            $stmt->close();
        } else {
            $uploadMessage = '<div class="alert error">Failed to move uploaded file.</div>';
        }
    } else {
        $uploadMessage = '<div class="alert error">Invalid image upload.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Baker Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #e91e63;
            --primary-dark: #c2185b;
            --secondary: #ff4081;
            --accent: #ff9800;
            --light: #f5f5f5;
            --dark: #333;
            --gray: #777;
            --light-gray: #f0f0f0;
            --success: #4CAF50;
            --warning: #FFC107;
            --danger: #F44336;
            --info: #2196F3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: var(--dark);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #e91e63, #ff4081);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .logo {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo h1 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo span {
            background: white;
            color: var(--primary);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links {
            list-style: none;
            padding: 0 15px;
        }

        .nav-links li {
            margin-bottom: 5px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-links a i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }

        .header h2 {
            color: var(--primary);
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
        }

        .orders .stat-icon { background: rgba(76, 175, 80, 0.2); color: var(--success); }
        .custom .stat-icon { background: rgba(255, 152, 0, 0.2); color: var(--accent); }
        .tickets .stat-icon { background: rgba(33, 150, 243, 0.2); color: var(--info); }
        .revenue .stat-icon { background: rgba(233, 30, 99, 0.2); color: var(--primary); }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }

        .section-header h3 {
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header h3 i {
            font-size: 1.3rem;
        }

        .action-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .action-btn:hover {
            background: var(--primary-dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        th {
            background-color: #f9f9f9;
            color: var(--gray);
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(233, 30, 99, 0.03);
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-received { background: rgba(33, 150, 243, 0.1); color: var(--info); }
        .status-processing { background: rgba(255, 152, 0, 0.1); color: var(--accent); }
        .status-completed { background: rgba(76, 175, 80, 0.1); color: var(--success); }
        .status-resolved { background: rgba(76, 175, 80, 0.1); color: var(--success); }

        .status-form {
            display: flex;
            gap: 10px;
        }

        .status-select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            min-width: 120px;
        }

        .update-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }

        .update-btn:hover {
            background: var(--primary-dark);
        }

        .resolve-btn {
            background: var(--success);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }

        .resolve-btn:hover {
            background: #388E3C;
        }

        /* Upload Form Styles */
        .upload-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .upload-form {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.2);
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload-btn {
            background: #f5f5f5;
            color: var(--dark);
            padding: 12px 15px;
            border: 1px dashed #ddd;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .file-upload-btn:hover {
            background: #eaeaea;
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .preview-container {
            margin-top: 15px;
            text-align: center;
            display: none;
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert.success {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success);
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .alert.error {
            background: rgba(244, 67, 54, 0.1);
            color: var(--danger);
            border: 1px solid rgba(244, 67, 54, 0.2);
        }

       
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1><span><i class="fas fa-birthday-cake"></i></span> <span class="logo-text">SweetCake Admin</span></h1>
            </div>
            <ul class="nav-links">
                <li><a href="#orders" class="active"><i class="fas fa-shopping-cart"></i> <span class="nav-text">Orders</span></a></li>
                <li><a href="#custom"><i class="fas fa-star"></i> <span class="nav-text">Custom Requests</span></a></li>
                <li><a href="#tickets"><i class="fas fa-ticket-alt"></i> <span class="nav-text">Support Tickets</span></a></li>
                <li><a href="#upload"><i class="fas fa-cloud-upload-alt"></i> <span class="nav-text">Upload Cake</span></a></li>
                
                <li><a href="#"><i class="fas fa-cog"></i> <span class="nav-text">Settings</span></a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2><i class="fas fa-birthday-cake"></i> Baker Dashboard</h2>
                <div class="user-info">
                    <div class="user-avatar">B</div>
                    <div>
                        <div>Baker Admin</div>
                        <small>Online</small>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card orders">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3></h3>
                        <p>New Orders</p>
                    </div>
                </div>
                <div class="stat-card custom">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3></h3>
                        <p>Custom Requests</p>
                    </div>
                </div>
                <div class="stat-card tickets">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3></h3>
                        <p>Support Tickets</p>
                    </div>
                </div>
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="fas fa--sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>TSH</h3>
                        <p>Today's Revenue</p>
                    </div>
                </div>
            </div>


            <!-- Orders Section -->
            <div class="dashboard-section" id="orders">
                <div class="section-header">
                    <h3><i class="fas fa-shopping-cart"></i> Cake Orders</h3>
                    <button class="action-btn"><i class="fas fa-plus"></i> New Order</button>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Cake Name</th>
                                <th>Customer</th>
                                <th>Price</th>
                                <th>Phone</th>
                                <th>Payment</th>
                                <th>Order Date</th>
                                <th>Required Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Custom Cake Requests -->
            <div class="dashboard-section" id="custom">
                <div class="section-header">
                    <h3><i class="fas fa-star"></i> Custom Cake Requests</h3>
                    <button class="action-btn"><i class="fas fa-filter"></i> Filter</button>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Flavor</th>
                                <th>Instructions</th>
                                <th>Date</th>
                                <th>Delivery</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                               
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cake Upload Section -->
            <div class="dashboard-section" id="upload">
                <div class="section-header">
                    <h3><i class="fas fa-cloud-upload-alt"></i> Upload New Cake Product</h3>
                </div>
                <div class="upload-container">
                    <?php echo $uploadMessage; ?>
                    <form class="upload-form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="cake_name">Cake Name</label>
                            <input type="text" class="form-control" id="cake_name" name="cake_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="flavor">Flavor</label>
                            <input type="text" class="form-control" id="flavor" name="flavor" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Cake Image</label>
                            <div class="file-upload">
                                <label class="file-upload-btn">
                                    <span id="file-name">Choose an image...</span>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </label>
                                <input type="file" class="file-upload-input" id="image" name="image" accept="image/*" required>
                            </div>
                            <div class="preview-container" id="preview-container">
                                <img src="" alt="Preview" class="preview-image" id="preview-image">
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-btn"><i class="fas fa-upload"></i> Upload Cake</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview for upload form
        const imageInput = document.getElementById('image');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');
        const fileNameDisplay = document.getElementById('file-name');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                fileNameDisplay.textContent = file.name;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                fileNameDisplay.textContent = 'Choose an image...';
            }
        });

        // Simulate form submissions
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const statusBadge = this.closest('tr').querySelector('.status-badge');
                const statusSelect = this.previousElementSibling;
                
                // Remove all status classes
                statusBadge.className = 'status-badge';
                
                // Add new status class
                if (statusSelect.value === 'Received') {
                    statusBadge.classList.add('status-received');
                } else if (statusSelect.value === 'processing') {
                    statusBadge.classList.add('status-processing');
                } else if (statusSelect.value === 'Completed') {
                    statusBadge.classList.add('status-completed');
                }
                
                // Update text
                statusBadge.textContent = statusSelect.value;
                
                // Show success message
                alert('Order status updated successfully!');
            });
        });

        // Ticket resolution
        document.querySelectorAll('.resolve-btn').forEach(button => {
            if (!button.disabled) {
                button.addEventListener('click', function() {
                    const statusBadge = this.closest('tr').querySelector('.status-badge');
                    
                    // Update to resolved
                    statusBadge.className = 'status-badge status-resolved';
                    statusBadge.textContent = 'Resolved';
                    
                    // Disable button
                    this.disabled = true;
                    this.textContent = 'Resolved';
                    
                    // Show success message
                    alert('Ticket marked as resolved!');
                });
            }
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>