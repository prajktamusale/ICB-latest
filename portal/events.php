<?php
/*
Title: differenceIMade.php provides user with various section where they contriducted in graphical formats.

Dependencies:
- config.php
*/

// 1. Basic Authorization check
// 1.1 session_status() checks for the status of session.
    if (session_status() == PHP_SESSION_NONE) {
        // If session_status is equal to PHP_SESSION_NONE
        // Then start the session using session_start()
        session_start();
    }
    // 1.2. Checking if session variable logged_in is set not null and logged_in value is true
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        // Redirect to index.php page for login.
        header("Location: ./index.php");
    }
    // 1.3. If session type variable is not  set  
    if (!isset($_SESSION['type'])){
    // Redirect to index.html page.
        header("Location: ./index.php");
    }

    // Connecting to the database
    include './config.php'; //

    // 
    $today = date("Y-m-d");
    date_default_timezone_set('Asia/Kolkata');
    $currTime = date('Y-m-d H:i:s');
    $userfullname = $_SESSION['full_name'];
    // Sql query
    $sql = "SELECT * FROM events";
    // executing query
    // mysqli_query(<mysqli_instance>, <query>)
    // Incase if any error is encountered then displays SQL Falied message.
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    // All events array
    $outputEvents = [];
    if(mysqli_num_rows($resultEvents) > 0){ // check the number of rows.
        //mysqli_fetch_assoc: retrieve a single row of data from a result set obtained after executing a query
        while($row = mysqli_fetch_assoc($resultEvents)){ 
            $outputEvents[] = $row;
        }
    }

    // Variables for attended events and enrolled events
    $attendedEvents = [];
    $enrolledEvents = [];
    // Looping through the outputEvent array
    for($x=0; $x<sizeof($outputEvents); $x++){
        // Assigning variables the values
        $eventName = $outputEvents[$x]['eventName'];
        $eventDate = $outputEvents[$x]['eventDate'];
        $eventTableName = $outputEvents[$x]['eventTableName'];
        $email = $_SESSION['email'];
        // Fetching the enrolled events details for the specified email address
        $sql = "SELECT * FROM `$eventTableName` WHERE enrolledUserEmail='$email'";
        // Execiting the query
        $resultEventTable = mysqli_query($mysqli, $sql) or die("SQL Failed");
        $outputEventTable = NULL;
        if(mysqli_num_rows($resultEventTable) > 0){
            // The number of events occured so far.

            // Fetching the data as array of rows using mysqli_fetch_array
            $outputEventTable = mysqli_fetch_array($resultEventTable);
            if($outputEventTable['enrolledUserAttended'] != 0){
                // This append to the attendedEvents variable.
                $attendedEvents[] = (object) ['id' => $outputEventTable['id'], 'enrolledUserFullName' => $outputEventTable['enrolledUserFullName'], 'enrolledUserMobile' => $outputEventTable['enrolledUserMobile'], 'enrolledUserEmail' => $outputEventTable['enrolledUserEmail'], 'eventName' => $eventName, 'eventDate' => $eventDate];
            }
            else if($outputEvents[$x]['endTime']>$currTime){
                $enrolledEvents[] = (object) ['id' => $outputEventTable['id'], 'enrolledUserFullName' => $outputEventTable['enrolledUserFullName'], 'enrolledUserMobile' => $outputEventTable['enrolledUserMobile'], 'enrolledUserEmail' => $outputEventTable['enrolledUserEmail'], 'eventName' => $eventName, 'eventDate' => $eventDate];
            }
        }
    }
    
    mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/events.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <title>CareforBharat</title>
