<?php
    $eventID = $_POST['eventID'];
    $previousEventTableName = $_POST['existingEventTableName'];

    include '../config.php';

    $sql = "DELETE FROM events WHERE id='$eventID'";
    $result = mysqli_query($mysqli, $sql) or die('Event not editted');

    $sql = "DROP TABLE IF EXISTS `$previousEventTableName`";
    $result = mysqli_query($mysqli, $sql) or die('Event Table Creation Unsuccessful');

    mysqli_close($mysqli);
    header("Location: ../eventsManage.php");
?>