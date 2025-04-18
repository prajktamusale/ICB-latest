<?php

    require 'config.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
    }

    $sql = "SELECT id, firstName, lastName FROM users";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $outputResult = [];
    
    while($row = $result->fetch_assoc()){ 
        $outputResult[] = $row;
    }
    echo "<script>console.log(".json_encode($outputResult).")</script>"

    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     if (isset($_POST['markAttendance'])) {
    //         $empName = $mysqli->escape_string($_POST['employee']);
    //         $currDate = $mysqli->escape_string($_POST['currDate']);
    //         $currTime = $mysqli->escape_string($_POST['currTime']);
    //         $status = $mysqli->escape_string($_POST['status']);
    //         $reason = $mysqli->escape_string($_POST['reason']);

    //         $year = date("Y", strtotime($currDate));
    //         $month = date("M", strtotime($currDate));

    //         $sql_a = "INSERT INTO emptrack (empName, year, month, attendance, reason, date, inTime) VALUES ('$empName', '$year', '$month', '$status', '$reason', '$currDate', '$currTime')";
    //         if ($mysqli->query($sql_a)){
    //             echo "<script>alert('Attendance Recorded Successfully!')</script>";
    //         }
    //     }
    // }
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
    <title>New Employee</title>
    <link rel="stylesheet" href="./css/emp.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        <h3 id="documentTitle">NEW EMPLOYEE</h3>
    </div>
    <p class="daysPresent"></p>
    <div class="userTab" id="userTab">
        <button class="default" id="existingUserBtn">Existing User</button>
        <button class="" id="newUserBtn">New User</button>
    </div>
    <div class="selectEmployee" id="selectEmployee">
        <select name="existEmployee" id="existEmployee">
            <option value="" selected disabled hidden>Employee</option>
            <?php
                for($x=0; $x<sizeof($outputResult); $x++){
                    echo '<option value="'.$outputResult[$x]['id'].'" >'.$outputResult[$x]['id'].' | '.$outputResult[$x]['firstName'].' '.$outputResult[$x]['lastName'].'</option>';
                }
            ?>
        </select>
    </div>
    <div class="tableContainer attend">
        <form action="./database/upgradeToEmployee.php" id="empDetails" method="post">
            <input type="text" name="firstname" placeholder="First Name" id="firstName" required>
            <input type="text" name="lastname" placeholder="Last Name" id="lastName" required>
            <input type="email" name="email" placeholder="Email" id="userEmail" required>
            <input type="number" name="mobile" placeholder="Mobile" id="userMobile" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
            <input type="number" name="aadhaar" placeholder="Aadhaar" id="userAadhaar" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
            <input type="text" name="pan" placeholder="PAN" id="userPan" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" required>
            <input type="number" name="ac_number" placeholder="A/C Number" id="userAccount" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
            <input type="text" name="ifsc" placeholder="IFSC Code" id="userIFSC" required>
            <input type="text" name="empposition" placeholder="Employee Position" id="userPosition" required>
            <input type="number" name="empsalary" placeholder="Employee Salary" id="userSalary" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
            <div class="pass-group" id="pass-group">
                <input type="password" name="password" placeholder="Create Password" id="userPassword" required>
                <button type="button" id="shPassword" onclick="showPassword()"><i class="fa-solid fa-eye" id="passEye"></i></button>
                <button type="button" id="genPassword" onclick="generatePassword()"><i class="fa-solid fa-arrows-rotate"></i></button>
            </div>
            <select name="usertype" id="userType">
                <option value="" selected disabled hidden>User Type</option>
                <option value="volunteer">Volunteer</option>
                <option value="associate">Associate</option>
                <option value="employees">Employee</option>
            </select>
            <input type="hidden" name="userid" id="userID">
            <button type="submit" name="submit" id="submit">Submit</button>
        </form>
    </div>
    <?php
        include "components/bottomNav.php";
    ?>
</body>
<script>
    const existingUser = document.getElementById('existingUserBtn');
    const newUser = document.getElementById('newUserBtn');
    const userPass = document.getElementById('userPassword');

    existingUser.addEventListener("click", function(){
        existingUser.classList.remove("default");
        existingUser.classList.add("selected");
        newUser.classList.remove("selected");
        document.getElementById("selectEmployee").style.display = "flex";
        document.getElementById("userTab").style.marginBottom = "0";
        document.getElementById("empDetails").action = "./database/upgradeToEmployee.php";
        document.getElementById("pass-group").style.display = "none";
        document.getElementById("userPassword").removeAttribute('required');
    });

    newUser.addEventListener("click", function(){
        existingUser.classList.remove("default");
        existingUser.classList.remove("selected");
        newUser.classList.add("selected");
        document.getElementById("selectEmployee").style.display = "none";
        document.getElementById("userTab").style.marginBottom = "20px";
        document.getElementById("empDetails").action = "./database/addNewEmployee.php";
        document.getElementById("pass-group").style.display = "flex";
        document.getElementById("userPassword").setAttribute('required');
    });

    function generatePassword() {
        var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var passwordLength = 15;
        var password = "";
        for (var i = 0; i <= passwordLength; i++) {
            var randomNumber = Math.floor(Math.random() * chars.length);
            password += chars.substring(randomNumber, randomNumber +1);
        }
        document.getElementById("userPassword").value = password;
    }

    function showPassword() {
        const eye = document.getElementById('passEye');
        if(userPass.type === "password") {
            userPass.type = "text";
        } else {
            userPass.type = "password";
        }
        if(eye.classList.contains("fa-eye")){
            eye.classList.remove("fa-eye");
            eye.classList.add("fa-eye-slash");
        } else {
            eye.classList.remove("fa-eye-slash");
            eye.classList.add("fa-eye");
        }
    }

    $('#existEmployee').change(function () {
        var selectedUser = $(this).val();
        $('#userName').val(selectedUser);
        $.ajax({
            url: './database/getExistingUserData.php',
            method: 'post',
            data: { userID: selectedUser },
            dataType: 'json',
            success: function (res) {
                var result = JSON.stringify(res);
                console.log(res);
                
                $('#firstName').val(res[0].firstName);
                $('#lastName').val(res[0].lastName);
                $('#userEmail').val(res[0].email);
                $('#userMobile').val(res[0].mobile);
                $('#userAadhaar').val(res[0].aadhaar);
                $('#userPan').val(res[0].pan);
                $('#userAccount').val(res[0].accountNum);
                $('#userIFSC').val(res[0].ifsc);
                $('#userPosition').val(res[0].empPosition);
                $('#userType').val(res[0].type);
                $('#userID').val(res[0].id);
            },
            error: function (err) {
                console.log(err);
            }
        });
    });
</script>
</html>