<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icb_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required parameters are present
    if (isset($_POST['field']) && isset($_POST['product_id'])) {
        $field = $conn->real_escape_string($_POST['field']);
        $value = isset($_POST['value']) ? $conn->real_escape_string($_POST['value']) : '';
        $productId = intval($_POST['product_id']);
        
        // Make sure product ID is valid
        if ($productId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
            exit;
        }
        
        // Update query
        $sql = "UPDATE product SET $field = '$value' WHERE id = $productId";
        
        if ($conn->query($sql) === TRUE) {
            // Special handling for price updates to recalculate ICB price
            if ($field == 'price' && is_numeric($value)) {
                $calculated_icb = round($value * 0.10, 2);
                $update_icb_sql = "UPDATE product SET icb_price = $calculated_icb WHERE id = $productId";
                $conn->query($update_icb_sql);
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>