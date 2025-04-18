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

    $id=$_GET['eID'];
    $eventTableName=$_GET['eTN'];

    $sql = "SELECT * FROM events WHERE id='$id'";
    $resultEvent = mysqli_query($mysqli, $sql) or die('Some error occured');

    $outputEvent=NULL;
    if(mysqli_num_rows($resultEvent)>0){
        $outputEvent=mysqli_fetch_array($resultEvent);
    }

    $sql = "SELECT * FROM `$eventTableName`;";
    $resultUsers = mysqli_query($mysqli, $sql) or die('Some error occured');
    $outputUsersAll = [];
    if(mysqli_num_rows($resultUsers) > 0){
        while($row = mysqli_fetch_assoc($resultUsers)){
            $outputUsersAll[] = $row;
        }
    }
    $enrolledUsers = [];
    $attendedUsers = [];
    for($x=0; $x<sizeof($outputUsersAll); $x++){
        if($outputUsersAll[$x]['enrolledUserAttended']==0){
            $enrolledUsers[] = $outputUsersAll[$x];
        }
        else{
            $attendedUsers[] = $outputUsersAll[$x];
        }
    }

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
    <title><?php echo $outputEvent['eventName']; ?></title>

    <!-- Font awesome key -->
    <script src="https://kit.fontawesome.com/ec7ea1a9d7.js" crossorigin="anonymous"></script>

    <!-- stylesheet link -->
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/eventmanage.css">
    <link rel="stylesheet" href="./css/utils.css">
    <style>
        <?php
            switch($outputEvent['eventInitiative']){
                case 'Animal Safety': $bannerColor = '224, 21, 24';break;
                case 'Mental Health': $bannerColor = '203, 143, 189';break;
                case 'Mission Shiksha': $bannerColor = '46, 197, 182';break;
                case 'Environment': $bannerColor = '65, 217, 80';break;
                case 'Art & Craft': $bannerColor = '52, 152, 219';break;
                case 'Sex Education': $bannerColor = '255, 190, 0';break;
                default: $bannerColor = '0, 0, 0';break;
            }

            echo ".eventPopup .event-form .input-group input:focus, .container .login-email .input-group input:valid {
                border-color: rgba($bannerColor, 0.7);
            }
    
            .eventPopup .event-form .input-group select:focus, .container .login-email .input-group select:valid {
                border-color: rgba($bannerColor, 0.7);
            }";
        ?>
        
    </style>
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
</head>
<body>

<!-- Navbar goes here  -->
<?php
    include 'header.php';
?>

<!-- event name banner -->
<?php
    switch($outputEvent['eventInitiative']){
        case 'Animal Safety': $bannerColor = '224, 21, 24';
                              $bannerImage = 'https://static.independent.co.uk/2022/06/06/11/GettyImages-544673512.jpg';
                              break;
        case 'Mental Health': $bannerColor = '203, 143, 189';
                              $bannerImage = 'https://images.pexels.com/photos/185801/pexels-photo-185801.jpeg';
                              break;
        case 'Mission Shiksha': $bannerColor = '46, 197, 182';
                                $bannerImage = 'https://images.pexels.com/photos/5088008/pexels-photo-5088008.jpeg';
                                break;
        case 'Environment': $bannerColor = '65, 217, 80';
                            $bannerImage = 'https://images.unsplash.com/photo-1545147986-a9d6f2ab03b5';
                            break;
        case 'Art & Craft': $bannerColor = '52, 152, 219';
                            $bannerImage = 'https://images.unsplash.com/photo-1522167428-d603a1d62f26';
                            break;
        case 'Sex Education': $bannerColor = '255, 190, 0';
                              $bannerImage = 'https://images.unsplash.com/photo-1545693315-85b6be26a3d6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=871&q=80';
                              break;
        default: $bannerColor = '0, 0, 0';
                 $bannerImage = 'https://images.pexels.com/photos/6331088/pexels-photo-6331088.jpeg';
                 break;
    }
    echo "<div class='peBanner' style='background: linear-gradient(180deg, rgba($bannerColor, 0.7) 22%, rgba($bannerColor, 0.14) 100%), url($bannerImage);background-position: center;background-repeat: no-repeat;background-size: cover;'>
            <button class='editbtn' onclick='openEventPop()'><img src='./images/edit.png' title='Edit Event' alt='Edit Event' class='sideImg edit'></button>
            <h1 class='peHeading'>".$outputEvent['eventName']."</h1>
            <p class='peSub'>".$outputEvent['eventInitiative']."</p>
            <button class='deletebtn' onclick='openDeletePop()'><img src='./images/delete.png' title='Delete Event' alt='Delete Event' class='sideImg delete'></button>
          </div>"
