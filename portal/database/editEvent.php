<?php   
    $eventName = $_POST['eventName'];
    $eventInitiative = $_POST['eventInitiative'];
    $eventTableName = $_POST['eventTableName'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $eventVenue = $_POST['eventVenue'];
    $eventRequirements = $_POST['eventRequirements'];
    $eventID = $_POST['eventID'];
    $previousEventTableName = $_POST['existingEventTableName'];
        
    include '../config.php';

    $sql = "UPDATE events SET eventName='$eventName', eventInitiative='$eventInitiative', eventTableName='$eventTableName', eventDate='$eventDate', eventVenue='$eventVenue', eventTime='$eventTime', eventRequirements='$eventRequirements' WHERE id='$eventID'";
    $result = mysqli_query($mysqli, $sql) or die('Event not editted');

    $sql = "ALTER TABLE `$previousEventTableName` RENAME TO `$eventTableName`";
    $result = mysqli_query($mysqli, $sql) or die('Event Table Creation Unsuccessful');

    mysqli_close($mysqli);
    header("Location: ../particularEvent.php?eID=$eventID&eTN=$eventTableName");
?>