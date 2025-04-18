<?php
    require '../config.php';

    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $userName = $firstName.$lastName;
    $userEmail = $mysqli->escape_string($_POST['email']);
    $userMobile = $mysqli->escape_string($_POST['mobile']);
    $userAadhaar = $_POST['aadhaar'];
    $userPAN = $_POST['pan'];
    $userAccount = $_POST['ac_number'];
    $userIfsc = $_POST['ifsc'];
    $userPosition = $_POST['empposition'];
    $userSalary = $_POST['empsalary'];
    $userType = $_POST['usertype'];
    $userID = $_POST['userid'];

    $sql_a = "UPDATE users SET firstName='$firstName', lastName='$lastName', username='$userName', email='$userEmail', mobile='$userMobile', aadhaar='$userAadhaar', pan='$userPAN', accountNum='$userAccount', ifsc='$userIfsc', empPosition='$userPosition', empSalary='$userSalary', type='$userType' WHERE id='$userID'";
    
    if ($mysqli->query($sql_a)){
        echo "<script>if(confirm('User Upgraded Successfully!')){window.location.href='http://localhost/ICB/portal/newEmployee.php';}else{window.location.href='http://localhost/ICB/portal/newEmployee.php';}</script>";
        //header("refresh:3;url=http://localhost/ICB/portal/newEmployee.php");
    }
?>