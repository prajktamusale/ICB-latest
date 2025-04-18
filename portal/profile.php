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
    $checkSrc = NULL;
    switch($_SESSION['type']){
        case "volunteer": $borderColor= '#87CEEB';
                       $checkSrc= './images/verify-volunteer.png';
                       break;
    }
    $display = '';
    if($checkSrc == NULL){
        $display = 'd-none';
    }
    $profile = $_SESSION['profile'];
    $instagram = ($_SESSION['instagram'] == "") ? 'bondsocially' : $_SESSION['instagram'];
    $telegram = ($_SESSION['telegram'] == "") ? '' : $_SESSION['instagram'];
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
    <link rel="stylesheet" href="css/profile-css/main.css">
    <!-- <link rel="stylesheet" href="css/profile-css/style.css"> -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <style>
        <?php include 'css/profile-css/style.css'; ?>
        input[type='range']::-webkit-slider-runnable-track {
            height: 10px;
            -webkit-appearance: none;
            color: <?php echo $borderColor ?>;
            margin-top: -1px;
            border-radius: 15px;
        }
  
        input[type='range']::-webkit-slider-thumb {
            width: 20px;
            -webkit-appearance: none;
            height: 20px;
            cursor: ew-resize;
            background: <?php echo $borderColor ?>;
            box-shadow: -800px 0 0 800px <?php echo $borderColor ?>;
            border-radius: 15px;
        }
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>

</head>

<body>

<?php
    include './header.php';
