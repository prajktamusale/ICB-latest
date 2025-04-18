<?php 
    require '../config.php';
    session_start();
    
    $selectedMonth = $_POST['month'];
    $selectedYear = $_POST['year'];
    $userEmail = $_POST['userEmail'];
    

    // $sql = "SELECT * FROM empTrack WHERE year='$selectedYear' AND month='$selectedMonth' AND empName='$userName'";
    $sql = "SELECT * FROM empcredit WHERE email = '$userEmail' AND year = '$selectedYear' AND month = '$selectedMonth'";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");

    $data = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode("No Data Found!");
    }
?>