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
    $borderColor = NULL;
    switch($_SESSION['type']){
        case "member": $borderColor= '#2196F3';
                       $checkSrc= './images/memberProfile.svg';
                       break;
    }
    $display = '';
    if($checkSrc == NULL){
        $display = 'd-none';
    }
    $profile = $_SESSION['profile'];

    // $instagram = ($_SESSION['instagram'] == "") ? 'bondsocially' : $_SESSION['instagram'];
    // $telegram = ($_SESSION['telegram'] == "") ? '' : $_SESSION['instagram'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="css/profile-css/main.css">
    <link rel="stylesheet" href="css/utils.css">
    <link rel="stylesheet" href="css/userProfile.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="./js/sideBar.js" defer></script>
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <?php 
        // include "./database/followProfile.php";
        // echo $userProfileResult
        include './config.php';
        // echo $_GET['username'];
        if(isset($_GET['username'])){
        //     // Sanitizing the username input
            $userProfile = mysqli_real_escape_string($mysqli, $_GET['username']);
            // echo $userProfile;
        //     // Selecting the data from the database
            $query = "SELECT username,type, firstname, lastname, profilepic, bio, instagram, initiatives, telegram, verifyEmail FROM users WHERE username='$userProfile';";
            $resultUser = mysqli_query($mysqli, $query) or die("Unknown User");
            $userProfile = NULL;
            $display2 = '';
            $borderColor2 = '#2196F3';
            $checkSrc2= './images/memberProfile.svg';
            if(mysqli_num_rows($resultUser)>0){
                $userProfile = mysqli_fetch_array($resultUser);
                if($userProfile['verifyEmail'] == 1){
                    $display2 = '';
                }

                switch($userProfile['type']){
                    case "member": $borderColor2= '#2196F3';
                                   $checkSrc2= './images/memberProfile.svg';
                                   break;
                }
            }
            else{
                echo "<h2>No Account Found</h2>";
                // $_SESSION['message'] = "No account found";
                header("Location: ./home.php");
            }
        } 
        $currentUser = $_SESSION['username'];
        // $userProfile = mysqli_real_escape_string($mysqli, $_GET['follow']);
        $alreadyFollowing = "SELECT * FROM followers WHERE follower='".$currentUser."' and following='". $userProfile['username']."';";
        // echo $userProfile;
        $alreadyFollowing = mysqli_num_rows(mysqli_query($mysqli, $alreadyFollowing));
        ?>

    <title>Profile: <?php echo  $userProfile!=NULL?$userProfile['username']:'No account Found'."";  ?></title>
</head>
<body>
    <?php include "./header.php" ?>

    <header id="topSection" class="row" style="overflow: hidden;">
        <div class="container grid grid-header col-4">
            <?php
                echo "<div class='profilePicture'>" . "\n" .
                        "<img src='".$userProfile['profilepic']."' style='border-color:".$borderColor2.";' alt='{$userProfile['username']}'>" . "\n" .
                        "<img class='check2 ".$display2."' src='".$checkSrc2."' alt=''>" . "\n" .
                     "</div>";
            ?>
        </div>

        <div class="user-details col-6">
            <div class="user-name-div">
                <span class="username">
                    <?php
                        echo $userProfile['firstname']." ".$userProfile['lastname'];
                    ?>
                </span>
            </div>

            <div class="numbers">
                <span class="post">
                    <?php
                        echo ucwords($userProfile['type']);
                    ?>
                </span>
            </div>

            <div class="follow">
            <form method="post" action="./database/followProfile.php">
                <?php if($alreadyFollowing==0){
                    echo "<button class='btn btn-primary' type='submit' name='follow' value=". $userProfile['username'].">Follow</button>";
                 }else{
                    echo "<button class='btn btn-primary' type='submit' name='unfollow' value=".$userProfile['username'].">Unfollow</button>";
                 } ?>
            </form>
        </div>

            <div class="bio-div">
                <p class="bio">
                    <?php
                        echo $userProfile['bio'];
                    ?>
                </p>
            </div>
        </div>
        </div>

        <div class="container btn-ctn">

            <?php
                echo "<a href='https://www.instagram.com/" . $userProfile['instagram'] . "' target='_blank'><button class='btn follow-btn'>Instagram</button></a>
                <a href='https://t.me/" . $userProfile['telegram'] . "' target='_blank'><button class='btn follow2-btn'>Telegram</button></a>";
            ?>
        </div>

    </header>
    
    <section class="userPosts">
        
    </section>
            
    <?php include "./components/bottomNav.php";?>
</body>
</html>