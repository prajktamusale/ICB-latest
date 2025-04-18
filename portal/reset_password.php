<?php
/* Title: Password reset process, updates database with new user password */
// Dependencies: config.php file
require './config.php';
// 1. Starting session.
session_start();

// 2. Make sure the form is being submitted with method="post"
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    // 2.1 Make sure the two passwords match
    if ( $_POST['newpassword'] == $_POST['confirmpassword'] ) { 
        // 2.1.1 Hashing of new password
        $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);
        
        // 2.1.2 We get $_POST['email'] and $_POST['hash'] from the hidden input field of reset.php form
        $email = $mysqli->escape_string($_POST['email']);
        $hash = $mysqli->escape_string($_POST['hash']);
        
        // 2.1.3 SQL query for updating.
        $sql = "UPDATE users SET password='$new_password', hash='$hash' WHERE email='$email'";

        // 2.1.4 Execute query and return execution status
        if ( $mysqli->query($sql) ) {
            // Session Message updated: Successfully updated password
        $_SESSION['message'] = "Your password has been reset successfully!";
        // Redirect to success.php page.
        header("location: success.php");    

        }

    }
    // 2.2 If new password and confirm password doesn't match
    else {
        // Session Message updated: new password and confirm password don't match.
        $_SESSION['message'] = "Two passwords you entered don't match, try again!";
        // Redirect to error.php
        header("location: error.php");    
    }

}
?>