<?php
    include '../config.php';
    $email = $_GET['userEmail'];
    $mobile = $_POST['mobile'];
    $currPassword = $_POST['currPassword'];
    $updatedPassword = $_POST['updatedPassword'];
    $updatedPasswordConfirmation = $_POST['updatedPasswordConfirmation'];
    $address = $_POST['address'];
    if($updatedPassword == $updatedPasswordConfirmation){
        $sql = "UPDATE users SET mobile='$mobile', address='$address', password='$updatedPassword' WHERE email='$email' AND password='$currPassword'";
        $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
        mysqli_close($mysqli);
        header("Location: ../home.php");
    }
?>