?>

    <!-- <section class="range-slider">

        <div class="container-slide"></div>
        <div class="range-slider-container-slide">
            <?php /*
            date_default_timezone_set('Asia/Kolkata');
            $date = date('Y-m-d');
            $currDate = strtotime($date);
            $expire = strtotime($_SESSION['expiration_date']);
            echo "<input type='range' class='range' min='0' max='365' step='1' disabled='true' value='" . ceil(abs($expire - $currDate) / 86400) . "'>" */
            ?>
            <div class="percentage">
                <span></span>
            </div>
        </div>



        <script>
            const rangeEl = document.querySelector('input')
            const percentage = document.querySelector('.percentage')
            const percentageSpan = document.querySelector('.percentage span')
            range()
            rangeEl.addEventListener('input', range)
            function range() {
                percentage.style.left = `${rangeEl.value / rangeEl.max * 100}%`;
                percentageSpan.innerText = `${rangeEl.value} Days`;
                percentage.style.filter = `hue-rotate(${rangeEl.value}deg)`
            }
        </script>

    </section> -->


    <!-- Header Section -->

    <header id="topSection" style="overflow: hidden;">
        <div class="container grid grid-header">
            <?php
                echo "<div class='profilePicture'>" . "\n" .
                        "<img src='".$profile."' style='border-color:".$borderColor.";' alt=''>" . "\n" .
                        "<img class='check2 ".$display."' src='".$checkSrc."' alt=''>" . "\n" .
                     "</div>";
            ?>
        </div>

        <div class="user-details">
            <div class="user-name-div">
                <span class="username">
                    <?php
                        echo $_SESSION['full_name'];
                    ?>
                </span>
            </div>

            <div class="numbers">
                <span class="post">
                    <?php
                        echo ucwords($_SESSION['type']);
                    ?>
                </span>
                <span class="follower">Joined on 
                    <?php
                        $date = strtotime($_SESSION['registration_date']);
                        $monthYear = date('M, Y', $date);
                        $date = date('d', $date);
                        $dateEnds = substr($date, 1);
                        if($date[0] == 0)$date = $dateEnds;
                        $dateSuffix = 'th';
                        switch($dateEnds){
                            case '1': $dateSuffix = 'st';
                                      break;
                            case '2': $dateSuffix = 'nd';
                                      break;
                            case '3': $dateSuffix = 'rd';
                                      break;
                        }
                        switch($date){
                            case '11': $dateSuffix = 'th';
                                       break;
                            case '12': $dateSuffix = 'th';
                                       break;
                            case '13': $dateSuffix = 'th';
                                       break;
                        }
                        echo $date . $dateSuffix . ' ' . $monthYear;
                    ?>
                </span>
            </div>

            <!-- <div class="name-div">
                <span class="name">WebApp | UI | Coding</span>
            </div>

            <div class="light-div">
                <span class="light-name">Interests</span>
            </div> -->

            <div class="bio-div">
                <p class="bio">
                    <?php
                        echo $_SESSION['bio'];
                    ?>
                </p>
            </div>
        </div>
        </div>

        <div class="container btn-ctn">

            <?php
                echo "<a href='https://www.instagram.com/" . $instagram . "' target='_blank'><button class='btn follow-btn'>Instagram</button></a>
                <a href='https://t.me/" . $telegram . "' target='_blank'><button class='btn follow2-btn'>Telegram</button></a>";
            ?>
            <!---   <button class="btn msg-btn">Connect<img src="images/user.png"></button>  -->
            <button id="btn-edit" class="profile-btn">Edit Profile</button>
            <button id="save" form="form" class="save-btn">Save</button>
            <button id="cancel" class="cancel-btn">Cancel</button>
            <br><br><br><br><br><br>
        </div>

    </header>


    <!-- Main Content Section -->

    <section id="edit" class="main-content d-flex">
        <div class="container2 d-flex">
            <div class="login-page d-flex">
                <div class="form">
                    <?php
                        echo "<form id='form' class='register-form' action='./database/editProfile.php?userEmail=" . $_SESSION['email'] . "' method='POST' onsubmit='return validate()' enctype='multipart/form-data'>";
                    ?>
                        <h1> Edit Profile</h1>
                        <span class="line">
                            <label>Profile:</label>
                            <?php
                                echo "<input name='file' type='file' id='file' accept='image/png, image/jpg, image/jpeg'/>";
                            ?>
                        </span>
                        <div id="usernameInst">
                            <ul style="list-style-type:disc;">
                                <li>username must start with a letter or underscore</li>
                                <li>username must contain only letters (both case), numbers and underscore</li>
                                <li>username must contain atleast 6 characters</li>
                            </ul>
                        </div>
                        <span class="line" style="position:relative;">
                            <label>Username:</label>
                            <div id="error_msg"></div>
                            <?php
                                echo "<input id='username' name='username' type='text' pattern='[a-zA-Z_]{1}[a-zA-Z0-9]{5,50}' placeholder='username' value='" . $_SESSION['username'] . "' required/>";
                            ?>
                        </span>
                        <span class="line">
                            <label>Phone:</label>
                            <?php
                                echo "<input name='mobile' type='phone' placeholder='phone' value='" . $_SESSION['mobile'] . "' required/>";
                            ?>
                        </span>
                        <span class="line">
                            <label>Instagram:</label>
                            <?php
                                echo "<input name='instagram' type='text' placeholder='Instagram Username' value='" . $instagram . "' />";
                            ?>
                        </span>
                        <span class="line">
                            <label>Telegram:</label>
                            <?php
                                echo "<input name='telegram' type='text' placeholder='Telegram Username' value='" . $telegram . "'/>";
                            ?>
                        </span>
                        <span class="line">
                            <label>Bio:</label>
                            <?php
                                echo "<textarea name='bio' placeholder='Type'>" . $_SESSION['bio'] . "</textarea>";
                            ?>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <?php include "./components/bottomNav.php";?>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="js/profile-js/script.js"></script>
    <!-- <script src="js/sideBar.js"></script> -->
</body>
<script>
    function validate() {
        const file = document.getElementById('file');
        var errMsg = "";
        if(file.files.length > 0) {
            for (const i=0; i<=file.files.length-1; i++) {
                const filesize = file.files[i].size;
                const filesizenew = Math.round(filesize/1024);
                if(filesizenew >= 1024) {
                    errMsg = 'File too Big, please select a file less than 1MB';
                }
                if(errMsg !== ""){
                    alert(errMsg);
                    return false;
                }
                return true;
            }
        }
    }

    $('#username').keyup(function(){
        var username = $('#username').val();

        if(username!='') {
            $.post('./database/checkUser.php', {username:username}, function(data){
                $('#error_msg').html(data);
                if(data == 'Username Available'){
                    $('#error_msg').removeClass();
                    $('#error_msg').addClass("form-success");
                } else if (data == 'Username Not Available') {
                    $('#error_msg').removeClass();
                    $('#error_msg').addClass("form-error");
                }
            });
        } else {
            $('#error_msg').html('');
        }
    });

    $('#username').focus(function(){
        $('#usernameInst').css("display", "block");
    });

    $('#username').focusout(function(){
        $('#usernameInst').css("display", "none");
    });
</script>

</html>