?>

<!-- details list -->
<div class="peDetails">
    <div class="peList">
        <i class="fa-solid fa-calendar-check fa-2x"></i> 
        <p><?php echo $outputEvent['eventDate']; ?></p>
    </div>
    <div class="peList">
        <i class="fa-solid fa-clock fa-2x"></i>
        <p><?php echo date("h:i a", strtotime($outputEvent['eventTime'])); ?></p>
    </div>
    <div class="peList">
        <i class="fa-solid fa-location-dot fa-2x"></i>
        <p><?php echo $outputEvent['eventVenue']; ?></p>
    </div>
    <div class="peList">
        <i class="fa-solid fa-rectangle-list fa-2x"></i>        
        <p><?php echo $outputEvent['eventRequirements']; ?></p>
    </div>
</div>

<!-- Enrollments list  -->
<div class="peEnrol">
    <div class="peHead">
        <h2>Enrollments</h2>
        <h2>Count : <span class="eCount"><?php echo sizeof($outputUsersAll); ?></span></h2>
    </div>
    <div class="peBody">
        <?php
            for($x=0; $x<sizeof($enrolledUsers); $x++){
                    echo "<div class='eRow'>
                            <div class='eName'>" . $enrolledUsers[$x]['enrolledUserFullName'] . "</div>" .
                           "<div class='eNum'>" . $enrolledUsers[$x]['enrolledUserMobile'] . "</div>
                            <a href='./database/attendance.php?attended=true&eTN=$eventTableName&email=" . $enrolledUsers[$x]['enrolledUserEmail'] . "'><i style='color:#00ba00' class='fa-solid fa-circle-check'></i></a>
                            <i style='color:#f80505' class='fa-solid fa-circle-xmark'></i>
                         </div>";
            }
            for($x=0; $x<sizeof($attendedUsers); $x++){
                    echo "<div class='eRow'>
                            <div class='eName eNameAttended'>" . $attendedUsers[$x]['enrolledUserFullName'] . "</div>" .
                           "<div class='eNum eNumAttended'>" . $attendedUsers[$x]['enrolledUserMobile'] . "</div>
                           <i style='color:#00ba00' class='fa-solid fa-circle-check'></i>
                           <a href='./database/attendance.php?attended=no&eTN=$eventTableName&email=" . $attendedUsers[$x]['enrolledUserEmail'] . "'><i style='color:#f80505' class='fa-solid fa-circle-xmark'></i></a>
                         </div>";
            }
        ?>
    </div>
</div>