</head>
<body>
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>

    <div class="container">
        <!-- Main options to display enrolled and attented events -->
        <div class="head">
            <div class="enrolledEventsHead">Enrolled</div>
            <div class="attendedEventsHead active">Attended</div>
        </div>
        <div class="enrollments d-block">
            <?php 
            // Checking the array fetched for events enrolled.
                if(sizeof($enrolledEvents)>0){
                    // If enrolledEvents size is greater than 0
                    for($x=0; $x<sizeof($enrolledEvents); $x++){
                        // looping through the events to be displayed
                        echo "<div class='enrolledEvent'>
                            <div class='enrolledEventHeading'>". $enrolledEvents[$x]->eventName . "</div>
                            <div class='enrolledEventData'>
                                <img src='./images/eye.svg' alt=''>
                            </div>
                        </div>";
                    }
                }
                // If enrolledEvents is empty then display this message.
                else echo "<div class='empty'>You have not enrolled in any event yet.</div>";
            ?>
        </div>
        <div class="attendedEvents d-none">
            <?php 
                // To connect to the database.
                include './config.php';
                // Checking the array fetched for events attendedEvents.
                if(sizeof($attendedEvents)>0){
                    // If attentedEvents size is greater than 0
                    for($x=0; $x<sizeof($attendedEvents); $x++){
                        // looping through the events to be displayed
                        echo "<div class='attendedEvent'>
                            <div class='attendedEventHeading'>" . $attendedEvents[$x]->eventName . "</div>
                            <div class='attendedEventData'>
                                <form action='./database/certificate.php' method='post' id='certificate'>
                                    <input type='hidden' name='userFullName' value='$userfullname'>
                                    <input type='hidden' name='eventName' value='".$attendedEvents[$x]->eventName."'>
                                    <input type='hidden' name='eventDate' value='".$attendedEvents[$x]->eventDate."'>
                                    <input type='submit' value=''>
                                </form>
                            </div>
                        </div>";
                    }
                }
                // If attentedEvents is empty then display this message.
                else echo "<div class='empty'>You have not attended any event.</div>";
            ?>
        </div>

        <?php
            $shouldDisplay = false;
            foreach ($outputEvents as $event) {
                if ($_SESSION["type"] == "employees" || $_SESSION["type"] == "admin" || $event["eventFor"] == "social") {
                    $shouldDisplay = true;
                    break;
                }
            }
            $display = $shouldDisplay ? "" : "d-none";
            echo "<div class='opportunities " . $display . "'>";
        ?>

            <!-- Upcoming Events Slider -->
            <div class="opportunityHead">Enroll Now</div>
            <div class="opportunityList">
                <!-- Slideshow container -->
                <div class="slideshow-container">
                    <!-- next button -->
                    <!-- plusSlides is javascript function -->
                    <a class="prev" onclick="plusSlides(-1)">&#62;&#62;</a>
                    <!-- Slider -->
                    <div class="slides">
                        <?php
                            include './config.php';
                            $linearGradient = '';
                            $email=$_SESSION['email'];
                            for($x=0; $x<sizeof($outputEvents); $x++){
                                if($outputEvents[$x]['endTime']>$currTime){
                                    $eventTableName = $outputEvents[$x]['eventTableName'];
                                    $enrolledEvent = $mysqli->query("SELECT * FROM `$eventTableName` WHERE enrolledUserEmail='$email'");
                                    switch($outputEvents[$x]["eventInitiative"]){
                                        case 'Animal Safety': $linearGradient = 'linear-gradient(90deg, rgba(224, 21, 24, 1) 0%, rgba(70, 70, 70, 0.2) 100%)'; 
                                                          $bannerImage = 'https://static.independent.co.uk/2022/06/06/11/GettyImages-544673512.jpg';
                                                          break;
                                        case 'Mental Health': $linearGradient = 'linear-gradient(90deg, rgba(203, 143, 189, 1) 0%, rgba(70, 70, 70, 0.2) 100%)';
                                                            $bannerImage = 'https://images.pexels.com/photos/185801/pexels-photo-185801.jpeg';
                                                            break;
                                        case 'Mission Shiksha': $linearGradient = 'linear-gradient(90deg, rgba(46, 197, 182, 1) 0%, rgba(70, 70, 70, 0.2) 100%)';
                                                                $bannerImage = 'https://images.pexels.com/photos/5088008/pexels-photo-5088008.jpeg';
                                                                break;
                                        case 'Environment': $linearGradient = 'linear-gradient(90deg, rgba(65, 217, 80, 1) 0%, rgba(70, 70, 70, 0.2) 100%)';
                                                            $bannerImage = 'https://images.unsplash.com/photo-1545147986-a9d6f2ab03b5';
                                                            break;
                                        case 'Art & Craft': $linearGradient = 'linear-gradient(90deg, rgba(52, 152, 219, 1) 0%, rgba(70, 70, 70, 0.2) 100%)';
                                                            $bannerImage = 'https://images.unsplash.com/photo-1522167428-d603a1d62f26';
                                                            break;
                                        case 'Sex Education': $linearGradient = 'linear-gradient(90deg, rgba(255, 190, 0) 0%, rgba(70, 70, 70, 0.2) 100%)';
                                                            $bannerImage = 'https://images.unsplash.com/photo-1545693315-85b6be26a3d6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=871&q=80';
                                                            break;
                                    }
                                    if(($_SESSION["type"] == "employees" or $_SESSION["type"] == "admin") and $outputEvents[$x]["eventFor"] == "employees"){
                                        echo "<div class='mySlides slide' style='background: $linearGradient, url($bannerImage) no-repeat center center / cover;'>
                                         <div class='opportunityDetails'>
                                            <div class='opportunityName'>".$outputEvents[$x]["eventName"]."</div>
                                             <div class='opportunityInitiative'>Initiative: ".$outputEvents[$x]["eventInitiative"]."</div>
                                             <div class='coreOpportunityDetails'>
                                                <div class='opportunityDate'>Date: ".$outputEvents[$x]["eventDate"]."</div>
                                                <div class='opportunityDate'>Venue: ".$outputEvents[$x]["eventVenue"]."</div>
                                                <div class='opportunityDate'>Time: ".date("h:i a", strtotime($outputEvents[$x]["eventTime"]))."</div>
                                                <div class='opportunityDate'>Requirements: ".$outputEvents[$x]["eventRequirements"]."</div>
                                             </div>
                                         </div>";
                                         if(mysqli_num_rows($enrolledEvent) == 0){
                                            echo"<a class='enroll' href='./database/enrollEvent.php?userEmail=$email&event=$eventTableName'>Enroll</a></div>";
                                         } else {
                                            echo"<button disabled id='enrolledbtn' style='cursor:default;'>Enrolled</button></div>";
                                         }
                                    } else {
                                        if($outputEvents[$x]["eventFor"] == "social"){
                                            echo "<div class='mySlides slide' style='background: $linearGradient, url($bannerImage) no-repeat center center / cover;'>
                                                <div class='opportunityDetails'>
                                                    <div class='opportunityName'>".$outputEvents[$x]["eventName"]."</div>
                                                    <div class='opportunityInitiative'>Initiative: ".$outputEvents[$x]["eventInitiative"]."</div>
                                                    <div class='coreOpportunityDetails'>
                                                        <div class='opportunityDate'>Date: ".$outputEvents[$x]["eventDate"]."</div>
                                                        <div class='opportunityDate'>Venue: ".$outputEvents[$x]["eventVenue"]."</div>
                                                        <div class='opportunityDate'>Time: ".date("h:i a", strtotime($outputEvents[$x]["eventTime"]))."</div>
                                                        <div class='opportunityDate'>Requirements: ".$outputEvents[$x]["eventRequirements"]."</div>
                                                    </div>
                                                </div>";
                                            if(mysqli_num_rows($enrolledEvent) == 0){
                                                echo"<a class='enroll' href='./database/enrollEvent.php?userEmail=$email&event=$eventTableName'>Enroll</a></div>";
                                            } else {
                                                echo"<button disabled id='enrolledbtn' style='cursor:default;'>Enrolled</button></div>";
                                            }
                                        }
                                    }
                                }
                            }
                            mysqli_close($mysqli);
                        ?>
                    </div>
                    <!-- Next button -->
                    <a class="next" onclick="plusSlides(1)">&#62;&#62;</a>
                </div>
                <br>
                <!-- The dots/circles -->
                <div style="text-align:center" class="dots">
                    <?php
                        include './config.php';
                        $sql = "SELECT * FROM events WHERE endTime>'$currTime'";
                        $resultAllEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");

                        $outputCurrEvents = [];
                        if(mysqli_num_rows($resultAllEvents) > 0){
                            while($row = mysqli_fetch_assoc($resultAllEvents)){
                                $outputCurrEvents[] = $row;
                            }
                        }

                        $relevantEvents = 0;
                        foreach ($outputCurrEvents as $event) {
                            if ($_SESSION["type"] == "employees" || $_SESSION["type"] == "admin" || $event["eventFor"] == "social") {
                                $relevantEvents++;
                            }
                        }
                        for($x=0; $x<$relevantEvents; $x++){
                            echo "<span class='dot' onClick='currentSlide(" . $x . "+1)'></span>";
                        }
                        mysqli_close($mysqli);
                    ?>
                </div>
            </div>
        </div>

    </div>
    <?php include "./components/bottomNav.php";?>
    <script src="./js/opportunity.js"></script>
    <script src="./js/event.js"></script>
    <script src="./js/sideBar.js"></script>
</body>
</html>