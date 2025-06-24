<?php
// Database credentials
$host = 'localhost';  // Change to your host
$dbname = 'cake_ordering_system';  // Change to your DB name
$username = 'root';  // Change to your DB username
$password = '';  // Change to your DB password

header('Content-Type: application/json');

try {
    // DB Connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (
        empty($data['name']) || empty($data['email']) || empty($data['phone']) ||
        empty($data['eventType']) || empty($data['guests']) ||
        empty($data['date']) || empty($data['time'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // Prepare SQL
    $stmt = $pdo->prepare("INSERT INTO garden_reservations 
        (name, email, phone, event_type, guests, reservation_date, reservation_time, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['eventType'],
        $data['guests'],
        $data['date'],
        $data['time'],
        $data['notes'] ?? null
    ]);

    echo json_encode(['success' => true, 'message' => 'Reservation saved successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
