<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sweet Delights - Customer Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #ff69b4;
      --primary-dark: #e55ba1;
      --secondary: #3a3a8a;
      --accent: #ffb6c1;
      --light: #fff9fb;
      --dark: #3a3a8a;
      --gray: #777;
      --success: #4CAF50;
      --warning: #FFC107;
      --info: #2196F3;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #fff0f5, #f8f8ff);
      color: #333;
      min-height: 100vh;
      padding-bottom: 60px;
    }

    .header {
      background: linear-gradient(to right, var(--primary), var(--secondary));
      padding: 15px 0;
      text-align: center;
      color: white;
      font-size: 1.2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,100 L0,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
      background-size: cover;
    }

    .navbar {
      background-color: var(--secondary);
      padding: 15px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo i {
      color: var(--primary);
    }

    .nav-links {
      display: flex;
      gap: 15px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 15px;
      border-radius: 30px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .nav-links a:hover, .nav-links a.active {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    .dashboard-title {
      text-align: center;
      padding: 30px 20px 20px;
    }

    .dashboard-title h1 {
      font-size: 2.8rem;
      color: var(--secondary);
      margin-bottom: 15px;
      background: linear-gradient(45deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .dashboard-title p {
      color: var(--gray);
      font-size: 1.2rem;
      max-width: 700px;
      margin: 0 auto;
    }

    /* Gallery Section */
    .gallery-section {
      padding: 20px 5%;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 2px dashed var(--accent);
    }

    .section-header h2 {
      font-size: 2rem;
      color: var(--secondary);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filter-controls {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .filter-btn {
      background: white;
      border: 2px solid var(--accent);
      padding: 8px 20px;
      border-radius: 30px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
    }

    .filter-btn:hover, .filter-btn.active {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    /* Cake Gallery Grid */
    .product-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
      margin-bottom: 50px;
    }

    .product-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .product-image {
      width: 100%;
      height: 280px;
      position: relative;
      overflow: hidden;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .product-card:hover .product-image img {
      transform: scale(1.05);
    }

    .product-tag {
      position: absolute;
      top: 15px;
      right: 15px;
      background: var(--primary);
      color: white;
      padding: 5px 15px;
      border-radius: 30px;
      font-size: 0.9rem;
      font-weight: 500;
      z-index: 2;
    }

    .product-info {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .product-info h3 {
      font-size: 1.5rem;
      color: var(--secondary);
      margin-bottom: 10px;
    }

    .product-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .product-flavor, .product-price {
      display: flex;
      flex-direction: column;
    }

    .product-flavor span:first-child,
    .product-price span:first-child {
      font-size: 0.9rem;
      color: var(--gray);
    }

    .product-price span:last-child {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--primary);
    }

    .order-btn {
      background: var(--secondary);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1.1rem;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: auto;
    }

    .order-btn:hover {
      background: var(--primary);
      transform: translateY(-3px);
    }

    /* Events Section */
    .events-section {
      background: white;
      border-radius: 20px;
      padding: 30px;
      margin: 0 5% 40px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .events-title {
      color: var(--secondary);
      font-size: 1.8rem;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .events {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .event-card {
      background: linear-gradient(135deg, var(--accent), #ffd1dc);
      border-radius: 15px;
      padding: 25px 20px;
      text-align: center;
      color: var(--secondary);
      font-weight: bold;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .event-icon {
      font-size: 2.5rem;
      margin-bottom: 15px;
      color: var(--primary);
    }

    .event-name {
      font-size: 1.3rem;
      margin-bottom: 10px;
    }

    .event-date {
      font-size: 1.1rem;
      background: rgba(255, 255, 255, 0.3);
      padding: 5px 15px;
      border-radius: 20px;
      display: inline-block;
    }

    .footer {
      background: var(--secondary);
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-bottom: 15px;
    }

    .footer-links a {
      color: white;
      text-decoration: none;
      transition: all 0.3s;
    }

    .footer-links a:hover {
      color: var(--accent);
    }

    .copyright {
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .help-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: var(--primary);
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      transition: all 0.3s;
      z-index: 100;
    }

    .help-btn:hover {
      transform: scale(1.1) rotate(10deg);
      background: var(--secondary);
    }

    /* Form Styling */
    .form-container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      margin: 0 5% 40px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .form-title {
      color: var(--secondary);
      font-size: 1.8rem;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--secondary);
    }

    .form-control {
      width: 100%;
      padding: 14px;
      border: 2px solid var(--accent);
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.2);
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .submit-btn {
      background: var(--secondary);
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: block;
      width: 100%;
      margin-top: 20px;
    }

    .submit-btn:hover {
      background: var(--primary);
      transform: translateY(-3px);
    }

    /* Modal Styling */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      overflow-y: auto;
    }

    .modal-content {
      background: white;
      margin: 5% auto;
      padding: 30px;
      width: 90%;
      max-width: 600px;
      border-radius: 20px;
      position: relative;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .close {
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 28px;
      font-weight: bold;
      color: var(--gray);
      cursor: pointer;
      transition: all 0.3s;
    }

    .close:hover {
      color: var(--primary);
      transform: rotate(90deg);
    }

    .modal-title {
      color: var(--secondary);
      font-size: 1.8rem;
      margin-bottom: 25px;
      text-align: center;
    }

    /* Alert Styling */
    .alert {
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-weight: 500;
      text-align: center;
    }

    .alert-success {
      background: rgba(76, 175, 80, 0.15);
      color: var(--success);
      border: 1px solid rgba(76, 175, 80, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 900px) {
      .navbar {
        flex-direction: column;
        gap: 15px;
      }
      
      .nav-links {
        flex-wrap: wrap;
        justify-content: center;
      }
      
      .product-container {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }
    }

    @media (max-width: 600px) {
      .section-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }
      
      .filter-controls {
        flex-wrap: wrap;
      }
      
      .product-container {
        grid-template-columns: 1fr;
      }
      
      .modal-content {
        padding: 20px 15px;
      }
    }
  </style>
</head>
<body>

  <div class="header">🎉 Welcome to Sweet Delights Cake Ordering System! Enjoy 15% off your first order with code: SWEETSTART 🎉</div>

  <div class="navbar">
    <div class="logo">
      <i class="fas fa-birthday-cake"></i>
      Sweet Delights
    </div>
    <div class="nav-links">
      <a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a>
      <a href="track_order.php"><i class="fas fa-map-marker-alt"></i> Track Order</a>
      <a href="reservation.php"><i class="fas fa-calendar-check"></i> Reserve</a>
      <a href="support_desk.php"><i class="fas fa-headset"></i>feedback</a>
      
    </div>
  </div>

  <div class="dashboard-title">
    <h1>Welcome to Your Cake Dashboard</h1>
    <p>Browse our delicious collection of handcrafted cakes and desserts, made fresh daily with love and premium ingredients</p>
  </div>

  <div class="gallery-section">
    <div class="section-header">
      <h2><i class="fas fa-utensils"></i> Available Cakes</h2>
      <div class="filter-controls">
        <button class="filter-btn active">All Cakes</button>
        <button class="filter-btn">Chocolate</button>
        <button class="filter-btn">Fruit</button>
        <button class="filter-btn">Specialty</button>
        <button class="filter-btn">Seasonal</button>
      </div>
    </div>
    
    <div class="product-container" id="productList">
      <!-- Dynamic cakes from database will appear here -->
      <?php
      $host = "localhost";
      $user = "root";
      $pass = "";
      $db = "cake_ordering_system";

      $conn = new mysqli($host, $user, $pass, $db);

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $galleryQuery = "SELECT * FROM cake_gallery ORDER BY uploaded_at DESC";
      $galleryResult = $conn->query($galleryQuery);

      if ($galleryResult && $galleryResult->num_rows > 0) {
        while ($cake = $galleryResult->fetch_assoc()) {
          $cakeJson = htmlspecialchars(json_encode([
            'name' => $cake['cake_name'],
            'flavor' => $cake['flavor'],
            'price' => $cake['price'],
            'image' => $cake['image_path']
          ]));
          echo "
            <div class='product-card'>
              <div class='product-image'>
                <img src='{$cake['image_path']}' alt='{$cake['cake_name']}'>
                <div class='product-tag'>Popular</div>
              </div>
              <div class='product-info'>
                <h3>{$cake['cake_name']}</h3>
                <div class='product-meta'>
                  <div class='product-flavor'>
                    <span>Flavor</span>
                    <span>{$cake['flavor']}</span>
                  </div>
                  <div class='product-price'>
                    <span>Price</span>
                    <span>" . number_format($cake['price']) . "</span>
                  </div>
                </div>
                <button class='order-btn' onclick='openModalWithProduct($cakeJson)'>
                  <i class='fas fa-shopping-cart'></i> Order Now
                </button>
              </div>
            </div>
          ";
        }
      } else {
        echo "<p>No cakes available at the moment. Please check back later!</p>";
      }
      ?>
    </div>
  </div>

  <div class="form-container">
    <h2 class="form-title"><i class="fas fa-star"></i> Custom Cake Request</h2>
    <form id="customForm" method="POST" action="">
      <div class="form-row">
        <div class="form-group">
          <label for="c_first_name">First Name</label>
          <input type="text" class="form-control" id="c_first_name" name="c_first_name" placeholder="Your first name" required>
        </div>
        <div class="form-group">
          <label for="c_last_name">Last Name</label>
          <input type="text" class="form-control" id="c_last_name" name="c_last_name" placeholder="Your last name" required>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label for="c_flavor">Flavor</label>
          <input type="text" class="form-control" id="c_flavor" name="c_flavor" placeholder="Select flavor" required>
        </div>
        <div class="form-group">
          <label for="c_date">Delivery Date</label>
          <input type="date" class="form-control" id="c_date" name="c_date" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="c_instructions">Special Instructions</label>
        <textarea class="form-control" id="c_instructions" name="c_instructions" placeholder="Tell us about your custom cake requirements" rows="4" required></textarea>
      </div>
      
      <div class="form-group">
        <label for="c_delivery">Delivery Type</label>
        <select class="form-control" id="c_delivery" name="c_delivery" required>
          <option value="">Select delivery type</option>
          <option value="pickup">Pickup at Bakery</option>
          <option value="delivery">Home Delivery (+ ₦5,000)</option>
        </select>
      </div>
      
      <button type="submit" class="submit-btn" name="submit_custom">
        <i class="fas fa-paper-plane"></i> Send Request
      </button>
    </form>
  </div>

  <div class="events-section">
    <h2 class="events-title"><i class="fas fa-calendar-alt"></i> Upcoming Sweet Events</h2>
    <div class="events">
      <div class="event-card">
        <div class="event-icon">🎂</div>
        <div class="event-name">Cake Festival</div>
        <div class="event-date">June 10-12</div>
      </div>
      <div class="event-card">
        <div class="event-icon">👩‍🍳</div>
        <div class="event-name">Baking Workshop</div>
        <div class="event-date">June 20</div>
      </div>
      <div class="event-card">
        <div class="event-icon">🧁</div>
        <div class="event-name">Cupcake Challenge</div>
        <div class="event-date">July 5</div>
      </div>
      <div class="event-card">
        <div class="event-icon">🎉</div>
        <div class="event-name">Anniversary Celebration</div>
        <div class="event-date">July 15</div>
      </div>
    </div>
  </div>

  <!-- Order Modal -->
  <div id="orderModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 class="modal-title">Place Your Order</h2>
      <form id="orderForm" method="POST" action="">
        <div class="form-row">
          <div class="form-group">
            <label for="cake_name">Cake Name</label>
            <input type="text" class="form-control" id="cake_name" name="cake_name" readonly>
          </div>
          <div class="form-group">
            <label for="price">Price TSH</label>
            <input type="text" class="form-control" id="price" name="price" readonly>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="customer_name">Your Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Full name" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="080XXXXXXXX" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="required_date">Required Date</label>
            <input type="date" class="form-control" id="required_date" name="required_date" required>
          </div>
          <div class="form-group">
            <label for="time_required">Time Required</label>
            <input type="time" class="form-control" id="time_required" name="time_required" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="payment_method">Payment Method (50% deposit required)</label>
          <select class="form-control" id="payment_method" name="payment_method" required>
            <option value="">Select payment method</option>
            <option value="mpesa">M-Pesa</option>
            <option value="airtel">Airtel Money</option>
            <option value="tigo">Tigo Pesa</option>
            <option value="nmb">NMB Bank</option>
            <option value="crdb">CRDB Bank</option>
          </select>
        </div>
        
        <div class="alert" style="background: rgba(255, 152, 0, 0.15); color: #ff9800; border: 1px solid rgba(255, 152, 0, 0.3);">
          <i class="fas fa-exclamation-circle"></i> A 50% deposit is required to confirm your order
        </div>
        
        <button type="submit" class="submit-btn" name="submit_order">
          <i class="fas fa-check-circle"></i> Confirm Order
        </button>
      </form>
    </div>
  </div>

  <div class="help-btn">
    <i class="fas fa-question"></i>
  </div>

  <div class="footer">
    <div class="footer-links">
      <a href="#">About Us</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact</a>
      <a href="#">Careers</a>
    </div>
    <div class="copyright">© 2023 Sweet Delights Cake Ordering System. All rights reserved.</div>
  </div>

  <script>
    // Function to open modal with product details
    function openModalWithProduct(product) {
      document.getElementById('cake_name').value = product.name;
      document.getElementById('price').value = product.price;
      
      // Set minimum date to today
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('required_date').min = today;
      
      document.getElementById('orderModal').style.display = 'block';
    }

    // Function to close modal
    function closeModal() {
      document.getElementById('orderModal').style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
      const modal = document.getElementById('orderModal');
      if (event.target === modal) {
        closeModal();
      }
    }

    // Add interactivity to filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
          btn.classList.remove('active');
        });
        this.classList.add('active');
      });
    });

    // Help button functionality
    document.querySelector('.help-btn').addEventListener('click', function() {
      alert("Need help? Our support team is available 24/7 at support@sweetdelights.com or call 0800-CAKE-HELP");
    });
  </script>
  
  <?php
  // BACKEND - handle orders and custom requests
  if (isset($_POST['submit_order'])) {
    $cake_name = $_POST['cake_name'];
    $customer_name = $_POST['customer_name'];
    $price = $_POST['price'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment_method'];
    $required_date = $_POST['required_date'];
    $time_required = $_POST['time_required'];
    
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO orders (cake_name, customer_name, price, phone, payment_method, required_date, time_required) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $cake_name, $customer_name, $price, $phone, $payment, $required_date, $time_required);
    
    if ($stmt->execute()) {
      $orderId = $conn->insert_id;
      echo "<script>
        alert('Order submitted successfully!\\\\nYour Order ID is: $orderId\\\\nPlease keep this ID to track your order.');
        closeModal();
      </script>";
    } else {
      echo "<script>alert('Failed to submit order. Please try again.');</script>";
    }
    
    $stmt->close();
    $conn->close();
  }
  
  if (isset($_POST['submit_custom'])) {
    $cfname = $_POST['c_first_name'];
    $clname = $_POST['c_last_name'];
    $flavor = $_POST['c_flavor'];
    $instructions = $_POST['c_instructions'];
    $date = $_POST['c_date'];
    $delivery = $_POST['c_delivery'];
    
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO custom_requests (first_name, last_name, flavor, instructions, delivery_date, delivery_type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cfname, $clname, $flavor, $instructions, $date, $delivery);
    
    if ($stmt->execute()) {
      echo "<script>alert('Custom cake request submitted successfully! We\\'ll contact you soon to discuss details.');</script>";
    } else {
      echo "<script>alert('Failed to submit request. Please try again.');</script>";
    }
    
    $stmt->close();
    $conn->close();
  }
  ?>
</body>
</html>