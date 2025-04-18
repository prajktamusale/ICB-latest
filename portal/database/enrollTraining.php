<?php
    include '../config.php';
    $userEmail = $_GET['userEmail'];
    $trainingTableName = $_GET['training'];
    $sql = "SELECT * FROM $trainingTableName WHERE enrolledUserEmail='$userEmail'";
    $result = mysqli_query($mysqli, $sql) or die('Some Error Occured');

    if(mysqli_num_rows($result) == 0){
        $sql = "SELECT username, mobile FROM users WHERE email='$userEmail'";
        $resultUser = mysqli_query($mysqli, $sql) or die("Unknown User");
        $outputUser=NULL;
        if(mysqli_num_rows($resultUser) > 0){
            $outputUser = mysqli_fetch_array($resultUser);
        }
        $enrolledUsername=$outputUser['username'];
        $enrolledUserMobile=$outputUser['mobile'];
        $enrolledUserEmail=$userEmail;
        $enrollmentDate = date("Y-m-d");
        $sql = "INSERT INTO $trainingTableName (enrolledUsername, enrolledUserMobile, enrolledUserEmail, enrollmentDate) VALUES ('$enrolledUsername', $enrolledUserMobile, '$enrolledUserEmail', '$enrollmentDate')";
        $result = mysqli_query($mysqli, $sql) or die('Service Unavailable');
    }
    mysqli_close($mysqli);
    header("Location: ../trainings.php");
?>