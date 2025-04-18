<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
    }

    include './config.php';

    $today = date("Y-m-d");
    date_default_timezone_set('Asia/Kolkata');
    $currTime = date('Y-m-d H:i:s');
    $sql = 'SELECT * FROM events';
    $result = mysqli_query($mysqli, $sql) or die('Data fetching issues');
    $outputEvents = [];
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $outputEvents[] = $row;
        }
    }
    $upcomingEvents = [];
    $pastEvents = [];
    for($x=0; $x<sizeof($outputEvents); $x++){
        if($outputEvents[$x]['endTime'] < $currTime){
            $pastEvents[] = $outputEvents[$x];
        }
        else {
            $upcomingEvents[] = $outputEvents[$x];
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
    <title>CareforBharat</title>
    <link rel="stylesheet" href="./css/header.css">
    <!-- <link rel="stylesheet" href="./css/main.css"> -->
    <link rel="stylesheet" href="./css/eventmanage.css">
    <link rel="stylesheet" href="./css/utils.css">
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>

</head>
<body>
    
    <?php
        include './header.php';
    ?>
    <br>
    <div class="eventNavbar">
        <button class="eButton upButton" role="button">Upcoming</button>    
        <button class="eButton pastButton" role="button">Past</button>    
    </div>

    <div class="eventBox">
        <div class="upcomingEvent">
            <br>
            <?php
                for($x=0; $x<sizeof($upcomingEvents); $x++){
                    $eventColor = '#fff';
                    switch($upcomingEvents[$x]["eventInitiative"]){
                        case 'Animal Safety': $eventColor = '#E01518 '; 
                                              break;
                        case 'Art & Craft': $eventColor = '#3498DB '; 
                                              break;
                        case 'Mental Health': $eventColor = '#CB8FBD';
                                              break;
                        case 'Mission Shiksha': $eventColor = '#2EC5B6';
                                                break;
                        case 'Environment': $eventColor = '#41D950';
                                            break;
                        case 'Sex Education': $eventColor = '#FFBE00';
                                              break;
                    }
                    echo "<a target='_blank' href='./particularEvent.php?eID=" . $upcomingEvents[$x]['id'] . "&eTN=" . $upcomingEvents[$x]["eventTableName"] .  "' style='color: black; text-decoration: none;'><div class='eventRow'>
                            <div class='eventColor' style='background-color:$eventColor;'></div>
                            <div class='eventName'>" . $upcomingEvents[$x]['eventName'] . "</div>
                            <div class='eventDate'>" . $upcomingEvents[$x]['eventDate'] . "</div>
                          </div></a>";
                }
            ?>
            <br>
        </div>
        <div class="pastEvent">
            <br>
            <?php
                for($x=0; $x<sizeof($pastEvents); $x++){
                    $eventColor = '#fff';
                    switch($pastEvents[$x]["eventInitiative"]){
                        case 'Animal Safety': $eventColor = '#E01518 '; 
                                              break;
                        case 'Art & Craft': $eventColor = '#3498DB '; 
                                              break;
                        case 'Mental Health': $eventColor = '#CB8FBD';
                                              break;
                        case 'Mission Shiksha': $eventColor = '#2EC5B6';
                                                break;
                        case 'Environment': $eventColor = '#41D950';
                                            break;
                        case 'Sex Education': $eventColor = '#FFBE00';
                                              break;
                    }
                    echo "<a target='_blank' href='./particularEvent.php?eID=" . $pastEvents[$x]['id'] . "&eTN=" . $pastEvents[$x]["eventTableName"] .  "' style='color: black; text-decoration: none;'><div class='eventRow'>
                            <div class='eventColor' style='background-color: $eventColor;'></div>
                            <div class='eventName'>" . $pastEvents[$x]['eventName'] . "</div>
                            <div class='eventDate'>" . $pastEvents[$x]['eventDate'] . "</div>
                          </div></a>";
                }
            ?>
            <br>
        </div>

        <div class="addEvent">
            <center>
            <h4 class="aeHead">Add Event</h4>
            </center>
            <form action="./database/addEvent.php" method="POST">
            <div class="aeForm">
                <br>
                <div class="aeRow">
                    <div class="aeTitle">Name:</div>
                    <div class="aeBox">
                        <input placeholder="Event Name" type="text" name='eventName' required>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Initiative:</div>
                    <div class="aeBox">
                            <select name='eventInitiative' required>
                                <option selected="true" disabled="true">Event Inititative</option>     
                                <option value="Mental Health">Mental Health</option>
                                <option value="Mission Shiksha">Mission Shiksha</option>
                                <option value="Animal Safety">Animal Safety</option>
                                <option value="Art & Craft">Art & Craft</option>
                                <option value="Environment">Environment</option>
                                <option value="Sex Education">Sex Education</option>
                            </select>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Event For:</div>
                    <div class="aeBox">
                            <select name='eventFor' required>
                                <option selected="true" disabled="true">Event For</option>     
                                <option value="social">Social</option>
                                <option value="employees">Employee</option>
                            </select>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Table Name:</div>
                    <div class="aeBox">
                        <input class="tableName" placeholder="Event Table Name" type="text" name='eventTableName' required>
                        <img class="setInfo" src="./images/info.png">
                        <span class="hiddenText">Event Name (lowercase) + date <br> eg: animalfeeding_19-07-2023 </span>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Date:</div>
                    <div class="aeBox">
                        <input name='date' class="neDate" placeholder="DD" step="1" min="1" max="31" type="number" required>
                        <input name='month' placeholder="MM" step="1" min="1" max="12" type="number" required>
                        <input name='year' placeholder="YYYY" min="2022" max="2100" type="text" required>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Start Time:</div>
                    <div class="aeBox">
                        <input name='startHour' placeholder="HH" step="1" min="0" max="24" type="number" required>
                        <input name='startMinute' placeholder="MM" step="1" min="0" max="59" type="number" required>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">End Time:</div>
                    <div class="aeBox">
                        <input name='endHour' placeholder="HH" step="1" min="0" max="24" type="number" required>
                        <input name='endMinute' placeholder="MM" step="1" min="0" max="59" type="number" required>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Venue:</div>
                    <div class="aeBox">
                        <input name='eventVenue' placeholder="Event Venue" type="text" required>
                    </div>
                </div>
                <div class="aeRow">
                    <div class="aeTitle">Requirements:</div>
                    <div class="aeBox">
                        <input name='eventRequirements' placeholder="Event Requirements" type="text" required>
                    </div>
                </div>
                <br>
                <center>
                <input type="submit" class = "button neButton" value="Save and Continue">
                </center>
                <br>
            </div>
            </form>
        </div>
    </div>

    <br><br><br>
    <?php include "./components/bottomNav.php";?>
    <script src="./js/sideBar.js"></script>
    <script src="./js/eventsManage.js"></script>

</body>
</html>