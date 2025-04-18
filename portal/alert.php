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
    <title>Document</title>
    <link rel="stylesheet" href="./css/main.css">
    <!-- <link rel="stylesheet" href="./css/alert.css"> -->
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/utils.css">
    <link rel="stylesheet" href="./css/bottomNav.css">
    <style>
        <?php
            include './css/alert.css';
        ?>
    </style>
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
</head>

<body>
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>
    <h2>Send an alert</h2>
<div class="container">
    <div class="form-container">
        <form action="./database/sendalert.php" id="alert" method="POST">
            <label>Name:</label>
            <input type="text" placeholder="Full Name of Patient/Contact Person" name="patientName" required><br><br>
            <label>Type:</label>
            <select name="emergencyType" id="cars" placeholder="Types of Emergency:">
                <option value="Human Care">Human Care</option>
                <option value="Animal Care">Animal Care</option>
                <option value="Relief/Pandemic">Relief/Pandemic</option>
                <option value="Medical">Medical</option>
            </select><br><br>
            <label>Place:</label>
            <input type="text" placeholder="City" name="place" required><br><br>
            <label>Address:</label>
            <input type="text" placeholder="Patient&#39;s Address" name="patientAddress" required><br><br>
            <label>Phone:</label>
            <input type="number" placeholder="Phone Number of Patient/Contact Person" name="patientMobile" required><br><br>
            <label>Description:</label>
            <textarea name="description" form="alert" placeholder="Please describe the situation..."></textarea>
            <div class="btn">
                <input type="submit" class="submit" name="submit">
            </div>
        </form>
    </div>
    </div>
    <?php include "./components/bottomNav.php";?>
    <script src="./js/alert.js"></script>
    <script src="./js/sideBar.js"></script>
</body>

</html>