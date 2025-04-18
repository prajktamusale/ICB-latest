<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $customer_id = (int)$data['customer_id'];
    $status = (int)$data['status'];

    // Update the status in the database
    $query = "UPDATE order_table SET status = ? WHERE customer_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $status, $customer_id);

    if ($stmt->execute()) {
        // Fetch updated customer details
        $query = "SELECT c.customer_name, c.email, c.whatsapp FROM customer c WHERE c.id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        echo json_encode([
            'success' => true,
            'customer_name' => $customer['customer_name'],
            'email' => $customer['email'],
            'whatsapp' => $customer['whatsapp'],
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
    }

    $stmt->close();
    $mysqli->close();
}
?>