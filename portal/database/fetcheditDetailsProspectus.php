<?php
    include "../config.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Getting the hash values
    $order_id = (int)$_GET['ordId'];
    $assocaiate_id = $_SESSION['id'];

    // Getting hashed value from the database.
    $getting_hash = "select order_hash from order_table where order_id = ".$order_id." and associate_id= ".$assocaiate_id.";";
    $result = mysqli_query($mysqli, $getting_hash) or die("Error to fetch order id");
    // Store in the hash_id
    $hash_id = ($result -> fetch_assoc())['order_hash'];
    
    // Format of hash message (int) order_id and (int) associate_id 
    $order_hash_message = "".(int)$order_id."".(int)$assocaiate_id."";
    
    // Verifying the details
    if(password_verify($order_hash_message, $hash_id)){
        $sql_fetch_order = "SELECT  customer.id cust_id, order_table.order_id, product.id, product.name product_name, order_table.quantity, customer.name customer_name, customer.whatsapp, customer.email, customer.state, customer.city, customer.address, customer.pin FROM order_table inner join customer on order_table.customer_id = customer.id inner join product on order_table.product_id=product.id where order_table.order_id = ".$order_id." and order_table.associate_id = ".$assocaiate_id."; ";
        $result_fetch_order = mysqli_query($mysqli, $sql_fetch_order) or die("Error in fecthing details");
        $fetched_order = $result_fetch_order -> fetch_assoc();
        echo '{"cust_id": '.$fetched_order["cust_id"].',"order_id":"'.$order_id.'", "id":"'.$fetched_order["id"].'", "product_name": "'.$fetched_order["product_name"].'", "quantity": "'.$fetched_order["quantity"].'", "customer_name": "'.$fetched_order["customer_name"].'", "whatsapp": "'.$fetched_order["whatsapp"].'" , "email": "'.$fetched_order["email"].'", "state": "'.$fetched_order["state"].'", "city":"'.$fetched_order["city"].'", "address":"'.$fetched_order["address"].'", "pin":"'.$fetched_order["pin"].'"}';
    }else{
        echo "Error in request made";
    }
    mysqli_close($mysqli);
?>