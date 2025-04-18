<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "icb_latest");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}

$product_name = $_POST['product_name'] ?? '';
$qty = $_POST['qty'] ?? '';
$cust_name = $_POST['product_cust_name'] ?? '';
$cust_email = $_POST['cust_email'] ?? '';
$cust_whatsapp = $_POST['cust_whatasapp'] ?? '';
$cust_address = $_POST['cust_address'] ?? '';
$cust_city = $_POST['cust_city'] ?? '';
$cust_state = $_POST['cust_state'] ?? '';
$cust_pin = $_POST['cust_pin'] ?? '';
$status = $_POST['status'] ?? '';

if (!$product_name || !$qty || !$cust_name) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

$cust_sql = "INSERT INTO customer_table (customer_name, phone, email, address, city, state, pin_code) VALUES (?, ?, ?, ?, ?, ?, ?)";
$cust_stmt = $conn->prepare($cust_sql);
$cust_stmt->bind_param("sssssss", $cust_name, $cust_whatsapp, $cust_email, $cust_address, $cust_city, $cust_state, $cust_pin);
if ($cust_stmt->execute()) {
    $customer_id = $conn->insert_id;
    $order_sql = "INSERT INTO order_table (product_name, qty, customer_id, status) VALUES (?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("siis", $product_name, $qty, $customer_id, $status);
    if ($order_stmt->execute()) {
        echo json_encode([
            "success" => true,
            "product_name" => $product_name,
            "qty" => $qty,
            "customer_id" => $customer_id,
            "customer_name" => $cust_name,
            "phone" => $cust_whatsapp,
            "email" => $cust_email,
            "status" => $status
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Order insert failed"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Customer insert failed"]);
}
$conn->close();
?>
