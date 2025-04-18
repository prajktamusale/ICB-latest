<?php
    require '../config.php';

    $user = $_POST['userID'];

    $sql = "SELECT * FROM users WHERE id=$user";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $data = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo "No Data Found!";
    }
?>