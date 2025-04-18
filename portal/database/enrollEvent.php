<?php
    include '../config.php';
    $email = $_GET['userEmail'];
    $eventTableName = $_GET['event'];

    $sql = "SELECT * FROM `$eventTableName` WHERE enrolledUserEmail='$email'";
    $result = mysqli_query($mysqli, $sql) or die('Some Error Occured');

    if(mysqli_num_rows($result)==0){
        $sql = "SELECT firstName, lastName, mobile FROM users WHERE email='$email'";
        $resultUser = mysqli_query($mysqli, $sql) or die("SQL Failed");
        if(mysqli_num_rows($resultUser) > 0){
            $outputUser = mysqli_fetch_array($resultUser);
        }
        $enrolledUserFullName = $outputUser['firstName']." ".$outputUser['lastName'];
        $enrolledUserMobile=$outputUser['mobile'];
        $sql = "INSERT INTO `$eventTableName` (enrolledUserFullName, enrolledUserMobile, enrolledUserEmail) VALUES ('$enrolledUserFullName', $enrolledUserMobile, '$email')";
        $resultUser = mysqli_query($mysqli, $sql);
    }
    mysqli_close($mysqli);
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("Location: $previousPage");
?>