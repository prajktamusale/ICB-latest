<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
  header("Location: ./index.php");
}
if (!isset($_SESSION['type'])) {
  header("Location: ../index.html");
}

include './config.php';
include '/header.php';
include '/navbar.php';
include '/sidebaar.php';
include '/footer.php';
?>
