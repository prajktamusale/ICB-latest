<?php
    include "../config.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
    //     header("Location: ./index.php");
    //   }
    // if (!isset($_SESSION['type'])){
    //     header("Location: ../index.html");
    // }

    $id = $_SESSION['id'];
    if($id!=null){
        $queryProspectus = "select product.id as product_id,order_table.order_id, product.name product_name, order_table.quantity, order_table.customer_id, customer.name customer_name, customer.whatsapp, customer.email, prospectus.status from order_table inner join product on order_table.product_id = product.id inner join customer on customer.id = order_table.customer_id inner join prospectus on prospectus.order_id = order_table.order_id WHERE order_table.associate_id =".$id.";";
        $resultProspectus = mysqli_query($mysqli, $queryProspectus) or die ("SQL Failed");
        $output = "{";
        while($row = mysqli_fetch_array($resultProspectus)){
            // $output.push();.$row['name']." ". $row['quantity']." ".$row['customer_id']." ".$row['name']." ".$row['whatsapp']." ".$row['email'] ." " .$row['status']."";
            // $output.array_push($row);
            $status = $row['status']==1?'Pitching': ($row['status']==2?'Closed':'Cancelled');
            // echo $row['status'];
            // $output.="{'product_name':'".$row['product_name']."', 'quantity':".$row['quantity'].", 'customer_id':".$row['customer_id'].", 'customer_name':'".$row['customer_name']."', 'whatsapp':".$row['whatsapp'].", 'email':'".$row['email']."', 'status':'".$status."'},";
            $output.='"'.$row["customer_id"].'":{"product_id":"'.$row["product_id"].'","order_id":"'.$row['order_id'].'","product_name":"'.$row["product_name"].'", "quantity":'.$row["quantity"].', "customer_id":'.$row["customer_id"].', "customer_name":"'.$row["customer_name"].'", "whatsapp":'.$row["whatsapp"].', "email":"'.$row["email"].'", "status":"'.$status.'"},';
        }
        $output=trim($output, ',').'}';
        echo $output;
    }
    mysqli_close($mysqli);
?>