<div class="eventPopup" id="eventpop">
    <form action="./database/editEvent.php" method="post" class="event-form">
        <p class='pop-text'>Event Editor</p>
        <div class="input-group">
            <input type="text" title="Event Name" placeholder="Event Name" name="eventName" value="<?php echo $outputEvent['eventName']; ?>" autocomplete="off" required>
        </div>
        <div class="input-group">
            <select name='eventInitiative' title="Event Initiative" required>
                <option <?php echo ($outputEvent['eventInitiative']=='Mental Health') ? "selected='true'" : "" ?> value="Mental Health">Mental Health</option>
                <option <?php echo ($outputEvent['eventInitiative']=='Mission Shiksha') ? "selected='true'" : "" ?> value="Mission Shiksha">Mission Shiksha</option>
                <option <?php echo ($outputEvent['eventInitiative']=='Animal Safety') ? "selected='true'" : "" ?> value="Animal Safety">Animal Safety</option>
                <option <?php echo ($outputEvent['eventInitiative']=='Art & Craft') ? "selected='true'" : "" ?> value="Art & Craft">Art & Craft</option>
                <option <?php echo ($outputEvent['eventInitiative']=='Environment') ? "selected='true'" : "" ?> value="Environment">Environment</option>
                <option <?php echo ($outputEvent['eventInitiative']=='Sex Education') ? "selected='true'" : "" ?> value="Sex Education">Sex Education</option>
            </select>
        </div>
        <div class="input-group">
            <input type="text" title="Event Table Name" placeholder="Event Table Name" name="eventTableName" value="<?php echo $eventTableName; ?>" autocomplete="off" required>
        </div>
        <div class="input-group">
            <input type="date" id="eventDate" title="Event Date" placeholder="Event Date" name="eventDate" value="<?php echo $outputEvent['eventDate']; ?>" autocomplete="off" required>
        </div>
        <div class="input-group">
            <input type="time" title="Event Time" placeholder="Event Time" name="eventTime" value="<?php echo date("h:i", strtotime($outputEvent['eventTime'])); ?>" autocomplete="off" required>
        </div>
        <div class="input-group">
            <input type="text" title="Event Address" placeholder="Event Venue" name="eventVenue" value="<?php echo $outputEvent['eventVenue']; ?>" autocomplete="off" required>
        </div>
        <div class="input-group">
            <input type="text" title="Any requirements for Attendees" placeholder="Requirements" name="eventRequirements" value="<?php echo $outputEvent['eventRequirements']; ?>" autocomplete="off" required>
        </div>
        <input type="hidden" name="eventID" value="<?php echo $id; ?>">
        <input type="hidden" name="existingEventTableName" value="<?php echo $eventTableName; ?>">
        <div class="input-group">
            <button class="btn" name="eventEditSubmit" style="background: rgba(<?php echo $bannerColor; ?>, 0.7)">Submit</button>
        </div>
    </form>
</div>
<div class="closer" id="closer" onclick="closeEventPop(); closeDeletePop()"></div>

<div class="deletePopup" id="deletepop">
    <div class="warning">
        <img src="./images/danger.png" alt="warning">
    </div>
    <div class="warning-content">
        <h1>Warning!</h1>
        <p>Are you sure you want to delete this event?</p>
    </div>
    <form action="./database/deleteEvent.php" method="POST" class="delete-form">
        <input type="hidden" name="eventID" value="<?php echo $id; ?>">
        <input type="hidden" name="existingEventTableName" value="<?php echo $eventTableName; ?>">
        <div class="input-group">
            <button class="btn" name="eventDeleteSubmit">DELETE</button>
        </div>
    </form>
</div>
<?php include "./components/bottomNav.php";?>
<script src="./js/sideBar.js"></script>
<script>
    var date = new Date();
    var dd = date.getDate() + 1;
    var mm = date.getMonth() + 1; //January is 0!
    var yyyy = date.getFullYear();
    if(dd<10){
      dd='0'+dd
    } 
    if(mm<10){
      mm='0'+mm
    }
    
    var tomorrow = yyyy+'-'+mm+'-'+dd;
    document.getElementById("eventDate").setAttribute("min", tomorrow);


    function openEventPop() {
        document.getElementById("eventpop").style.display = "block";
        document.getElementById("closer").style.display = "block";
    }
    function closeEventPop() {
        document.getElementById("eventpop").style.display = "none";
        document.getElementById("closer").style.display = "none";
    }
    function openDeletePop() {
        document.getElementById("deletepop").style.display = "block";
        document.getElementById("closer").style.display = "block";
    }
    function closeDeletePop() {
        document.getElementById("deletepop").style.display = "none";
        document.getElementById("closer").style.display = "none";
    }
</script>
</body>
</html>