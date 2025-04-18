<?php
    include "../config.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $id = $_SESSION['id'];
    if($id!=null){
        $queryProspectus = "select product.id,product.name product_name, order_table.quantity, order_table.customer_id, customer.name customer_name, customer.whatsapp, customer.email,customer.state, customer.city, customer.address, customer.pin, prospectus.status from order_table inner join product on order_table.product_id = product.id inner join customer on customer.id = order_table.customer_id inner join prospectus on prospectus.order_id = order_table.order_id WHERE order_table.associate_id =".$id.";";
        // echo $queryProspectus;
        $resultProspectus = mysqli_query($mysqli, $queryProspectus) or die ("SQL Failed");
        $output = "{";
        while($row = mysqli_fetch_array($resultProspectus)){
            $status = $row['status']==1?'Pitching': ($row['status']==2?'Closed':'Cancelled');
            $output.='"'.$row["customer_id"].'":{"product_name":"'.$row["product_name"].'", "quantity":'.$row["quantity"].', "customer_id":'.$row["customer_id"].', "customer_name":"'.$row["customer_name"].'", "whatsapp":'.$row["whatsapp"].', "email":"'.$row["email"].'", "status":"'.$status.'"},';
        }
        $output=trim($output, ',').'}';
        echo $output;
    }
    mysqli_close($mysqli);
?>