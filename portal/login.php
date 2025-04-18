<?php
/* Title: User login process, checks if user exists and password is correct */

// 1. Email ID Filtering
// 1.1 Escape email to protect against SQL injections
$email = $mysqli->escape_string($_POST['email']); 
// 1.2 Loading user details from database.
$result = $mysqli->query("SELECT * FROM users WHERE email='$email' ");

// 2. Checking if user Exist
/* 2.1 If user doesn't exist
   User rows fetched from database is equal to zero
*/
if ( $result->num_rows == 0 ){ // User doesn't exist
    // message to be displayed to user is updated and added to the session variable
    $_SESSION['message'] = "User with that email doesn't exist!";
    //This line sends a HTTP header to the browser with "Location" header specifying the URL to redirect, which is error.php
    header("location: error.php");
}
// 2.2 User Exists
else { // User exists

    // 2.2.1 Here we fetch a single row from the users using fetch_assoc method and store it to user variable.
    $user = $result->fetch_assoc();

    //2.2.2 Password verficiation
    //The password_verify() function returns true if the plaintext password matches the hashed password, and false otherwise.
    if ( password_verify($_POST['password'], $user['password']) ) {
        // On successful verification user details are stored into session variables.
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['type'] = $user['type'];
        $_SESSION['first_name'] = $user['firstName'];
        $_SESSION['last_name'] = $user['lastName'];
        $_SESSION['full_name'] = $user['firstName']." ".$user['lastName'];
        $_SESSION['registration_date'] = $user['registrationDate'];
        $_SESSION['expiration_date'] = $user['expirationDate'];
        $_SESSION['profile'] = $user['profilepic'];
        $_SESSION['bio'] = $user['bio'];
        $_SESSION['mobile'] = $user['mobile'];
        $_SESSION['instagram'] = $user['instagram'];
        $_SESSION['telegram'] = $user['telegram'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['userTeam'] = $user['team'];
        $_SESSION['empPosition'] = $user['empPosition'];
        
        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;

        // This is to check if the user has provided all the necessary information regarding account.
        // If the user has any field empty, then user is redirected to the signupDetail.php page.
        // || $user['pan']==NULL
        if($user['mobile']=='0' || empty($user['gender']) || empty($user['address']) || empty($user['city']) || empty($user['state']) || $user['pin']=='0' || $user['aadhaar']==NULL ){
            // echo "<div> </div>"
            header("location: signupDetail.php");
        }
        // If all details exist. Redirect to home.
        else{
            header("location: home.php");
        }
    }
    // If user entered incorrect password.
    else {
        // Message to be displayed is updated for the session.
        $_SESSION['message'] = "You have entered wrong password, try again!";
        // Redirected to error.php page.
        header("location: error.php");
    }
}