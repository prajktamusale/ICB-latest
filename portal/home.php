<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!isset($_SESSION['type'])){
        header("Location: ./index.php");
    }

    include './config.php';

    $today = date("Y-m-d");
    date_default_timezone_set('Asia/Kolkata');
    $currTime = date('Y-m-d H:i:s');
    
    $sql = "SELECT * FROM announcements WHERE expiryDate>'$today' ORDER BY noticeDate DESC";
    $resultAnnouncements = mysqli_query($mysqli, $sql) or die("SQL Failed");
    
    $sql = "SELECT * FROM events WHERE endTime>'$currTime'";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");

    $sql = "select * from publicstory
    left JOIN followers on publicstory.username=followers.following
    order by publicstory.dateposted desc, followers.following ASC;";
    $resultPosts = mysqli_query($mysqli, $sql) or die("SQL Failed");

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
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/utils.css">

    <style>
        <?php 
            include "./css/home.css";
            include "./css/header.css"; 
            include "./css/bottomNav.css";
        ?>
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>ICB: Home</title>
</head>
<body>
    <!-- Navigation Bar -->
    <!-- <?php
    $checkSrc = NULL;
    $borderColor = NULL;
    $icbName = "<h2>Care For Bharat</h2>";
    $options = array("<button class='accordian'>Volunteer</button>
    <ul class='panel'>
        <a href='./home.php'>Home</a>
        <a href='./profile.php'>Profile</a>
        <a href='./events.php'>My Events</a>
        <a href='./differenceIMade.php'>Difference I Made</a>
        <a href='./shareMyStory.php'>Share My Story</a>
        <a href='./donate.php'>Donate</a>
    </ul>",
    "<button class='accordian'>Employee</button>
    <ul class='panel'>
        <a href='./empHome.php'>Employee Home</a>
        <a href='./empAttendanceScan.php'>Scan</a>
        <a href='./empDocuments.php'>My Docs</a>
        <a href='./empAttendance.php'>My Attendance</a>
        <a href='./attendanceQR.php'>Attendance QR</a>
    </ul>",
    "<button class='accordian'>Adminstrator</button>
    <ul class='panel'>
        <a href='./stats.php'>Stats</a>
        <a href='./eventsManage.php'>Event Manager</a>
        <a href='./announcement.php'>Announcement</a>
    </ul>"
    );
    $commonOptions = "<button class='accordian'>Support and Settings</button>
    <ul class='panel'>
        <a href=''>Settings & Support</a>
        <a href='./coreTeam.php'>Contact Team</a>
        <a href='./alert.php'>Send an Alert</a>
        <a href='./logout.php'>Logout</a>
    </ul>";
    
    switch($_SESSION['type']){
        case "superadmin": $borderColor= '#00FF00'; 
                        $checkSrc= './images/verify-admin.png';
                        $menu =  $options[0].$options[1].$options[2].$options[3]."\n".$commonOptions;
                        break;
        case "admin": $borderColor= '#00FF00'; 
                        $checkSrc= './images/verify-admin.png';
                        $menu =  $options[0].$options[1].$options[2]."\n".$commonOptions;
                        break;
        case "volunteer": $borderColor= '#87CEEB';
                    $checkSrc= './images/verify-volunteer.png';
                    $menu = $icbName.$options[0]."\n".$commonOptions;
                    break;
        case "employees": 
                    $borderColor= '#FFA500';
                    $checkSrc= './images/verify-employee.png';
                    $menu = $icbName.$options[0].$options[1].$commonOptions;
                    break;
    }
    $display = '';
    if($checkSrc == NULL){
        $display = 'd-none';
    }
    echo "<nav>
            <div class='hamBurger'>
                <div></div>
                <div></div>
                <div></div>
            </div>  
            <div class='userName'>" . $_SESSION['username'] . "</div>" . "\n" .
                "<div class='profilePicture'>" . "\n" .
                    "<img class='profPic' src='".$_SESSION['profile']."' style='border-color: " . $borderColor . ";' alt=''>" . "\n" .
                    "<img class='check " . $display . "' src='" . $checkSrc . "' alt=''>" . "\n" .
                "</div>" .
                "<a href='./shareMyStory.php' class='containerAddStory'>
                    <img src='./images/plus.png' class='addStory'></img>
                </a>" .
            "</div>
        </nav>" .
        "<div class='sideBar hideSideBar'>
            <div class='sideItems'>
                    ".$menu."
                <div class='cross'>
                    <img src='./images/cross.png' alt=''>
                </div>
            </div>
        </div>";
