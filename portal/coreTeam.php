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
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/coreTeam.css">
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

    <!-- Heading -->
    <h2>Contact Team</h2>

    <div class="container">
        <div class="contactTeam"></div>
        <div class="contactTeam">
            <div class="coreTeamMember">
                <div class="profilePictureCTM">
                    <div class="profilePicture">
                        <img src="./images/core-team/atharv.jpg">
                        <img class="check" src="./images/checkadmin.png">
                    </div>
                </div>
                <div class="contactDetailsCTM">
                    <div class="nameCTM">Atharv Sawant</div>
                    <div class="positionCTM">Chairman</div>
                    <div class="socialMediaHandles">
                        <a href="https://t.me/atharvs16escape" target="_blank" class="telegram">Telegram</a>
                        <a href="https://www.instagram.com/atharvs16escape/" target="_blank" class="instagram">Instagram</a>
                    </div>
                </div>
            </div>
            <div class="coreTeamMember">
                <div class="profilePictureCTM">
                    <div class="profilePicture">
                        <img src="./images/core-team/mandeep.jpg">
                        <img class="check" src="./images/checkadmin.png">
                    </div>
                </div>
                <div class="contactDetailsCTM">
                    <div class="nameCTM">Mandeep Dalavi</div>
                    <div class="positionCTM">Vice Chairman</div>
                    <div class="socialMediaHandles">
                        <a href="https://t.me/MandeepDalavi" target="_blank" class="telegram">Telegram</a>
                        <a href="https://www.instagram.com/mandeepdalavi" target="_blank" class="instagram">Instagram</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php include "./components/bottomNav.php";?>
    <script src="./js/sideBar.js"></script>
    <!-- <script src="./js/coreTeam.js"></script> -->
</body>
</html>