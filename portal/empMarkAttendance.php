<?php

    require './config.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
    }

    $sql = "SELECT * FROM users WHERE type='employees' OR type='admin'";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $outputResult = [];
    
    while($row = $result->fetch_assoc()){ 
        $outputResult[] = $row;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['markAttendance'])) {
            $empID = $_POST['employee'];
            $currDate = $mysqli->escape_string($_POST['currDate']);
            $currTime = $mysqli->escape_string($_POST['currTime']);
            $status = $mysqli->escape_string($_POST['status']);
            $reason = $mysqli->escape_string($_POST['reason']);

            $year = date("Y", strtotime($currDate));
            $month = date("M", strtotime($currDate));
            
            $sql_a = "SELECT empName FROM empTrack WHERE date='$currDate' AND year='$year' AND month='$month' AND empID='$empID'";
            $result = mysqli_query($mysqli, $sql_a) or die("SQL Failed");
            
            $outputEmpResult = [];
            while($row = $result->fetch_assoc()){ 
                $outputEmpResult[] = $row;
            }
            
            // echo "<script>console.log(".$outputEmpResult[0]['empName'].");</script>";

            if($result->num_rows > 0){
                $sql_b = "UPDATE empTrack SET attendanceStat='$status', reason='$reason', inTime='$currTime' WHERE empID='$empID' AND date='$currDate'";
                if ($mysqli->query($sql_b)){
                    echo "<script>alert('Attendance Recorded Successfully!')</script>";
                }
            } else {
                $sql_emp = "SELECT firstName, lastName FROM users WHERE id='$empID'";
                $resultEmpName = mysqli_query($mysqli, $sql_emp) or die("SQL Failed");
                $outputEmpName = [];
                while($row = $resultEmpName->fetch_assoc()){ 
                    $outputEmpName[] = $row;
                }
                $empName = $outputEmpName[0]["firstName"]." ".$outputEmpName[0]["lastName"];
                $sql_c = "INSERT INTO empTrack (empID, empName, year, month, attendanceStat, reason, date, inTime) VALUES ('$empID', '$empName', '$year', '$month', '$status', '$reason', '$currDate', '$currTime')";
                if ($mysqli->query($sql_c)){
                    echo "<script>alert('Attendance Recorded Successfully!')</script>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/coreTeam.css">
    <link rel="stylesheet" href="./css/utils.css">
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <title>Employee Mark Attendance</title>
    <link rel="stylesheet" href="./css/emp.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body style="background: #fff;">
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>
    <!-- <div class="topbar">
        <a href="javascript:void(0)" onclick="openNav()"><img src="./assets/hamburger.png" alt="Menu Icon"></a>
        <img id="userProfile" src="./assets/userProfile.png" alt="User Profile">
        <p>MandeepDalavi</p>

        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Clients</a>
            <a href="#">Contact</a>
        </div>
    </div> -->
    <div class="baseContainer docCtn">
        <h3 id="documentTitle">MARK ATTENDANCE</h3>
    </div>
    <p class="daysPresent"></p>
    <div class="tableContainer attend">
        <form action="" id="empAttendance" method="post">
            <select name="employee" id="employee">
                <option value="" selected disabled hidden>Employee</option>
                <?php
                    for($x=0; $x<sizeof($outputResult); $x++){
                        if ($outputResult[$x]["id"] != $_SESSION["id"]) {
                        echo '<option value="'.$outputResult[$x]["id"].'" >'.$outputResult[$x]["id"].' | '.$outputResult[$x]['firstName'].' '.$outputResult[$x]["lastName"].'</option>';
                        }
                    }
                ?>
            </select>
            <input type="date" name="currDate" id="currDate" required>
            <input type="time" name="currTime" id="currTime" value="00:00" min="09:00" max="21:00">
            <select name="status" id="status">
                <option value="" selected disabled hidden>Status</option>
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Half-Day">Half-Day</option>
                <option value="Absent">Absent</option>
                <option value="Leave">Leave</option>
                <option value="Paid Leave">Paid Leave</option>
            </select>
            <input type="text" name="reason" placeholder="Reason (if any)">
            <button type="submit" id="markAtnBtn" name="markAttendance">Submit</button>
        </form>
    </div>
    <?php
        include "components/bottomNav.php";
    ?>
</body>
<script>
    var date = document.getElementById("currDate");
    
    const minDate1 = new Date();
    const maxDate2 = new Date();
    minDate1.setDate(minDate1.getDate() - 2);
    maxDate2.setDate(maxDate2.getDate() + 3);
    let minDay = minDate1.getDate();
    let minMonth = minDate1.getMonth() + 1;
    let minYear = minDate1.getFullYear();
    let maxDay = maxDate2.getDate();
    let maxMonth = maxDate2.getMonth() + 1;
    let maxYear = maxDate2.getFullYear();
    let minDate = `${minYear}-${('0'+minMonth).slice(-2)}-${('0'+minDay).slice(-2)}`;
    let maxDate = `${maxYear}-${('0'+maxMonth).slice(-2)}-${('0'+maxDay).slice(-2)}`;
    
    console.log(minDate);
    console.log(maxDate);
    date.min = minDate;
    date.max = maxDate;
</script>
</html>