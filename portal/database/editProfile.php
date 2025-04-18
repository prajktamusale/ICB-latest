<?php
    include '../config.php';
    session_start();

    $file = $_FILES['file'];
    $username = $_POST['username'];
    $mobile = $_POST['mobile'];
    $instagram = $_POST['instagram'];
    $telegram = $_POST['telegram'];
    $bio = $mysqli->escape_string($_POST['bio']);
    $userEmail = $_GET['userEmail'];

    function uploadfile($file, $userEmail) {
        include '../config.php';

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size']; // size in bytes. 1 KB = 1024 Bytes
        $fileSizeKb = round($fileSize / 1024);
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowedExt = array('png', 'jpg', 'jpeg');

        if (in_array($fileActualExt, $allowedExt)) {
            if ($fileError === 0) {
                if ($fileSizeKb < 1024) {
                    $fileNameNew = $userEmail.uniqid('', true).".".$fileActualExt;
                    $fileDestination = '../../pro-pics/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
    
                    $fileDestinationDb = '../pro-pics/'.$fileNameNew;
                    $mysqli->query("UPDATE users SET profilepic='$fileDestinationDb' WHERE email='$userEmail'");
                } else {
                    echo 'Your file is too big!';
                }
            } else {
                echo 'There was an error uploading your file!';
            }
        } else {
            echo 'You cannot upload files of this type!';
        }
    }

    if($_FILES['file']['error'] != 4) {
        echo 'isset';
        print_r($_FILES['file']);
        $result = $mysqli->query("SELECT profilepic FROM users WHERE email='$userEmail'");
        $resultArr = $result->fetch_assoc();
        $oldProfile = $resultArr['profilepic'];
        if (!($oldProfile == './images/defaultprofile.png')) {
            $oldProfileExp = explode('/', $oldProfile);
            $oldProfileName = end($oldProfileExp);
            $oldProfilePath = "../../pro-pics/".$oldProfileName;
            if (file_exists($oldProfilePath)){
                echo 'old file exist';
                unlink($oldProfilePath); // old file deleted
                echo 'old file deleted';
            }
            uploadfile($file, $userEmail);
        } else {
            uploadfile($file, $userEmail);
        }
    }
    
    $sql = "UPDATE users SET username='$username', mobile='$mobile', instagram='$instagram', telegram='$telegram', bio='$bio' WHERE email='$userEmail'";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
    mysqli_close($mysqli);
    $_SESSION['message'] = "Some changes may take time to reflect in your account. We request you to login again!";
    header("Location: ../success.php");
?>