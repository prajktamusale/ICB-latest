<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='employees' || $_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
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
    <link rel="stylesheet" href="./css/emp.css">
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <title>Employee Home</title>
</head>

<body style="background: #fff;">

    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>

    <!-- Heading -->
    <!-- <div class="topbar">
        <a href="javascript:void(0)" onclick="openNav()"><img src="./assets/hamburger.png" alt="Menu Icon"></a>
        <img id="userProfile" src="./assets/userProfile.png" alt="User Profile">
        <p>MandeepDalavi</p>

        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="#">Home</a>
            <a href="./attendance.html">My Attendance</a>
            <a href="./documents.html">My Documents</a>
        </div>
    </div> -->
    <div class="baseContainer">
        <div class="grid-item">
            <h2 id="greeting">Good Morning!</h2>
            <p><?php echo $_SESSION['full_name'] ?></p>
            <p><?php echo $_SESSION['empPosition'] ?></p> 
            <p>ID: <?php echo $_SESSION['id'] ?></p>
        </div>
        <div class="grid-item">
        <?php echo "<img src=".$_SESSION['profile']." alt='User Profile'>"; ?>
        </div>
    </div>
    <div class="btnContainer">
        <?php 
        
            if ($_SESSION['type'] == 'admin' || $_SESSION['type'] == 'superadmin'){
                echo '<div class="adminBtn">
                    <button type="button" onclick="window.location.href=\'./newEmployee.php\'">New Employee</button>
                    <button type="button" onclick="window.location.href=\'./empMarkAttendance.php\'">Mark Employee Attendance</button>
                </div>
                <button type="button" onclick="window.location.href=\'./empAttendanceView.php\'">Employee Attendance</button>';
            }

        ?>
        
        <button type="button" onclick="window.location.href='./empAttendance.php'">My Attendance</button>
        <button type="button" onclick="window.location.href='./empDocuments.php'">My Documents</button>
    </div>
    <!-- <div class="scanContainer">
        <a href="./employeeAttendanceScan.php"><img src="./images/scanner.png" alt="Scan Icon"></a>
        <p></p>
    </div> -->
    <?php
        include "components/bottomNav.php";
    ?>
</body>
<script>
    function userGreet() {
    var currHour = new Date().getHours();
    var greetMsg;

    if (currHour < 12)
        greetMsg = 'Good Morning!';
    else if (currHour >= 12 && currHour < 17)
        greetMsg = 'Good Afternoon!';
    else if (currHour >= 17  && currHour < 24)
        greetMsg = 'Good Evening!';
    
    document.getElementById('greeting').innerHTML = greetMsg;
    }    
    userGreet();
</script>
</html>