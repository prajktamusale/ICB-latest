<?php 
    require '../config.php';
    session_start();
    error_reporting(0);
    $username = $_SESSION['full_name'];
    $userID = $_SESSION['id'];
    $userTeam = $_SESSION['userTeam'];
    $sendData = json_decode($_POST['dataSend']);
    $date1 = $sendData->date;
    $time = $sendData->time;
    $type = $sendData->type;
    $fulldatebit = strtotime($date1);
    $ymd = date('Y-m-d', $fulldatebit);
    $year = date('Y', $fulldatebit);
    $month = date('M', $fulldatebit);
    
    $time1 = new DateTime($time);
    // $time2 = strtotime("10:07:00");
    // $officeAssemblyLateTimeLimit = new DateTime("10:35:00");
    // $officeAssemblyHalfDayTimeLimit = new DateTime("10:40:00");
    // $officeAssemblyAbsentTimeLimit = new DateTime("10:45:00");

    // $minu = $time1 - $time2;
    // $minutime = date("H:i:s", $minu);

    // $hours = floor($minu / 3600);
    // $mins = floor($minu / 60 % 60);
    // $secs = floor($minu % 60);

    // $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    
    switch ($userTeam) {
        case "assist":
            $officeAssemblyLateTimeLimit = new DateTime("11:35:00");
            $officeAssemblyHalfDayTimeLimit = new DateTime("11:40:00");
            $officeAssemblyAbsentTimeLimit = new DateTime("11:45:00");
            break;
        case "consulting":
            $officeAssemblyTime = new DateTime("17:00:00");
            // $officeAssemblyLateTimeLimit = new DateTime("12:35:00");
            $officeAssemblyHalfDayTimeLimit = new DateTime("17:05:00");
            $officeAssemblyAbsentTimeLimit = new DateTime("17:10:00");
            $officeEndTime = 00;
            $officeInitialCheckout = new DateInterval('PT6H');
            break;
        case "morning":
            $officeAssemblyTime = new DateTime("09:00:00");
            // $officeAssemblyOneTime = new DateTime("09:30:00");
            $officeAssemblyHalfDayTime = new DateTime("09:30:00");
            $officeAssemblyAbsentTime = new DateTime("09:35:00");
            $officeEndTime = 17;
            $officeInitialCheckout = new DateInterval('PT8H30M');
            $officeFinalCheckout = new DateTime('19:00:00');
            break;
        default:
            $officeAssemblyTime = new DateTime("10:00:00");
            // $officeAssemblyOneTime = new DateTime("10:10:00");
            $officeAssemblyHalfDayTime = new DateTime("10:30:00");
            $officeAssemblyAbsentTime = new DateTime("10:35:00");
            $officeEndTime = 18;
            $officeInitialCheckout = new DateInterval('PT8H30M');
            $officeFinalCheckout = new DateTime('20:00:00');
            break;
    }
    
    $attendanceStatus = 'NA';
    if ($type == 'checkin') {
        // Calculate checkout time based on checkin time
        $checkoutTime = clone $time1;
        if ($time1 < $officeAssemblyTime){
            $checkoutTime->add($officeInitialCheckout); // add 8 hours
        } elseif ($time1 > $officeAssemblyTime && $time1 <= $officeAssemblyHalfDayTime){
            $lateMinutes = $time1->diff($officeAssemblyTime)->i; // calculate late minutes
            $checkoutTime->setTime($officeEndTime, 30, 0); // set checkout time to 17:00:00
            $checkoutTime->add(new DateInterval("PT" . ($lateMinutes) . "M")); // add late minutes x2
        } elseif ($time1 > $officeAssemblyHalfDayTime && $time1 <= $officeAssemblyAbsentTime){
            $attendanceStatus = "Half-Day";
            // $lateHours = $time1->diff($officeAssemblyTime)->h; // calculate late hours
            $lateMinutes = $time1->diff($officeAssemblyTime)->i; // calculate late minutes
            $extralateMinutes = $time1->diff($officeAssemblyHalfDayTime)->i; // calculate late minutes
            $checkoutTime->setTime($officeEndTime, 30, 0); // set checkout time to 17:00:00
            $checkoutTime->add(new DateInterval("PT" . ($lateMinutes + $extralateMinutes) . "M")); // add late minutes x2
            // $checkoutTime->add(new DateInterval("PT" . ($extralateMinutes) . "M")); // add late minutes x2
            // $checkoutTime->add(new DateInterval("PT" . ($lateHours) . "H" . ($lateMinutes * 2) . "M")); // add late minutes x2
        } elseif ($time1 > $officeAssemblyAbsentTime){
            $attendanceStatus = "Absent";
            $lateHours = $time1->diff($officeAssemblyTime)->h; // calculate late hours
            $lateMinutes = $time1->diff($officeAssemblyTime)->i; // calculate late minutes
            $extralateMinutes = $time1->diff($officeAssemblyHalfDayTime)->i; // calculate late minutes
            $checkoutTime->setTime($officeEndTime, 30, 0); // set checkout time to 17:00:00
            $checkoutTime->add(new DateInterval("PT" . ($lateHours) . "H")); // add late minutes x2
            $checkoutTime->add(new DateInterval("PT" . ($lateMinutes + $extralateMinutes) . "M")); // add late minutes x2
            // $checkoutTime->add(new DateInterval("PT" . ($lateHours) . "H" . ($lateMinutes * 2) . "M")); // add late minutes x2
        }
        $checkoutTimeStr = $checkoutTime->format('Y-m-d H:i:s');
        
        $sql_a = $mysqli->query("SELECT * FROM emptrack WHERE empID='$userID' && date='$date1'") or die($mysqli->error());
        if ( $sql_a->num_rows > 0 ) {
            $result = array(
                'stat' => 'alreadyExists'
            );
            // print_r($result);
            echo json_encode($result);
        } else {
            // Insert checkin data into database
            $sql_a = "INSERT INTO emptrack (empID, empName, year, month, attendanceStat, date, inTime, expectedCheckout)" . "VALUES ('$userID', '$username','$year','$month','$attendanceStatus', '$date1', '$time', '$checkoutTimeStr')";
            if ($mysqli->query($sql_a)){
                $result = array(
                    'stat' => 'checkin',
                    'expectedCheckoutTime' => $checkoutTimeStr,
                );
                // print_r($result);
                echo json_encode($result);
            }
        }
    } elseif ($type == 'checkout') {
        // Retrieve checkin data from database
        $sql_b = "SELECT inTime, expectedCheckout FROM emptrack WHERE empID='$userID' AND date='$date1'";
        $result = mysqli_query($mysqli, $sql_b) or die("SQL Failed");
        $checkinTime = $result->fetch_assoc()['inTime'];
        $expectedCheckout = $result->fetch_assoc()['expectedCheckout'];
        
        // Calculate attendance status based on checkout time
        $checkoutTime = new DateTime($time);
        $diff = $checkoutTime->diff(new DateTime($checkinTime));
        $hours = $diff->h;
        $minutes = $diff->i;
        $totalHours = $hours + ($minutes/60);
        // Check if actual checkout is less than expected checkout
        if ($expectedCheckout < $officeFinalCheckout) {
            if ($totalHours < 5) {
                $attendanceStatus = "Absent";
            } elseif ($totalHours >= 5 && $totalHours < 8.5 && $userTeam != "consulting") {
                $attendanceStatus = "Half-Day";
            } elseif ($totalHours >= 5 && $totalHours < 7 && $userTeam == "consulting") {
                $attendanceStatus = "Half-Day";
            } else {
                $attendanceStatus = "Present";
            }
        }
        
        // Update attendance status and checkout time in database
        $sql_c = "UPDATE emptrack SET attendanceStat='$attendanceStatus', actualCheckout='$time' WHERE empID='$userID' AND date='$date1'";
        if ($mysqli->query($sql_c)){
            $result = array(
                'stat' => 'checkout',
                'attendance' => $attendanceStatus,
            );
            // print_r($result);
            echo json_encode($result);
            // print_r($attendanceStatus);
        }
    }

    // switch ($userTeam) {
    //     case "assist":
    //         $officeAssemblyLateTimeLimit = new DateTime("11:35:00");
    //         $officeAssemblyHalfDayTimeLimit = new DateTime("11:40:00");
    //         $officeAssemblyAbsentTimeLimit = new DateTime("11:45:00");
    //         break;
    //     case "consulting":
    //         $officeAssemblyLateTimeLimit = new DateTime("12:35:00");
    //         $officeAssemblyHalfDayTimeLimit = new DateTime("12:40:00");
    //         $officeAssemblyAbsentTimeLimit = new DateTime("12:45:00");
    //         break;
    //     default:
    //         $officeAssemblyLateTimeLimit = new DateTime("10:35:00");
    //         $officeAssemblyHalfDayTimeLimit = new DateTime("10:40:00");
    //         $officeAssemblyAbsentTimeLimit = new DateTime("10:45:00");
    //         break;
    // }
    
    // if ($time1 < $officeAssemblyLateTimeLimit){
    //     $attendanceStatus = "Present";
    // } elseif ($time1 < $officeAssemblyHalfDayTimeLimit && $time1 > $officeAssemblyLateTimeLimit){
    //     $attendanceStatus = "Late";
    // } elseif ($time1 < $officeAssemblyAbsentTimeLimit && $time1 > $officeAssemblyHalfDayTimeLimit){
    //     $attendanceStatus = "Half-Day";
    // } else{
    //     $attendanceStatus = "Absent";
    // }
    
    // $sql_a = "SELECT empName FROM emptrack WHERE date='$date1' AND year='$year' AND month='$month' AND empID='$userID'";
    // $result = mysqli_query($mysqli, $sql_a) or die("SQL Failed");
    
    // $outputEmpResult = [];
    // while($row = $result->fetch_assoc()){ 
    //     $outputEmpResult[] = $row;
    // }
    
    // echo "<script>console.log(".$outputEmpResult[0]['empName'].");</script>";

    // if($result->num_rows > 0){
    //     $sql_b = "UPDATE emptrack SET attendanceStat='$attendanceStatus', inTime='$time' WHERE empID='$userID' AND date='$date1'";
    //     if ($mysqli->query($sql_b)){
    //         print_r($attendanceStatus);
    //     }
    // } else {
    //     $sql_a = "INSERT INTO emptrack (empID, empName, year, month, attendanceStat, date, inTime)" . "VALUES ('$userID', '$username','$year','$month','$attendanceStatus', '$date1', '$time')";
    //     if ($mysqli->query($sql_a)){
    //         print_r($attendanceStatus);
    //     }
    // }
    
    // echo "<script>alert(".$time.");</script>";
?>