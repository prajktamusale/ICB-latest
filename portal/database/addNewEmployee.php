<?php
    require '../config.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';

    $firstName = $mysqli->escape_string($_POST['firstname']);
    $lastName = $mysqli->escape_string($_POST['lastname']);
    $userName = $firstName.$lastName;
    $userFullName = $firstName.' '.$lastName;
    $userEmail = $mysqli->escape_string($_POST['email']);
    $userMobile = $mysqli->escape_string($_POST['mobile']);
    $userAadhaar = $mysqli->escape_string($_POST['aadhaar']);
    $userPAN = $mysqli->escape_string($_POST['pan']);
    $userAccount = $mysqli->escape_string($_POST['ac_number']);
    $userIfsc = $mysqli->escape_string($_POST['ifsc']);
    $userPosition = $mysqli->escape_string($_POST['empposition']);
    $userType = $mysqli->escape_string($_POST['usertype']);
    
    $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
    $hash = $mysqli->escape_string(hash('sha512', md5(rand(99,9999))));
    date_default_timezone_set("Asia/Kolkata");
    $userRegistrationDate = date("Y-m-d");
    $userExpirationDate = date("Y-m-d", strtotime($userRegistrationDate."+365 days"));

    $sql_a = "INSERT INTO users (firstName, lastName, username, email, password, hash, registrationDate, expirationDate, profilepic, bio, mobile, aadhaar, pan, accountNum, ifsc, empPosition, type) VALUES ('$firstName', '$lastName', '$userName', '$userEmail', '$password', '$hash', '$userRegistrationDate', '$userExpirationDate', './images/defaultprofile.png', 'Welcome!',  '$userMobile', '$userAadhaar', '$userPAN', '$userAccount', '$userIfsc', '$userPosition', '$userType')";

    if ($mysqli->query($sql_a)){
        echo "<script>if(confirm('New Employee Added Successfully!')){window.location.href='https://careforbharat.in/portal/newEmployee.php';}else{window.location.href='https://careforbharat.in/portal/newEmployee.php';}</script>";
        //header("refresh:3;url=https://careforbharat.in/portal/newEmployee.php");
    }
?>