?> -->

<?php include"./header.php";?>
    <div class="container">
        <!-- <div class="search-container">
            <table class="tableSearch">
                <tr>
                    <td>
                        <input class="search" type="text" placeholder="Search for friends..." name="search">
                    </td>
                    <td class="searchLogo">
                        <a href=""><i class="fa fa-search"></i></a>
                    </td>
                </tr>
            </table>
        </div> -->

        <?php
            $outputAnnouncements = [];
            if(mysqli_num_rows($resultAnnouncements) > 0){
                while($row = mysqli_fetch_assoc($resultAnnouncements)){
                    $outputAnnouncements[] = $row;
                }
            }
            $display = sizeof($outputAnnouncements)==0?'d-none':'';
            echo "<div class='announcements " . $display . "'>";
        ?>
            <div class="announceHead">Big & Major Announcement</div>
            <div class="announceList">
                <ul class="announces">
                    <?php
                        for($x=0; $x < sizeof($outputAnnouncements); $x++){
                            echo "<li>" . $outputAnnouncements[$x]["content"] . "</li>" . "\n";
                        }
                    ?>
                </ul>
            </div>
        </div>

        <?php
            $outputEvents = [];
            if(mysqli_num_rows($resultEvents) > 0){
                while($row = mysqli_fetch_assoc($resultEvents)){
                    $outputEvents[] = $row;
                }
            }
            $shouldDisplay = false;
            foreach ($outputEvents as $event) {
                if ($_SESSION["type"] == "employees" || $_SESSION["type"] == "admin" || $event["eventFor"] == "social") {
                    $shouldDisplay = true;
                    break;
                }
            }
            $display = $shouldDisplay ? "" : "d-none";
            echo "<div class='opportunities " . $display . "'>";
            // $display = sizeof($outputEvents)==0?"d-none": "";
            // echo "<div class='opportunities " . $display . "'>";
        ?>

            <div class="opportunityHead">Opportunities Calendar</div>
            <div class="opportunityList">
                <!-- Slideshow container -->
                <div class="slideshow-container">
                    <!-- next button -->
                    <a class="prev" onclick="plusSlides(-1)">&#62;&#62;</a>
                    <div class="slides">
                        <script>
                            var user = <?php echo $_SESSION['type'] ?>;
                            var passedArray = <?php echo json_encode($outputEvents); ?>;
                            console.log(passedArray);
                        </script>
                        <?php
                            include './config.php';
                            $linearGradient = '';
                            $email=$_SESSION['email'];
                            for($x=0; $x<sizeof($outputEvents); $x++){
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
                                                    <div class='opportunityDate'>Date: ".$outputEvents[$x]["eventDate"]."(Y-m-d) </div>
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
                                                    <div class='opportunityDate'>Date: ".$outputEvents[$x]["eventDate"]."(Y-m-d) </div>
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
                            mysqli_close($mysqli);
                        ?>
                    </div>
                    <a class="next" onclick="plusSlides(1)">&#62;&#62;</a>
                </div>
                <br>
                <!-- The dots/circles -->
                <div style="text-align:center" class="dots">
                    <?php
                        $relevantEvents = 0;
                        foreach ($outputEvents as $event) {
                            if ($_SESSION["type"] == "employees" || $_SESSION["type"] == "admin" || $event["eventFor"] == "social") {
                                $relevantEvents++;
                            }
                        }
                        for($x=0; $x<$relevantEvents; $x++){
                            echo "<span class='dot' onClick='currentSlide(" . $x . "+1)'></span>";
                        }
                    ?>
                </div>
            </div>
        </div>

        <?php
            $outputPosts = [];
            if(mysqli_num_rows($resultPosts) > 0){
                while($row = mysqli_fetch_assoc($resultPosts)){
                    $outputPosts[] = $row;
                }
            }
            $display = sizeof($outputPosts)==0?'d-none':'';
            echo "<div class='Feeds $display'>
            <div class='FeedsHead $display'>Feeds</div>";
                include './config.php';
                $userEmail = $_SESSION['email'];
                $sql = "SELECT id FROM users WHERE email='$userEmail'";
                $result = mysqli_query($mysqli, $sql) or die('User Not Found');
                $outputUser = NULL;
                if(mysqli_num_rows($result)>0){
                    $outputUser = mysqli_fetch_array($result);
                }
                $userID = $outputUser['id'];
                for($x=0; $x<sizeof($outputPosts); $x++){
                    $postID = $outputPosts[$x]['id'];
                    $sql = "SELECT * FROM postlikes WHERE postID=$postID AND userID=$userID";
                    $result = mysqli_query($mysqli, $sql) or die('Error');
                    $likeSource = './images/empty_heart.png';
                    $liked='false';
                    if(mysqli_num_rows($result)>0){
                        $likeSource = './images/heart.png';
                        $liked='true';
                    }
                    $nLikes = 0;
                    if($outputPosts[$x]['likes']!=NULL)$nLikes=$outputPosts[$x]['likes'];
                    $likes = $nLikes==1?'Like': 'Likes';
                    $email = $outputPosts[$x]['userEmail'];
                    $sql = "SELECT `type`, `profilepic` FROM users WHERE email='$email'";
                    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
                    $output = NULL;
                    $checkSrc = NULL;
                    $borderColor = NULL;
                    if(mysqli_num_rows($result) > 0){
                        $output = mysqli_fetch_array($result);
                        $userProfilePic = $output['profilepic'];
                        if($output){
                            switch($output['type']){
                                case "volunteer": $borderColor= '#87CEEB';
                                            $checkSrc= './images/verify-volunteer.png';
                                            break;
    }
                        }
                    }
                    $display = '';
                    if($checkSrc == NULL){
                        $display = 'd-none';
                    }

                    if($outputPosts[$x]['postfile']){
                    echo "<div class='post'>
                            <a href='./userProfile.php?username={$outputPosts[$x]['username']}' target='_self'>
                              <div class='postUser'>" . 
                                  $outputPosts[$x]['username'] .
                                  "<div class='profilePicture FeedProfilePicture'>
                                       <img src='$userProfilePic' alt='userprofile' style='border-color: $borderColor'>
                                       <img src='$checkSrc' alt='' class='check $display'>
                                  </div>
                              </div></a>" .

                              "<div class='postImage'>
                                   <img src='{$outputPosts[$x]['postfile']}' alt='' class=''>
                              </div>" .
                              "<div class='postFooter'>
                                   <div class='LikeAndShare'>
                                       <a href='./database/alterLikes.php?userID=$userID&postID=$postID&like=$liked'><img src='$likeSource' alt=''></a>
                                       <div class='likeNumber'>" . $nLikes . " " . $likes . "</div>
                                   </div>
                                   <div class='description'>" .
                                       $outputPosts[$x]['caption'] .
                                   "</div>" .
                              "</div>
                          </div>";
                    }else{
                        echo "<div class='post'>
                            <a href='./userProfile.php?username={$outputPosts[$x]['username']}' target='_self'>
                              <div class='postUser'>" . 
                                  $outputPosts[$x]['username'] .
                                  "<div class='profilePicture FeedProfilePicture'>
                                       <img src='$userProfilePic' alt='userprofile' style='border-color: $borderColor'>
                                       <img src='$checkSrc' alt='' class='check $display'>
                                  </div>
                              </div></a>" .
                              "<div class='postFooter'>
                                   <div class='LikeAndShare'>
                                       <a href='./database/alterLikes.php?userID=$userID&postID=$postID&like=$liked'><img src='$likeSource' alt=''></a>
                                       <div class='likeNumber'>" . $nLikes . " " . $likes . "</div>
                                   </div>
                                   <div class='description'>" .
                                       $outputPosts[$x]['caption'] .
                                   "</div>" .
                              "</div>
                          </div>";
                    }
                }
                mysqli_close($mysqli);
            ?>
        </div>
    </div>
    <?php
        include "components/bottomNav.php";
    ?>
    <script src="./js/opportunity.js"></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
</body>
</html>