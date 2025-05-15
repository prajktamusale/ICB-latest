<?php
include "../config.php";

$response = ['success' => false, 'message' => ''];
header('Content-Type: application/json');

try {
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = isset($_POST['status']) ? (int)$_POST['status'] : -1;

    if ($order_id <= 0 || $status < 0 || $status > 2) {
        error_log("Invalid input data: Order ID: $order_id, Status: $status");
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit();
    }

    // Update the status in the `prospectus` table
    $sql = "UPDATE prospectus SET status = ? WHERE order_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $status, $order_id);

    if ($stmt->execute()) {
        // If status is "Closed (2)", move the record to the `sold` table
        if ($status == 2) {
            $sql_move_to_sold = "INSERT INTO sold (order_id, status)
                                 SELECT o.order_id, 0, (o.quantity * p.price)
                                 FROM order_table o
                                 JOIN product p ON o.product_id = p.id
                                 WHERE o.order_id = ?";
            $stmt_move = $mysqli->prepare($sql_move_to_sold);
            $stmt_move->bind_param("i", $order_id);

            if ($stmt_move->execute()) {
                // Remove the record from the `prospectus` table
                $sql_delete_from_prospectus = "DELETE FROM prospectus WHERE order_id = ?";
                $stmt_delete = $mysqli->prepare($sql_delete_from_prospectus);
                $stmt_delete->bind_param("i", $order_id);
                $stmt_delete->execute();
                $stmt_delete->close();

                $response['success'] = true;
                $response['message'] = 'Record moved to Sold and removed from Prospectus';
            } else {
                error_log("Failed to move record to Sold: " . $stmt_move->error);
                $response['message'] = 'Failed to move record to Sold';
            }
            $stmt_move->close();
        } else {
            $response['success'] = true;
            $response['message'] = 'Status updated successfully';
        }
    } else {
        error_log("SQL Error: " . $stmt->error);
        $response['message'] = 'Failed to update status';
    }
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'An error occurred: ' . $e->getMessage();
}

echo json_encode($response);
$mysqli->close();
?>