<!-- Navbar -->
<?php
    $checkSrc = NULL;
    $borderColor = NULL;
    switch($_SESSION['type']){
        case "admin": $borderColor= '#0ED678'; 
                      $checkSrc= './images/checkadmin.png';
                      $menu =  "<a href='./home.php'>Home</a>
                                <a href='./stats.php'>Stats</a>
                                <a href='./eventsManage.php'>Event Manager</a>
                                <a href='./announcement.php'>Announcement</a>
                                <a href='./profile.php'>Profile</a>
                                <a href='./trainings.php'>My Training</a>
                                <a href='./events.php'>My Events</a>
                                <a href='./differenceIMade.php'>Difference I Made</a>
                                <a href='./shareMyStory.php'>Share My Story</a>
                                <a href='./donate.php'>Donate</a>
                                <a href='./addMarshalls.php'>Add a Marshal</a>
                                <a href='./settings.php'>Settings & Support</a>
                                <a href='./logout.php'>Logout</a>";
                      break;
        case "core-team": $borderColor= '#FC8955';
                        $checkSrc= './images/shield core-team.png';
                        $menu =  "<a href='./home.php'>Home</a>
                                    <a href='./profile.php'>Profile</a>
                                    <a href='./trainings.php'>My Training</a>
                                    <a href='./events.php'>My Events</a>
                                    <a href='./differenceIMade.php'>Difference I Made</a>
                                    <a href='./shareMyStory.php'>Share My Story</a>
                                    <a href='./donate.php'>Donate</a>
                                    <a href='./addMarshalls.php'>Add a Marshal</a>
                                    <a href='./settings.php'>Settings & Support</a>
                                    <a href='./coreTeam.php'>Contact Team</a>
                                    <a href='./alert.php'>Send an Alert</a>
                                    <a href='./logout.php'>Logout</a>";
                        break;
        case "member": $borderColor= '#2196F3';
                       $checkSrc= './images/memberProfile.svg';
                       $menu = "<a href='./home.php'>Home</a>
                                <a href='./profile.php'>Profile</a>
                                <a href='./trainings.php'>My Training</a>
                                <a href='./events.php'>My Events</a>
                                <a href='./differenceIMade.php'>Difference I Made</a>
                                <a href='./shareMyStory.php'>Share My Story</a>
                                <a href='./donate.php'>Donate</a>
                                <a href='./settings.php'>Settings & Support</a>
                                <a href='./coreTeam.php'>Contact Team</a>
                                <a href='./alert.php'>Send an Alert</a>
                                <a href='./logout.php'>Logout</a>";
                       break;
        case "student": $borderColor= '#FFC4C4';
                        $menu ="<a href='./home.php'>Home</a>
                                <a href='./profile.php'>Profile</a>
                                <a href='./trainings.php'>My Training</a>
                                <a href='./events.php'>My Events</a>
                                <a href='./differenceIMade.php'>Difference I Made</a>
                                <a href='./shareMyStory.php'>Share My Story</a>
                                <a href='./donate.php'>Donate</a>
                                <a href='./settings.php'>Settings & Support</a>
                                <a href='./coreTeam.php'>Contact Team</a>
                                <a href='./alert.php'>Send an Alert</a>
                                <a href='./logout.php'>Logout</a>";
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
                <ul>
                    ".$menu."
                </ul>
                <div class='cross'>
                    <img src='./images/cross.png' alt=''>
                </div>
            </div>
        </div>";
?>