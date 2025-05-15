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
    $quantity = (int)get_post_value('qty'); // Corrected field name
    $customer_name = $mysqli->real_escape_string(get_post_value('product_cust_name')); // Corrected field name
    $email = $mysqli->real_escape_string(get_post_value('cust_email'));
    $whatsapp = $mysqli->real_escape_string(get_post_value('cust_whatasapp'));
    $address = $mysqli->real_escape_string(get_post_value('cust_address'));
    $city = $mysqli->real_escape_string(get_post_value('cust_city'));
    $state = $mysqli->real_escape_string(get_post_value('cust_state'));
    $pin = (int)get_post_value('cust_pin');
    $status = (int)get_post_value('status');

    // Validate required fields
    if (empty($product_id) || empty($product_name) || empty($quantity) || empty($customer_name) || empty($email) || empty($whatsapp) || empty($address) || empty($city) || empty($state) || empty($pin)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields properly.']);
        exit();
    }

    // Check if the email already exists
    $query = "SELECT id FROM customer WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, fetch the existing customer ID
        $row = $result->fetch_assoc();
        $customer_id = $row['id'];
    } else {
        // Insert customer into the database
        $query = "INSERT INTO customer (name, email, whatsapp, address, city, state, pin) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssi', $customer_name, $email, $whatsapp, $address, $city, $state, $pin);

        if ($stmt->execute()) {
            $customer_id = $stmt->insert_id;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add customer.']);
            exit();
        }
    }

    // Insert order into the database
    $query = "INSERT INTO order_table (product_id, customer_id, quantity) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iii', $product_id, $customer_id, $quantity);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Insert into prospectus table
        $query = "INSERT INTO prospectus (order_id, status) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ii', $order_id, $status);
        $stmt->execute();

        // If status is "Closed", insert into sold table
        if ($status == 2) {
            $query = "INSERT INTO sold (order_id, status) VALUES (?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ii', $order_id, $status);
            $stmt->execute();
        }

        echo json_encode([
            'success' => true,
            'customer_id' => $customer_id,
            'order_id' => $order_id,
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

    $stmt->close();
    $mysqli->close();
}
?>