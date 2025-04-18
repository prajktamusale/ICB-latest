<?php 
    include "../config.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
      if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
      }
      if (!isset($_SESSION['type'])){
        header("Location: ../index.html");
      }
    $product_id = $mysqli->escape_string($_POST['id']);
    $product_name = $mysqli->escape_string($_POST['name']);
    $product_qty = $mysqli->escape_string($_POST['qty']);
    $product_cust_name = $mysqli->escape_string($_POST['product_cust_name']);
    $cust_email = $mysqli->escape_string($_POST['cust_email']);
    $cust_whatasapp= $mysqli->escape_string($_POST['cust_whatasapp']);
    $cust_address = $mysqli->escape_string($_POST['cust_address']);
    $cust_city = $mysqli->escape_string($_POST['cust_city']);
    $cust_state = $mysqli->escape_string($_POST['cust_state']);
    $cust_pin = $mysqli->escape_string($_POST['cust_pin']);
    $status =  $mysqli->escape_string($_POST['status']);
    $assocaiate_id = $_SESSION['id'];
    // $sql = "INSERT into customer values ('".$product_cust_name."', '".$cust_email."','".$cust_address."','".$cust_city."','".$cust_state."','".$cust_pin."','".$cust_whatasapp."');";
    // $rproduct_details_sqlesult = mysqli_query($mysqli, $sql) or die('Customer not added');
    
    // $ = "select * from product where id=".$product_id."";
    // $product_details_result = mysqli_query($mysqli, $product_details_sql) or die('Error in fetching product details');

    // $sql2 = "SELECT id from customer where email = '".$cust_email."';";
    // $customer_id = mysqli_query($mysqli, $sql2) or die('Customer data not fetched');
    // // Value format: order_id	associate_id	product_id	quantity	customer_id	total_price	
    // $sql3 = "INSERT INTO order_table(associate_id,	product_id,	quantity,	customer_id,	total_price) values (".$_SESSION["id"].", ".$product_id.", ".$product_qty.", ".$customer_id["id"].", ".$product_qty*$product_details_result["price"]." );";
    // $order_table = mysqli_query($mysqli, $sql3) or die('Customer data not fetched');
    // // $sql_ = "INSERT into order_tabel values ()";
  //   $call_addCustomer = "CALL addCustomer(
  //     '" . $cust_name . "',  -- name (assuming cust_name contains the customer name)
  //     '" . $cust_email . "', -- email (assuming cust_email contains the customer email)
  //     '" . $cust_address . "', -- address (assuming cust_address contains the customer address)
  //     '" . $cust_city . "',   -- city (assuming cust_city contains the customer city)
  //     '" . $cust_state . "',  -- state (assuming cust_state contains the customer state)
  //     '" . $cust_pin . "',    -- pin (assuming cust_pin contains the customer pin)
  //     " . $cust_whatasapp . ", -- whatsapp (assuming cust_whatsapp contains the customer WhatsApp)
  //     " . $product_id . ",    -- product_id (assuming product_id contains the product ID)
  //     '" . $product_name . "', -- product_name (assuming product_name contains the product name)
  //     " . $_SESSION['id'] . ", -- associate_id (assuming Sessionid contains the associate ID)
  //     " . $product_qty . ",    -- quantity (assuming product_qty contains the product quantity)
  //     @message               -- result (output parameter)
  // )";
  // if ($mysqli->query($call_addCustomer)) {
  //     // Fetch the result from the output parameter
  //     $result = $mysqli->query("SELECT @message")->fetch_assoc();
  //     $message = $result['@message'];
  //     echo "Result: " . $message;
  // } else {
  //     echo "Error in entering details: " . $mysqli->error;
  // }
  //   mysqli_close($mysqli);

  // Getting the id
  $sql_get_id = "SELECT id FROM product WHERE name = '".$product_name."';";  
  $result_get_id =  mysqli_query($mysqli, $sql_get_id) or die('product id not feteched');
  // $data = $result_get_id['id'];
  while($id=mysqli_fetch_array($result_get_id,MYSQLI_BOTH)){
    // $id['id']
    if($id["id"]!=$product_id){
    echo "Error";
  }else{
    $sql_associate_status = "SELECT COUNT(*) as count FROM associate WHERE id = ".$_SESSION['id'].";";
    $result_associate_status =  mysqli_query($mysqli, $sql_associate_status) or die('product id not feteched');
    
    
    if(mysqli_num_rows($result_associate_status)!=1){
      echo "No associate found";
    }else{
      // try{
        if(mysqli_num_rows(mysqli_query($mysqli ,"select * from customer where email='".$cust_email."';" ))<1){
      $sql_add_customer_data = "INSERT INTO customer(name, email, address, city, state, pin, whatsapp) VALUES ('".$product_cust_name."', '".$cust_email."', '".$cust_address."', '".$cust_city."', '".$cust_state."', '".$cust_pin."', ".$cust_whatasapp.");";
      $result_add_customer_data = mysqli_query($mysqli, $sql_add_customer_data) or die('add customer');
          
    }
      // }catch(Exception $e){
        // echo "User already exists";
      // }
      // echo $sql_add_customer_data;
      // while($add_customer_data=mysqli_fetch_array($result_add_customer_data,MYSQLI_BOTH)){
      // echo $add_customer_data['id'];

      // }
      // $add_customer_data = $result_add_customer_data -> fetch_assoc();
      $sql_get_customer_id = "SELECT id from customer where email='".$cust_email."';";
      $result_get_customer_id = mysqli_query($mysqli, $sql_get_customer_id) or die('customer id');
      $customer_id = ($result_get_customer_id -> fetch_assoc())['id'];
      // echo $customer_id['id'];
      $sql_price = "SELECT price FROM product WHERE id = ".$product_id.";";
      $result_price = mysqli_query($mysqli, $sql_price) or die('price');
      $price = ($result_price -> fetch_assoc())['price'];
      $sql_order_add = "INSERT INTO order_table(associate_id, product_id, quantity, customer_id, total_price)
             VALUES (".$_SESSION["id"].", ".$product_id.", ".$product_qty.", ".$customer_id.", ".$product_qty * $price.");";
      $result_order_table = mysqli_query($mysqli, $sql_order_add) or die('order_added');
      $sql_order_id = "SELECT order_id from order_table where associate_id=".$_SESSION["id"]." and	product_id=".$product_id." and	quantity=".$product_qty." and	customer_id=".$customer_id."	and total_price=".$product_qty * $price.";";
      
      $result_order_id = mysqli_query($mysqli, $sql_order_id) or die('prospectus error');
      $order_id = ($result_order_id -> fetch_assoc())['order_id']; 
      
      // sql_order_id, associate_id, date
      $order_hash_message = "".(int)$order_id."".(int)$assocaiate_id."";
      $order_hash = password_hash($order_hash_message, PASSWORD_BCRYPT);
      $sql_insert_hash = "UPDATE order_table SET order_hash = '".$order_hash."' where order_id = ".$order_id.";";
      $result = mysqli_query($mysqli, $sql_insert_hash) or die("ERROR in updating hash");
      // @prospectus = "INSERT into prospectus values()"
      $sql_prospectus_insert = "INSERT INTO prospectus values (".$order_id.", ".$status.");";
      $result_order_id = mysqli_query($mysqli, $sql_prospectus_insert) or die('prospectus error');

      echo "Data added successfully";
      $_SESSION["message"] = "Data added successfully";
      mysqli_close($mysqli);
      header("Location: ../salesCRM.php");
    }
  }
  }

  


?>