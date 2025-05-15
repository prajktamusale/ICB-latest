<?php

// update_product.php - Script to handle AJAX requests for updating product details

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']);
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $price = isset($_POST['price']) ? floatval($_POST['price']) : null;
    $icb_price = isset($_POST['icb_price']) ? floatval($_POST['icb_price']) : null;
    $productId = isset($_POST['productId']) ? intval($_POST['productId']) : null;

    if ($price !== null && $icb_price !== null && $productId !== null) {
        $sql = "UPDATE product SET price = ?, icb_price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddi", $price, $icb_price, $productId);
        if ($stmt->execute()) {
            echo "Price updated successfully.";
        } else {
            echo "Error executing update.";
        }
    } else {
        echo "Invalid input values.";
    }
} else {
    echo "Invalid request method.";
}
?>
