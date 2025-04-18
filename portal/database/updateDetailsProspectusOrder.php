<?php
include "../config.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sanitize and validate input
$order_id = (int)$_POST['ordId'];
$cust_id = (int)$_POST['customer_id'];
$quantity = (int)$_POST['quantity'];
$customer_name = $mysqli->real_escape_string($_POST['customer_name']);
$whatsapp = $mysqli->real_escape_string($_POST['whatsapp']);
$email = $mysqli->real_escape_string($_POST['email']);
$state = $mysqli->real_escape_string($_POST['state']);
$city = $mysqli->real_escape_string($_POST['city']);
$address = $mysqli->real_escape_string($_POST['address']);
$pin = $mysqli->real_escape_string($_POST['pin']);

// 1. Get the hash based only on order_id
$getting_hash = "SELECT order_hash FROM order_table WHERE order_id = $order_id";
$result = mysqli_query($mysqli, $getting_hash);

if (!$result || $result->num_rows == 0) {
    die("Invalid order ID.");
}

$hash_id = ($result->fetch_assoc())['order_hash'];
$order_hash_message = "" . $order_id;

// 2. Verify hash
if (!password_verify($order_hash_message, $hash_id)) {
    die("Error in request made");
}

// 3. Get price
$sql_fetch_price = "SELECT price FROM product WHERE id = (SELECT product_id FROM order_table WHERE order_id = $order_id)";
$result_price = mysqli_query($mysqli, $sql_fetch_price);
$price = ($result_price->fetch_assoc())["price"];

// 4. Update order_table
$total_price = $quantity * $price;
$sql_order_table_update = "UPDATE order_table SET quantity = $quantity, total_price = $total_price WHERE order_id = $order_id";
mysqli_query($mysqli, $sql_order_table_update);

// 5. Update customer table
$sql_customer_update = "UPDATE customer SET name = '$customer_name', email = '$email', address = '$address', city = '$city', state = '$state', pin = '$pin', whatsapp = '$whatsapp' WHERE id = $cust_id";
mysqli_query($mysqli, $sql_customer_update);

// 6. Done
mysqli_close($mysqli);
header("Location: ../salesCRM.php");
exit;
?>
