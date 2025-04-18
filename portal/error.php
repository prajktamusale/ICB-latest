<!-- <?php
/* Displays all error messages */
session_start();
?> -->
<!DOCTYPE html>
<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
  <link rel="manifest" href="../assets/favicon/site.webmanifest">
  <title>Error</title>
  <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="./css/error.css">
  <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
</head>
<body>
<div class="form">
    <h1>Error</h1>
    <p>
    <?php 
    if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ): 
        echo $_SESSION['message'];    
    else:
        header( "location: ./index.php" );
    endif;
    ?>
    </p>     
    <a href="./index.php"><button class="button button-block"/>Home</button></a>
</div>
<?php include "./components/bottomNav.php";?>
</body>
</html>