<?php   
    $eventName = $_POST['eventName'];
    $eventInitiative = $_POST['eventInitiative'];
    $eventFor = $_POST['eventFor'];
    $eventTableName = $_POST['eventTableName'];
    $eventDate = $_POST['date'];
    $eventMonth = $_POST['month'];
    $eventYear = $_POST['year'];
    $eventStartHour = $_POST['startHour'];
    $eventStartMinute = $_POST['startMinute'];
    $eventEndHour = $_POST['endHour'];
    $eventEndMinute = $_POST['endMinute'];
    $eventVenue = $_POST['eventVenue'];
    $eventRequirements = $_POST['eventRequirements'];
    $today = date("Y-m-d");
    $eDate = date_format(date_create("$eventYear-$eventMonth-$eventDate"), "Y-m-d");
    $eStartTime = date("Y-m-d H:i:s", mktime($eventStartHour, $eventStartMinute, 0, $eventMonth, $eventDate, $eventYear));
    $eEndTime = date("Y-m-d H:i:s", mktime($eventEndHour, $eventEndMinute, 0, $eventMonth, $eventDate, $eventYear));
    if($today <= $eDate){
        include '../config.php';

        $sql = "INSERT INTO events (eventName, eventInitiative, eventFor, eventTableName, eventDate, eventVenue, eventTime, endTime, eventRequirements) VALUES ('$eventName', '$eventInitiative', '$eventFor', '$eventTableName', '$eDate', '$eventVenue', '$eStartTime', '$eEndTime', '$eventRequirements')";
        $result = mysqli_query($mysqli, $sql) or die('Event not added');

        $sql = "CREATE TABLE `$eventTableName`(
            id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            enrolledUserFullName VARCHAR(255) NOT NULL,
            enrolledUserMobile BIGINT(15) NOT NULL,
            enrolledUserEmail VARCHAR(255) NOT NULL,
            enrolledUserAttended TINYINT(1) NOT NULL DEFAULT 0
        )";
        $result = mysqli_query($mysqli, $sql) or die('Event Table Creation Unsuccessful');

        mysqli_close($mysqli);
    }

    header("Location: ../index.php");

?>