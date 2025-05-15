<?php
include "../config.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false, 'message' => ''];
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

header('Content-Type: application/json');

try {
    // Check database connection
    if (!isset($mysqli) || $mysqli->connect_error) {
        throw new Exception("Database connection failed: " . ($mysqli->connect_error ?? "Connection not established"));
    }
    
    // Check if required POST variables exist
    if (!isset($_POST['ordId']) || !isset($_POST['customer_name'])) {
        throw new Exception("Required parameters missing");
    }
    
    // Sanitize and validate input
    $order_id = isset($_POST['ordId']) ? (int)$_POST['ordId'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    $customer_name = isset($_POST['customer_name']) ? trim($mysqli->real_escape_string($_POST['customer_name'])) : '';
    $whatsapp = isset($_POST['whatsapp']) ? trim($mysqli->real_escape_string($_POST['whatsapp'])) : '';
    $email = isset($_POST['email']) ? trim($mysqli->real_escape_string($_POST['email'])) : '';
    $state = isset($_POST['state']) ? trim($mysqli->real_escape_string($_POST['state'])) : '';
    $city = isset($_POST['city']) ? trim($mysqli->real_escape_string($_POST['city'])) : '';
    $address = isset($_POST['address']) ? trim($mysqli->real_escape_string($_POST['address'])) : '';
    $pin = isset($_POST['pin']) ? trim($mysqli->real_escape_string($_POST['pin'])) : '';
    
    // First get customer_id from order table
    $sql_get_customer = "SELECT customer_id FROM order_table WHERE order_id = ?";
    $stmt = $mysqli->prepare($sql_get_customer);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Order not found");
    }
    
    $row = $result->fetch_assoc();
    $customer_id = $row['customer_id'];
    $stmt->close();
    
    // Log operation
    error_log("Updating customer $customer_id for order $order_id");
    
    // Update customer table
    $sql_customer_update = "UPDATE customer SET 
                           name = ?, 
                           email = ?, 
                           address = ?, 
                           city = ?, 
                           state = ?, 
                           pin = ?, 
                           whatsapp = ? 
                           WHERE id = ?";
                           
    $stmt = $mysqli->prepare($sql_customer_update);
    $stmt->bind_param("sssssssi", 
                     $customer_name, 
                     $email, 
                     $address, 
                     $city,
                     $state, 
                     $pin, 
                     $whatsapp,
                     $customer_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update customer: " . $stmt->error);
    }
    
    // Update quantity in order table if needed
    if ($quantity > 0) {
        $sql_update_quantity = "UPDATE order_table SET quantity = ? WHERE order_id = ?";
        $stmt = $mysqli->prepare($sql_update_quantity);
        $stmt->bind_param("ii", $quantity, $order_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update quantity: " . $stmt->error);
        }
    }
    
    $response['success'] = true;
    $response['message'] = "Record updated successfully";
    
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
    error_log("Exception in updateDetailsProspectusOrder.php: " . $e->getMessage());
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($mysqli) && !$mysqli->connect_error) {
        $mysqli->close();
    }
    
    echo json_encode($response);
}
?>