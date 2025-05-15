<?php
include "../config.php";

$response = ['success' => false, 'message' => ''];
header('Content-Type: application/json');

try {
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

    if ($order_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
        exit();
    }

    // Update the status in the `sold` table to 3 (Paid)
    $sql = "UPDATE sold SET status = 3 WHERE order_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Order marked as Paid';
    } else {
        $response['message'] = 'Failed to mark order as Paid';
    }
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'An error occurred: ' . $e->getMessage();
}

echo json_encode($response);
$mysqli->close();
?>