<?php
    include "../config.php";
    if(session_status()== PHP_SESSION_NONE){
        session_start();
    }

    // file_put_contents('ajax_log.txt', date('Y-m-d H:i:s') . " - Request received from IP: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);

    $assocaiate_id = $_SESSION['id'];
    $order_id =  $mysqli->escape_string($_POST['orderId']);
    $value = $mysqli->escape_string($_POST['value']);
    // file_put_contents('ajax_log.txt', $assocaiate_id." ".$order_id." ".$value, FILE_APPEND);

    // Getting hashed value from the database.
    $getting_hash = "select order_hash from order_table where order_id = ".$order_id." and associate_id= ".$assocaiate_id.";";
    $result = mysqli_query($mysqli, $getting_hash) or die("Error to fetch order id");
    // Store in the hash_id
    $hash_id = ($result -> fetch_assoc())['order_hash'];
    
    // Format of hash message (int) order_id and (int) associate_id 
    $order_hash_message = "".(int)$order_id."".(int)$assocaiate_id."";
    
    // Verifying the details
    if(password_verify($order_hash_message, $hash_id)){
        if($value==1 || $value==0 || $value==2){
           $sql_update_status = "UPDATE prospectus SET status=".$value." where order_id = ".$order_id.";";
            $result_fetch_order = mysqli_query($mysqli, $sql_update_status) or die("Error in fecthing details");
            $_SESSION["message"] = "Status updated";
    // file_put_contents('ajax_log.txt', date('Y-m-d H:i:s') . " - Request received from IP: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
    // file_put_contents('ajax_log.txt', $sql_update_status, FILE_APPEND);
    

            // header("Location: ../salesCRM.php");
        }else{
            $_SESSION['message']="Error in request";
            // header("Location: ../Error.php");
        }
    }else{
        $_SESSION['message']= "Error in request made";
        // header("Location: ../Error.php");
    } 
    mysqli_close($mysqli);
?>