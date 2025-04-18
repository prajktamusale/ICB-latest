<?php 
/* Title: Verifies registered user email, the link to this page
   is included in the register.php email message 
*/
require 'config.php';
// 1. Start Session
session_start();

// 2. Verify email and hash variables: 
// 2.1 Make sure email and hash variables aren't empty
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
{
    // 2.1.1 Verify email id and hash to prevent SQL injection
    $email = $mysqli->escape_string($_GET['email']);
    $hash = $mysqli->escape_string($_GET['hash']);
    
    // 2.1.2 Select user with matching email and hash, who hasn't verified their account yet (active = 0)
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email' AND hash='$hash' AND verifyEmail=0");
    // 2.1.3 If the number of rows is 0.
    if ( $result->num_rows == 0 )
    { 
        // Session message updated: Account already active or invalid URL proided.
        $_SESSION['message'] = "Account has already been activated or the URL is invalid!";
        // Redirect to error.php
        header("location: error.php");
    }
    // 2.1.4 If the number of rows is not zero
    else {
        // Update session message value. 
        $_SESSION['message'] = "Your account has been activated!";
        
        // Update the verified column value as 1 in database.
        // Set the user status to active (active = 1)
        $mysqli->query("UPDATE users SET verifyEmail='1' WHERE email='$email'") or die($mysqli->error);
        // set session varible verifyEmail as 1
        $_SESSION['verifyEmail'] = 1;
        // Redirect to success.php
        header("location: success.php");
    }
}
// 2.2 If email and hash values aren't set or is empty 
else {
    // Update session message: Invalid parameters provided for verification
    $_SESSION['message'] = "Invalid parameters provided for account verification!";
    // Redirect to error.php
    header("location: error.php");
}     
?>