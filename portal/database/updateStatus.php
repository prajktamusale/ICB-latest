<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the status in the prospectus table
    $query = "UPDATE prospectus SET status = ? WHERE order_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $status, $orderId);

    if ($stmt->execute()) {
        // If status is "Closed" (2), move the record to the "Sold" table
        if ($status == 2) {
            $query = "INSERT INTO sold (order_id, product_id, customer_id, quantity, status)
                      SELECT order_id, product_id, customer_id, quantity, status
                      FROM prospectus WHERE order_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $orderId);
            $stmt->execute();

            // Remove the record from the prospectus table
            $query = "DELETE FROM prospectus WHERE order_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $orderId);
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }

    $stmt->close();
    $mysqli->close();
}
?>