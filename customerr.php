<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delicious Cakes - Customer Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
      --primary: #ff69b4;
      --primary-dark: #e55ba1;
      --secondary: #3a3a8a;
      --accent: #ffb6c1;
      --light: #fff9fb;
      --dark: #3a3a8a;
      --gray: #777;
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
    }
  </style>
</head>
<body>

  <div class="header">🎉 Welcome to the Cake Ordering System! Enjoy 15% off your first order with REBECCA: BEAUTIFUL BAKER 🎉</div>

  <div class="navbar">
    <div class="logo">
      <i class="fas fa-birthday-cake"></i>
      Sweet Delights
    </div>
    <div class="nav-links">
      <a href="customer_dashboard.php" class="active"><i class="fas fa-home"></i>order here</a>
      <a href="track_order.php"><i class="fas fa-map-marker-alt"></i> Track Order</a>
      <a href="reservation.php"><i class="fas fa-calendar-check"></i> Reserve</a>
      <a href="support_desk.php"><i class="fas fa-headset"></i> feedback</a>
    
    </div>
  </div>

  <div class="dashboard-title">
    <h1>Welcome to Your Cake Dashboard</h1>
    <p>Browse our delicious collection of handcrafted cakes and desserts, made fresh daily with love and premium ingredients</p>
  </div>

  <div class="gallery-section">
    <div class="section-header">
      <h2><i class="fas fa-utensils"></i> Our Cake Collection</h2>
      <div class="filter-controls">
        <button class="filter-btn active">our Cakes</button>
        
      </div>
    </div>
    
    <div class="product-container">
      <!-- Cake 1 -->
      <div class="product-card">
        <div class="product-image">
          <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" alt="Chocolate Fudge Cake">
          <div class="product-tag">Bestseller</div>
        </div>
        <div class="product-info">
          <h3>Chocolate Fudge Cake</h3>
          <div class="product-meta">
            <div class="product-flavor">
              <span>Flavor</span>
              <span>Rich Chocolate</span>
            </div>
          </div>
          <p>Layers of moist chocolate cake with creamy fudge frosting</p>
         
        </div>
      </div>
      
      <!-- Cake 3 -->
      <div class="product-card">
        <div class="product-image">
          <img src="https://images.unsplash.com/photo-1519869325930-281384150729?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" alt="Vanilla Dream Cake">
        </div>
        <div class="product-info">
          <h3>Vanilla Dream</h3>
          <div class="product-meta">
            <div class="product-flavor">
              <span>Flavor</span>
              <span>Pure Vanilla</span>
            </div>
            
          </div>
          <p>Light vanilla sponge with fresh berry compote filling</p>
          
            
        </div>
      </div>
      
      <!-- Cake 4 -->
      <div class="product-card">
        <div class="product-image">
          <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" alt="Lemon Drizzle Cake">
          <div class="product-tag">New</div>
        </div>
        <div class="product-info">
          <h3>Lemon Drizzle</h3>
          <div class="product-meta">
            <div class="product-flavor">
              <span>Flavor</span>
              <span>Zesty Lemon</span>
            </div>
            
          </div>
          <p>Tangy lemon cake with citrus glaze and candied lemon peel</p>
          
        </div>
      </div>
      
      <!-- Cake 5 -->
      <div class="product-card">
        <div class="product-image">
          <img src="https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" alt="Carrot Cake">
        </div>
        <div class="product-info">
          <h3>Carrot Walnut</h3>
          <div class="product-meta">
            <div class="product-flavor">
              <span>Flavor</span>
              <span>Spiced Carrot</span>
            </div>
            
          </div>
          <p>Moist carrot cake with walnuts and cream cheese frosting</p>
          
        </div>
      </div>
      
      <!-- Cake 6 -->
      <div class="product-card">
        <div class="product-image">
          <img src="https://images.unsplash.com/photo-1611293388250-580b08c4a145?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80" alt="Strawberry Shortcake">
          <div class="product-tag">Seasonal</div>
        </div>
        <div class="product-info">
          <h3>Strawberry Shortcake</h3>
          <div class="product-meta">
            <div class="product-flavor">
              <span>Flavor</span>
              <span>Fresh Strawberry</span>
            </div>
           
          </div>
          <p>Layers of vanilla cake, fresh strawberries and whipped cream</p>
          
        </div>
      </div>
    </div>
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
    <div class="copyright">© 2025 Sweet Delights Cake Ordering System. All rights reserved.</div>
  </div>

  <script>
    // Add interactivity to filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
      button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
          btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // In a real implementation, this would filter the cake gallery
        // For now, we'll just show an alert
        alert(`Filtering by: ${this.textContent}`);
      });
    });
    
    // Add interactivity to order buttons
    document.querySelectorAll('.order-btn').forEach(button => {
      button.addEventListener('click', function() {
        const cakeName = this.closest('.product-card').querySelector('h3').textContent;
        alert(`Added "${cakeName}" to your cart!`);
      });
    });
    
    // Help button functionality
    document.querySelector('.help-btn').addEventListener('click', function() {
      alert("Need help? Our support team is available 24/7 at support@sweetdelights.com or call 0800-CAKE-HELP");
    });
  </script>
</body>
</html>