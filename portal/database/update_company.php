<?php
header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Read the incoming JSON payload
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received or invalid JSON']);
    exit;
}

// Extract and sanitize values
$name = $data['company_name'] ?? '';
$organization = $data['organization_name'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$sector = $data['sector'] ?? '';
$pan = $data['pan'] ?? '';

// Connect to the database
$conn = new mysqli("localhost", "root", "", "icb_portal");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Update the first row (assuming single row to update)
$sql = "UPDATE company 
        SET name = ?, Organization = ?, email = ?, phone = ?, Sector = ?, PAN = ?
        ORDER BY id ASC LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssssss", $name, $organization, $email, $phone, $sector, $pan);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Company updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>