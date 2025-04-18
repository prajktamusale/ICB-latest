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
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $os = '';
    if (preg_match('/Windows/i', $userAgent)) {
        $os = 'Windows';
    } elseif (preg_match('/Macintosh/i', $userAgent)) {
        $os = 'Mac';
    } else {
        $_SESSION['message'] = "Device Not Supported! Please use Windows or Mac OS";
        header('Location: error.php'); // redirect to a "not supported" page
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <title>QR Attendance</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./css/header.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@600&display=swap');
        body{
            width:100%;
            min-height: 100vh;
            background: linear-gradient(-45deg, #DE2EFF 45%, #2690EC);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form{
            width:350px;
        }
        p{margin:0}
        .form #qrTitle {
            font-family: 'Inter', sans-serif;
            text-align: center;
            color: #000000;
            font-size:  30px;
        }
        #qrcode-container{
            display:none;
            width: 100%;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
        }
        #curDate, #curTime{
            font-family: sans-serif;
            font-weight: 600;
            text-align: center;
            color: #000000;
            font-size: 36px;
        }
        .btnDiv {
            display: flex;
            width: 100%;
            justify-content: space-around;
            align-items: center;
            padding: 15px 0;
        }
        button {
            background: rgb(175 196 248 / 47%);
            border: 1px solid #00000;
            width: 45%;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="form">
      <p id="qrTitle">SCAN TO MARK <br/>YOUR ATTENDANCE</p>
      <div id="qrcode-container">
        <div id="qrcode" class="qrcode"></div>
      </div>
      <p id="curDate"></p>
      <p id="curTime"></p>
      <div class="btnDiv">
          <button id="checkinBtn">Check-In</button>
          <button id="checkoutBtn">Check-Out</button>
      </div>
    </div>
</body>
<script>
    let checkIn = document.getElementById('checkinBtn');
    let checkOut = document.getElementById('checkoutBtn');
    let type = null;

    checkIn.addEventListener('click', () => {
        type = 'checkin';
        checkIn.style.background = 'transparent';
        checkOut.style.background = 'rgb(175 196 248 / 47%)';
    });
    checkOut.addEventListener('click', () => {
        type = 'checkout';
        checkOut.style.background = 'transparent';
        checkIn.style.background = 'rgb(175 196 248 / 47%)';
    });

    function generateQRCode() {
        const d = new Date().toJSON();
        const dt = new Date().toLocaleTimeString([],{hour12: false});
        let currentDate = d.slice(0, 10);
        // let currentTime = dt.slice(0, 8);
        // console.log(dt);
        // let dateTime = currentDate+'_'+dt+'_'+type;
        let dateTime = '2024-12-05_18:55:31_'+type;
        console.log(dateTime);
        let qrcodeContainer = document.getElementById("qrcode");
        qrcodeContainer.innerHTML = "";
        new QRCode(qrcodeContainer,  {
        text: dateTime,
        width: 250,
        height: 250,
        colorDark: "#000000",
        colorLight: "transparent",
        correctLevel: QRCode.CorrectLevel.H
        });
        
        document.getElementById("qrcode-container").style.display = "flex";
    }
    generateQRCode();
    setInterval(generateQRCode, 1000);

    function generateDateTime() {
        var currdate = new Date().toDateString();
        var currtime = new Date().toLocaleTimeString();
        document.getElementById("curDate").textContent = currdate;
        document.getElementById("curTime").textContent = currtime;
    }
    setInterval(generateDateTime, 999);

    // var userAgent = navigator.userAgent;
    // if (!/Windows|Macintosh/i.test(userAgent)) {
    //     window.location.href = 'error.php'; // redirect to a "not supported" page
    // }
</script>
</html>