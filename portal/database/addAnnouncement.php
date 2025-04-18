<?php
    include '../config.php';
    $expiryDate = $mysqli->escape_string($_POST['endDate']);
    $noticeDate = date("Y-m-d");
    $content = $mysqli->escape_string($_POST['announcement']);
    $mysqli->query("INSERT INTO announcements (noticeDate, expiryDate, content) VALUES ('$noticeDate', '$expiryDate', '$content')");
    header("location: ../home.php");
?>