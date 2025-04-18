<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='employees' || $_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
    }
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
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/coreTeam.css">
    <link rel="stylesheet" href="./css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <title>Scan</title>
    <link rel="stylesheet" href="./css/emp.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body style="background: #fff;">
     <!-- Navigation Bar -->
     <?php
        include './header.php';
    ?>
    <!-- <div class="topbar">
        <a href="javascript:void(0)" onclick="openNav()"><img src="./assets/hamburger.png" alt="Menu Icon"></a>
        <img id="userProfile" src="./assets/userProfile.png" alt="User Profile">
        <p>MandeepDalavi</p>

        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Clients</a>
            <a href="#">Contact</a>
        </div>
    </div> -->
    <div class="baseContainer docCtn">
        <h3 id="documentTitle">SCAN QR</h3>
    </div>
    <p class="daysPresent"></p>
    <div class="tableContainer">
        <div id="reader"></div>
        <div id="result">
            <dotlottie-player src="https://lottie.host/9337c082-ad1b-4161-959a-eadd1ab84200/hJBV5tfxbw.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></dotlottie-player>
            <h3>Attendance Recorded Successfully!</h3>
            <p id="resultContent"></p>
            <p id="resultStatus"></p>
        </div>
    </div>
    <?php
        include "components/bottomNav.php";
    ?>
</body>
<!-- <script src="./script.js"></script> -->
<script>
    
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        /* handle success */
        let fetchDate = decodedText.slice(0,10);
        let fetchTime = decodedText.slice(11,19);
        let fetchType = decodedText.slice(20);
        document.getElementById('result').style.display = 'flex';
        var resultStat = document.getElementById('resultStatus');
        // console.log(decodedText);
        var dataSend = {};
        //var content = {};
        dataSend.date = fetchDate;
        dataSend.time = fetchTime;
        dataSend.type = fetchType;
        //dataSend.push(content);

        $.ajax({
            url:'./database/saveAttendance.php',
            method: "post",
            data: { dataSend : JSON.stringify( dataSend ) },
            success: function(res){
                console.log(res);
                var response = JSON.parse(res);
                console.log(response);
                if(response.stat == 'checkin'){
                    document.getElementById('resultContent').innerHTML = 'Check-In Time: '+fetchDate+' '+fetchTime;
                    resultStat.innerHTML = "Expected Check-Out: "+response.expectedCheckoutTime;
                } else if (response.stat == 'alreadyExists') {
                    resultStat.innerHTML = "Error: Check-In already done!";
                } else {
                    document.getElementById('resultContent').innerHTML = 'Check-Out Time: '+fetchDate+' '+fetchTime;
                    resultStat.innerHTML = "You have been Marked: "+response.attendance;
                }
            }
        })

        html5QrCode.stop();
        html5QrCode.stop().then((ignore) => {
            // QR Code scanning is stopped.
          }).catch((err) => {
            // Stop failed, handle it.
          });
        };

        const mediaCheckDefault = window.matchMedia("screen and (min-width: 551px)")
        const mediaCheckOne = window.matchMedia("screen and (max-width: 550px)")

        var config;

        mediaCheckDefault.addListener(function(e){
            if (e.matches){
                var config = { fps: 50, qrbox: { width: 300, height: 300 } };
            }
        })
        mediaCheckOne.addListener(function(e){
            if (e.matches){
                var config = { fps: 50, qrbox: { width: 250, height: 250 } };
            }
        })
                
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);

</script>
</html>