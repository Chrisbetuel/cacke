<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Garden Reservation with Gallery</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f7fafc;
    margin: 0;
    padding: 20px;
  }
  .container {
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
  }
  .gallery {
    flex: 1 1 400px;
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
    gap: 15px;
  }
  .gallery img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    object-fit: cover;
    height: 120px;
    cursor: pointer;
    transition: transform 0.3s ease;
  }
  .gallery img:hover {
    transform: scale(1.05);
  }

  .reservation-box {
    flex: 1 1 450px;
    background: white;
    padding: 30px 35px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #2d6cdf;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
  }

  input, select, textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 18px;
    border-radius: 6px;
    border: 1.8px solid #2d6cdf;
    font-size: 15px;
    box-sizing: border-box;
    transition: border-color 0.3s;
  }

  input:focus, select:focus, textarea:focus {
    border-color: #1a43b8;
    outline: none;
  }

  textarea {
    resize: vertical;
    min-height: 80px;
  }

  button {
    background-color: #2d6cdf;
    color: white;
    font-weight: 700;
    font-size: 16px;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background-color: #1a43b8;
  }

  .error {
    color: #d9534f;
    font-size: 14px;
    margin-top: -14px;
    margin-bottom: 14px;
  }

  .success {
    background: #d4edda;
    border: 1px solid #28a745;
    color: #155724;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    margin-top: 15px;
  }

  /* Responsive for smaller screens */
  @media (max-width: 900px) {
    .container {
      flex-direction: column;
      gap: 30px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <!-- Gallery Section -->
  <div class="gallery" aria-label="Gallery of available gardens/places">
    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" alt="Garden with flowers and chairs" />
    <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=400&q=80" alt="Outdoor wedding setup" />
    <img src="https://images.unsplash.com/photo-1470770903676-69b98201ea1c?auto=format&fit=crop&w=400&q=80" alt="Picnic area with tables" />
    <img src="https://images.unsplash.com/photo-1540206395-68808572332f?auto=format&fit=crop&w=400&q=80" alt="Evening garden with lights" />
    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400&q=80" alt="Green lawn with chairs" />
    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=400&q=80" alt="Wedding tent decoration" />
  </div>

  <!-- Reservation Form -->
  <div class="reservation-box">
    <h2>Reserve Your Place</h2>
    <form id="gardenForm" novalidate>
      <label for="name">Full Name *</label>
      <input type="text" id="name" name="name" required />

      <label for="email">Email Address *</label>
      <input type="email" id="email" name="email" required />

      <label for="phone">Phone Number *</label>
      <input type="tel" id="phone" name="phone" required placeholder="+255..." pattern="^\+?\d{7,15}$" />

      <label for="eventType">Event Type *</label>
      <select id="eventType" name="eventType" required>
        <option value="" disabled selected>Select event type</option>
        <option>Birthday</option>
        <option>Wedding</option>
        <option>Meeting</option>
        <option>Anniversary</option>
        <option>Other</option>
      </select>

      <label for="guests">Number of Guests *</label>
      <input type="number" id="guests" name="guests" min="1" max="1000" required />

      <label for="date">Reservation Date *</label>
      <input type="date" id="date" name="date" required min="" />

      <label for="time">Reservation Time *</label>
      <input type="time" id="time" name="time" required />

      <label for="notes">Special Requests / Notes</label>
      <textarea id="notes" name="notes" placeholder="E.g., decoration preferences, food allergies"></textarea>

      <button type="submit">Book Now</button>
    </form>

    <div id="responseMessage"></div>
  </div>
</div>

<script>
  // Set minimum date to today
  const dateInput = document.getElementById('date');
  const today = new Date().toISOString().split('T')[0];
  dateInput.setAttribute('min', today);

  const form = document.getElementById('gardenForm');
  const responseMessage = document.getElementById('responseMessage');

  form.addEventListener('submit', function(event) {
    event.preventDefault();
    responseMessage.innerHTML = '';

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const eventType = form.eventType.value;
    const guests = form.guests.value;
    const date = form.date.value;
    const time = form.time.value;

    if (!name) {
      showError('Please enter your full name.');
      return;
    }
    if (!validateEmail(email)) {
      showError('Please enter a valid email address.');
      return;
    }
    if (!validatePhone(phone)) {
      showError('Please enter a valid phone number (7-15 digits, may start with +).');
      return;
    }
    if (!eventType) {
      showError('Please select an event type.');
      return;
    }
    if (!(guests > 0)) {
      showError('Please enter a valid number of guests.');
      return;
    }
    if (!date) {
      showError('Please select a reservation date.');
      return;
    }
    if (!time) {
      showError('Please select a reservation time.');
      return;
    }

    // On success show confirmation
    responseMessage.innerHTML = `<div class="success">Thanks, ${name}! Your reservation for ${eventType} on ${date} at ${time} for ${guests} guest(s) has been received.</div>`;

    form.reset();
  });

  function showError(msg) {
    responseMessage.innerHTML = `<p class="error">${msg}</p>`;
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validatePhone(phone) {
    return /^\+?\d{7,15}$/.test(phone);
  }
</script>

</body>
</html>
