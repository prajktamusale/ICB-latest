<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Helper function to get POST data safely
    function get_post_value($key, $default = '') {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    // Sanitize and validate input
    $product_id = $mysqli->real_escape_string(get_post_value('id'));
    $product_name = $mysqli->real_escape_string(get_post_value('name'));
    $quantity = (int)get_post_value('quantity');
    $customer_name = $mysqli->real_escape_string(get_post_value('name'));
    $email = $mysqli->real_escape_string(get_post_value('email'));
    $whatsapp = $mysqli->real_escape_string(get_post_value('whatsapp'));
    $address = $mysqli->real_escape_string(get_post_value('address'));
    $city = $mysqli->real_escape_string(get_post_value('city'));
    $state = $mysqli->real_escape_string(get_post_value('state'));
    $pin = (int)get_post_value('pin');
    $status = (int)get_post_value('status');

    // Validate required fields
    if (empty($product_id) || empty($product_name) || empty($quantity) || empty($customer_name) || empty($email) || empty($whatsapp) || empty($address) || empty($city) || empty($state) || empty($pin)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields properly.']);
        exit();
    }

    // Insert customer into the database
    $query = "INSERT INTO customer (name, email, whatsapp, address, city, state, pin) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssssi', $customer_name, $email, $whatsapp, $address, $city, $state, $pin);

    if ($stmt->execute()) {
        $customer_id = $stmt->insert_id;

        // Insert order into the database
        $query = "INSERT INTO order_table (product_id, customer_id, quantity, status) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iiii', $product_id, $customer_id, $quantity, $status);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'customer_id' => $customer_id,
                'name' => $customer_name,
                'email' => $email,
                'whatsapp' => $whatsapp,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'pin' => $pin,
                'quantity' => $quantity,
                'status' => $status
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add order.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add customer.']);
    }

    $stmt->close();
    $mysqli->close();
}
?>