<?php
    /* Database connection settings */
    $host = 'localhost'; 
    // $user = 'u598508499_portal'; 
    $user = 'root';
    // $pass = 'F9^MBzQir^l';
    $pass = '';
    $db = 'icb_portal';
    // $db = 'u598508499_careforbharat';
    $mysqli = new mysqli($host, $user, $pass, $db) or die($mysqli->error); // In mysqli varaible a connection is opened. If unsuccessful will show error

//This is used to connect to the database in mysql
// Connection Variables
    $keyId = 'rzp_test_13G2isdzTpKXkK';
    $keySecret = '7NnSu0AIlRNFcodK20JLKyVn';
    $displayCurrency = 'INR';
    
    //These should be commented out in production
    // This is for error reporting
    // Add it to config.php to report any errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
