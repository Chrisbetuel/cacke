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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle support ticket resolution
    if (isset($_POST['resolve_ticket'])) {
        $ticket_id = intval($_POST['ticket_id']);
        $update = "UPDATE support_tickets SET status='resolved' WHERE id=$ticket_id";
        $conn->query($update);
    }
    
    // Handle order status update
    if (isset($_POST['update_status'])) {
        $order_id = intval($_POST['order_id']);
        $new_status = $conn->real_escape_string($_POST['new_status']);
        $updated_at = date("Y-m-d H:i:s");
        $updateOrder = "UPDATE orders SET status='$new_status', status_updated_at='$updated_at' WHERE order_id=$order_id";
        $conn->query($updateOrder);
    }
    
    // Handle cake deletion
    if (isset($_POST['delete_cake'])) {
        $cake_id = intval($_POST['cake_id']);
        $delete = "DELETE FROM cake_gallery WHERE id=$cake_id";
        $conn->query($delete);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Cake Ordering System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --primary: #9c27b0;
      --primary-dark: #7b1fa2;
      --secondary: #ff9800;
      --accent: #e91e63;
      --light: #f5f5f5;
      --dark: #333;
      --gray: #777;
      --success: #4caf50;
      --warning: #ffc107;
      --info: #2196f3;
      --sidebar-bg: linear-gradient(180deg, #7b1fa2, #4a0072);
      --sidebar-width: 250px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f0f2f5;
      color: var(--dark);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Sidebar Styles */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--sidebar-bg);
      color: white;
      height: 100vh;
      position: fixed;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      z-index: 100;
      transition: all 0.3s ease;
    }

    .sidebar-header {
      padding: 25px 20px;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h2 {
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .sidebar-menu {
      padding: 20px 0;
    }

    .menu-item {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s;
      border-left: 4px solid transparent;
      cursor: pointer;
    }

    .menu-item i {
      width: 30px;
      font-size: 1.1rem;
      transition: all 0.3s;
    }

    .menu-item:hover, .menu-item.active {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border-left-color: var(--secondary);
    }

    .menu-item:hover i, .menu-item.active i {
      transform: scale(1.1);
      color: var(--secondary);
    }

    /* Main Content Styles */
    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 1px solid #e0e0e0;
    }

    .header h1 {
      color: var(--primary-dark);
      font-size: 1.8rem;
      display: flex;
      align-items: center;
      gap: 10px;
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

    /* Stats Cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 1.8rem;
    }

    .stat-tickets .stat-icon { background: rgba(33, 150, 243, 0.15); color: var(--info); }
    .stat-requests .stat-icon { background: rgba(156, 39, 176, 0.15); color: var(--primary); }
    .stat-bakers .stat-icon { background: rgba(255, 152, 0, 0.15); color: var(--secondary); }
    .stat-cakes .stat-icon { background: rgba(76, 175, 80, 0.15); color: var(--success); }
    .stat-orders .stat-icon { background: rgba(233, 30, 99, 0.15); color: var(--accent); }

    .stat-info h3 {
      font-size: 1.8rem;
      margin-bottom: 5px;
    }

    .stat-info p {
      color: var(--gray);
      font-size: 1rem;
    }

    /* Content Sections */
    .content-section {
      display: none;
      background: white;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .section-header h2 {
      color: var(--primary-dark);
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .action-btn {
      background: var(--primary);
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 30px;
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

    /* Table Styles */
    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-top: 15px;
    }

    th {
      background-color: var(--primary);
      color: white;
      text-align: left;
      padding: 15px;
      position: sticky;
      top: 0;
    }

    td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
    }

    tr {
      transition: background 0.2s;
    }

    tr:hover {
      background-color: rgba(156, 39, 176, 0.05);
    }

    .resolved {
      background-color: rgba(76, 175, 80, 0.1);
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      display: inline-block;
    }

    .status-pending {
      background: rgba(255, 152, 0, 0.15);
      color: #ff9800;
    }

    .status-resolved {
      background: rgba(76, 175, 80, 0.15);
      color: #4caf50;
    }

    .status-processing {
      background: rgba(33, 150, 243, 0.15);
      color: #2196f3;
    }

    .status-completed {
      background: rgba(156, 39, 176, 0.15);
      color: #9c27b0;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
      .sidebar {
        width: 70px;
      }
      .sidebar-header h2 span, .menu-item span {
        display: none;
      }
      .sidebar-header {
        padding: 20px 10px;
      }
      .main-content {
        margin-left: 70px;
      }
    }

    @media (max-width: 768px) {
      .stats-container {
        grid-template-columns: 1fr;
      }
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
      }
      .mobile-menu-btn {
        display: block;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 101;
        background: var(--primary);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
      }
    }
    
    /* Form Elements */
    .form-control {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      width: 100%;
      margin-bottom: 15px;
    }
    
    .btn {
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-danger {
      background: #f44336;
      color: white;
    }
    
    .btn-sm {
      padding: 5px 10px;
      font-size: 0.85rem;
    }
    
    .update-form {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    
    .update-select {
      flex: 1;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <!-- Mobile Menu Button -->
  <div class="mobile-menu-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>
  
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h2><i class="fas fa-crown"></i> <span>Admin Panel</span></h2>
    </div>
    <div class="sidebar-menu">
      <a class="menu-item active" onclick="showSection('dashboard')">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
      </a>
      <a class="menu-item" onclick="showSection('support')">
        <i class="fas fa-ticket-alt"></i> <span>Support Tickets</span>
      </a>
      <a class="menu-item" onclick="showSection('requests')">
        <i class="fas fa-star"></i> <span>Custom Requests</span>
      </a>
      <a class="menu-item" onclick="showSection('admins')">
        <i class="fas fa-user-shield"></i> <span>Admins</span>
      </a>
      <a class="menu-item" onclick="showSection('bakers')">
        <i class="fas fa-user-chef"></i> <span>Bakers</span>
      </a>
      <a class="menu-item" onclick="showSection('cakes')">
        <i class="fas fa-birthday-cake"></i> <span>Cakes</span>
      </a>
      <a class="menu-item" onclick="showSection('orders')">
        <i class="fas fa-shopping-cart"></i> <span>Orders</span>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <h1><i class="fas fa-birthday-cake"></i> Cake Ordering System Dashboard</h1>
      <div class="user-info">
        <div class="user-avatar">A</div>
        <div>
          <div>Admin User</div>
          <small>Last login: <?php echo date("Y-m-d H:i"); ?></small>
        </div>
      </div>
    </div>

    <!-- Dashboard Section -->
    <div id="dashboard" class="content-section" style="display: block;">
      <div class="stats-container">
        <?php
        // Get counts from database
        $tickets_count = $conn->query("SELECT COUNT(*) FROM support_tickets")->fetch_row()[0];
        $requests_count = $conn->query("SELECT COUNT(*) FROM custom_requests")->fetch_row()[0];
        $bakers_count = $conn->query("SELECT COUNT(*) FROM bakers")->fetch_row()[0];
        $cakes_count = $conn->query("SELECT COUNT(*) FROM cake_gallery")->fetch_row()[0];
        $orders_count = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
        ?>
        
        <div class="stat-card stat-tickets">
          <div class="stat-icon">
            <i class="fas fa-ticket-alt"></i>
          </div>
          <div class="stat-info">
            <h3><?php echo $tickets_count; ?></h3>
            <p>Support Tickets</p>
          </div>
        </div>
        
        <div class="stat-card stat-requests">
          <div class="stat-icon">
            <i class="fas fa-star"></i>
          </div>
          <div class="stat-info">
            <h3><?php echo $requests_count; ?></h3>
            <p>Custom Requests</p>
          </div>
        </div>
        
        <div class="stat-card stat-bakers">
          <div class="stat-icon">
            <i class="fas fa-user-chef"></i>
          </div>
          <div class="stat-info">
            <h3><?php echo $bakers_count; ?></h3>
            <p>Bakers</p>
          </div>
        </div>
        
        <div class="stat-card stat-cakes">
          <div class="stat-icon">
            <i class="fas fa-birthday-cake"></i>
          </div>
          <div class="stat-info">
            <h3><?php echo $cakes_count; ?></h3>
            <p>Cakes</p>
          </div>
        </div>
        
        <div class="stat-card stat-orders">
          <div class="stat-icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <div class="stat-info">
            <h3><?php echo $orders_count; ?></h3>
            <p>Orders</p>
          </div>
        </div>
      </div>

      <div class="section-header">
        <h2><i class="fas fa-chart-line"></i> Recent Activity</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Type</th>
              <th>Description</th>
              <th>User</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get recent activity from database
            $recent_activity = $conn->query("
              (SELECT 'Order' AS type, CONCAT('Order #', order_id, ' placed') AS description, customer_name AS user, created_at AS time FROM orders ORDER BY created_at DESC LIMIT 3)
              UNION
              (SELECT 'Ticket' AS type, CONCAT('Ticket #', id, ' created') AS description, customer_name AS user, created_at AS time FROM support_tickets ORDER BY created_at DESC LIMIT 2)
              UNION
              (SELECT 'Request' AS type, CONCAT('Custom request #', id, ' submitted') AS description, CONCAT(first_name, ' ', last_name) AS user, created_at AS time FROM custom_requests ORDER BY created_at DESC LIMIT 2)
              ORDER BY time DESC LIMIT 5
            ");
            
            while($row = $recent_activity->fetch_assoc()):
            ?>
            <tr>
              <td><span class="status-badge status-completed"><?php echo $row['type']; ?></span></td>
              <td><?php echo $row['description']; ?></td>
              <td><?php echo $row['user']; ?></td>
              <td><?php echo date("Y-m-d H:i", strtotime($row['time'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Support Tickets Section -->
    <div id="support" class="content-section">
      <div class="section-header">
        <h2><i class="fas fa-ticket-alt"></i> Support Tickets</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get support tickets from database
            $tickets = $conn->query("SELECT * FROM support_tickets ORDER BY created_at DESC");
            while($ticket = $tickets->fetch_assoc()):
            ?>
            <tr class="<?php echo $ticket['status'] == 'resolved' ? 'resolved' : ''; ?>">
              <td><?php echo $ticket['id']; ?></td>
              <td><?php echo $ticket['customer_name']; ?></td>
              <td><?php echo $ticket['subject']; ?></td>
              <td><?php echo $ticket['message']; ?></td>
              <td>
                <span class="status-badge <?php 
                  echo $ticket['status'] == 'resolved' ? 'status-resolved' : 
                       ($ticket['status'] == 'pending' ? 'status-pending' : 'status-processing'); 
                ?>">
                  <?php echo ucfirst($ticket['status']); ?>
                </span>
              </td>
              <td><?php echo date("Y-m-d H:i", strtotime($ticket['created_at'])); ?></td>
              <td>
                <?php if($ticket['status'] != 'resolved'): ?>
                <form method="POST" class="update-form">
                  <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                  <button type="submit" name="resolve_ticket" class="btn btn-primary btn-sm">
                    <i class="fas fa-check"></i> Resolve
                  </button>
                </form>
                <?php else: ?>
                <span class="status-resolved">Resolved</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Custom Cake Requests Section -->
    <div id="requests" class="content-section">
      <div class="section-header">
        <h2><i class="fas fa-star"></i> Custom Cake Requests</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Flavor</th>
              <th>Instructions</th>
              <th>Delivery Date</th>
              <th>Delivery Type</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get custom cake requests from database
            $requests = $conn->query("SELECT * FROM custom_requests ORDER BY created_at DESC");
            while($request = $requests->fetch_assoc()):
            ?>
            <tr>
              <td>#<?php echo $request['id']; ?></td>
              <td><?php echo $request['first_name']; ?></td>
              <td><?php echo $request['last_name']; ?></td>
              <td><?php echo $request['flavor']; ?></td>
              <td><?php echo $request['instructions']; ?></td>
              <td><?php echo $request['delivery_date']; ?></td>
              <td><?php echo ucfirst($request['delivery_type']); ?></td>
              <td><?php echo date("Y-m-d H:i", strtotime($request['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Admins Section -->
    <div id="admins" class="content-section">
      <div class="section-header">
        <h2><i class="fas fa-user-shield"></i> Admins</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get admins from database
            $admins = $conn->query("SELECT * FROM admins");
            while($admin = $admins->fetch_assoc()):
            ?>
            <tr>
              <td>#<?php echo $admin['id']; ?></td>
              <td><?php echo $admin['username']; ?></td>
              <td><?php echo $admin['email']; ?></td>
              <td><?php echo $admin['role']; ?></td>
              <td><?php echo date("Y-m-d", strtotime($admin['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Bakers Section -->
    <div id="bakers" class="content-section">
      <div class="section-header">
        <h2><i class="fas fa-user-chef"></i> Bakers</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Specialty</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get bakers from database
            $bakers = $conn->query("SELECT * FROM bakers");
            while($baker = $bakers->fetch_assoc()):
            ?>
            <tr>
              <td>#<?php echo $baker['id']; ?></td>
              <td><?php echo $baker['name']; ?></td>
              <td><?php echo $baker['email']; ?></td>
              <td><?php echo $baker['specialty']; ?></td>
              <td><?php echo date("Y-m-d", strtotime($baker['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Cakes Section -->
    <div id="cakes" class="content-section">
      <div class="section-header">
        <h2><i class="fas fa-birthday-cake"></i> Cakes</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Flavor</th>
              <th>Price </th>
              <th>Image</th>
              <th>Uploaded At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get cakes from database
            $cakes = $conn->query("SELECT * FROM cake_gallery ORDER BY uploaded_at DESC");
            while($cake = $cakes->fetch_assoc()):
            ?>
            <tr>
              <td><?php echo $cake['id']; ?></td>
              <td><?php echo $cake['cake_name']; ?></td>
              <td><?php echo $cake['flavor']; ?></td>
              <td><?php echo number_format($cake['price']); ?></td>
              <td>
                <?php if($cake['image_path']): ?>
                <img src="<?php echo $cake['image_path']; ?>" alt="<?php echo $cake['cake_name']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                <?php else: ?>
                No Image
                <?php endif; ?>
              </td>
              <td><?php echo date("Y-m-d", strtotime($cake['uploaded_at'])); ?></td>
              <td>
                <form method="POST" class="update-form">
                  <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                  <button type="submit" name="delete_cake" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Orders Section -->
    <div id="orders" class="content-section">
      <div class="section-header">
        <h2><i class="fas fa-shopping-cart"></i> Orders</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Cake Name</th>
              <th>Customer</th>
              <th>Price </th>
              <th>Phone</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Required Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Get orders from database
            $orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
            while($order = $orders->fetch_assoc()):
            ?>
            <tr>
              <td><?php echo $order['order_id']; ?></td>
              <td><?php echo $order['cake_name']; ?></td>
              <td><?php echo $order['customer_name']; ?></td>
              <td><?php echo number_format($order['price']); ?></td>
              <td><?php echo $order['phone']; ?></td>
              <td><?php echo ucfirst($order['payment_method']); ?></td>
              <td>
                <span class="status-badge <?php 
                  echo $order['status'] == 'completed' ? 'status-completed' : 
                       ($order['status'] == 'processing' ? 'status-processing' : 'status-pending'); 
                ?>">
                  <?php echo ucfirst($order['status']); ?>
                </span>
              </td>
              <td><?php echo $order['required_date']; ?></td>
              <td>
                <form method="POST" class="update-form">
                  <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                  <select name="new_status" class="update-select">
                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                  </select>
                  <button type="submit" name="update_status" class="btn btn-primary btn-sm">
                    <i class="fas fa-sync"></i> Update
                  </button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Function to show selected section
    function showSection(sectionId) {
      // Hide all sections
      document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
      });
      
      // Show the selected section
      document.getElementById(sectionId).style.display = 'block';
      
      // Update active menu item
      document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
      });
      
      // Find the corresponding menu item and activate it
      document.querySelector(`.menu-item[onclick="showSection('${sectionId}')"]`).classList.add('active');
    }
    
    // Function to toggle sidebar on mobile
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('active');
    }
    
    // Set dashboard as default active section
    document.addEventListener('DOMContentLoaded', function() {
      showSection('dashboard');
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>