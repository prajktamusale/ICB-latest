<?php
    include "../config.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $id = $_SESSION['id'];
    if($id!=null){
        $sql_get_sold_data = "select * from sold_data;";
        $get_sold_data = mysqli_query($mysqli, $sql_get_sold_data) or die ("error in fetching data");

        // echo gettype($get_sold_data);
        // while($row = mysqli_fetch_assoc($get_sold_data)){
        //     // echo gettype($row);
        //     foreach($row as $data){
        //         // echo $data;
        //     }
        //     // echo "<br>";
        //     // echo '{"product_name":"'.$row["product_name"].'" , "quantity":"'.$row["quantity"].'" , "customer_id": "'.$row["customer_id"].'" , "customer_name":"'.$row["customer_name"].'", "city":"'.$row["city"].'", "state":"'.$row["state"].'", "phone":"'.$row["phone"].'", "total_price":"'.$row["total_price"].'", "email":"'.$row["email"].'", "order_id": "'.$row["order_id"].'"}';
        // }

        include "../sql_to_json.php";
        $converter = new sqlToJson();
        $converter->sql_to_json($get_sold_data);
    }
    mysqli_close($mysqli);
?>