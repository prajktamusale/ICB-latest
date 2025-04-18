<?php 
    require '../config.php';
    session_start();
    
    $selectedMonth = $_POST['month'];
    $selectedYear = $_POST['year'];
    $userID = $_POST['userID'];
    // $date1 = $sendData->date;
    // $time1 = $sendData->time;
    // $fulldatebit = strtotime($date1);
    // $ymd = date('Y-m-d', $fulldatebit);
    // $year = date('Y', $fulldatebit);
    // $month = date('M', $fulldatebit);
    
    // $sql = "SELECT * FROM empTrack WHERE year='$selectedYear' AND month='$selectedMonth' AND empName='$userName'";
    $sql = "SELECT empTrack.*, empDetails.basicPay, empDetails.travelAll, empDetails.otherAll, empDetails.bonus, empDetails.esic, empDetails.epfo, empDetails.pt, empDetails.otherDeduction FROM empTrack JOIN empDetails ON empTrack.empID = empDetails.id WHERE empDetails.id = '$userID' AND empTrack.empID = '$userID' AND empTrack.year = '$selectedYear' AND empTrack.month = '$selectedMonth'";
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