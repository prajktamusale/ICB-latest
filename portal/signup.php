<?php
/*
 Title: This page provides UI for users to sign up into platform.
 Backend
 Dependencides:
 - config.php
 - phpMailer
*/
    require 'config.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    // 1. Session start
    session_start();

    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        $newUserFirstName = $mysqli->escape_string($_POST['firstName']);
        $newUserLastName = $mysqli->escape_string($_POST['lastName']);
        $newUsername = $_POST['firstName'].$_POST['lastName'];
        $newUserFullName = $_POST['firstName']." ".$_POST['lastName'];
        $newUserEmail = $mysqli->escape_string($_POST['email']);
        $newUserMobile = $mysqli->escape_string($_POST['mobile']);
    
        $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
        $hash = $mysqli->escape_string(hash('sha512', md5(rand(99,9999))));
        date_default_timezone_set("Asia/Kolkata");
        $newUserRegistrationDate = date("Y-m-d");
        $newUserExpirationDate = date("Y-m-d", strtotime($newUserRegistrationDate."+365 days"));

        $result = $mysqli->query("SELECT * FROM users WHERE email='$newUserEmail'") or die($mysqli->error());
        if ( $result->num_rows > 0 ) {
            $_SESSION['message'] = 'User with this email already exists!';
            header("location: error.php");
        }
        else {
            $sql_a = "INSERT INTO users (username, type, email, password, hash, firstName, lastName, registrationDate, expirationDate, profilepic, bio, mobile)" 
                . "VALUES ('$newUsername','volunteer','$newUserEmail','$password', '$hash', '$newUserFirstName', '$newUserLastName', '$newUserRegistrationDate', '$newUserExpirationDate', './images/defaultprofile.png', 'Welcome!', '$newUserMobile')";
                // $sql_b = "INSERT INTO payment (registrationDate, userEmail, order_id, payment_id, amount_paid) VALUES ('$newUserRegistrationDate', '$newUserEmail', '$razorpay_order_id', '$razorpay_payment_id', '$amount')";
                // 2. stats table
                $sql_b = "INSERT INTO stats (userEmail, username, type) VALUES ('$newUserEmail', '$newUsername', 'volunteer')";
            $mysqli->query($sql_a);
            $mysqli->query($sql_b);
            // if($mysqli->query($sql_a) && $mysqli->query($sql_b)){
            //     $mail = new PHPMailer(true);
            //     // SMTP settings
            //     $mail->isSMTP();
            //     $mail->Host = 'smtp.gmail.com';
            //     $mail->SMTPAuth = true;
            //     $mail->Username = 'i@careforbharat.in';
            //     $mail->Password = 'qukulbddgdabdnrl';
            //     $mail->SMTPSecure = 'TLS';
            //     $mail->Port = '587';

            //     // Email settings
            //     $mail->setFrom('i@careforbharat.in', 'Care for Bharat');
            //     $mail->addAddress($newUserEmail, $newUserFullName);
            //     // $mail->addAddress('ellen@example.com');
            //     $mail->addReplyTo('i@careforbharat.in', 'Care for Bharat');
            //     // $mail->addCC('cc@example.com');
            //     // $mail->addBCC('');
            //     // $mail->addAttachment($path);
            //     // $mail->addAttachment($offerletter, 'new.jpg'); 
            //     $mail->isHTML(true);

            //     $mail->Subject = 'Please Verify your Email | careforbharat.in';
            //     $mail->Body    = "
                
            //     <!DOCTYPE html>
            //     <html>

            //     <head>
            //         <title></title>
            //         <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
            //         <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
            //         <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />
            //         <style type=\"text/css\">
            //             @media screen {
            //                 @font-face {
            //                     font-family: 'Lato';
            //                     font-style: normal;
            //                     font-weight: 400;
            //                     src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            //                 }

            //                 @font-face {
            //                     font-family: 'Lato';
            //                     font-style: normal;
            //                     font-weight: 700;
            //                     src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
            //                 }

            //                 @font-face {
            //                     font-family: 'Lato';
            //                     font-style: italic;
            //                     font-weight: 400;
            //                     src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
            //                 }

            //                 @font-face {
            //                     font-family: 'Lato';
            //                     font-style: italic;
            //                     font-weight: 700;
            //                     src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
            //                 }
            //             }

            //             /* CLIENT-SPECIFIC STYLES */
            //             body,
            //             table,
            //             td,
            //             a {
            //                 -webkit-text-size-adjust: 100%;
            //                 -ms-text-size-adjust: 100%;
            //             }

            //             table,
            //             td {
            //                 mso-table-lspace: 0pt;
            //                 mso-table-rspace: 0pt;
            //             }

            //             img {
            //                 -ms-interpolation-mode: bicubic;
            //             }

            //             /* RESET STYLES */
            //             img {
            //                 border: 0;
            //                 height: auto;
            //                 line-height: 100%;
            //                 outline: none;
            //                 text-decoration: none;
            //             }

            //             table {
            //                 border-collapse: collapse !important;
            //             }

            //             body {
            //                 height: 100% !important;
            //                 margin: 0 !important;
            //                 padding: 0 !important;
            //                 width: 100% !important;
            //             }

            //             /* iOS BLUE LINKS */
            //             a[x-apple-data-detectors] {
            //                 color: inherit !important;
            //                 text-decoration: none !important;
            //                 font-size: inherit !important;
            //                 font-family: inherit !important;
            //                 font-weight: inherit !important;
            //                 line-height: inherit !important;
            //             }

            //             /* MOBILE STYLES */
            //             @media screen and (max-width:600px) {
            //                 h1 {
            //                     font-size: 32px !important;
            //                     line-height: 32px !important;
            //                 }
            //             }

            //             /* ANDROID CENTER FIX */
            //             div[style*=\"margin: 16px 0;\"] {
            //                 margin: 0 !important;
            //             }
            //         </style>
            //     </head>

            //     <body style=\"background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;\">
            //         <!-- HIDDEN PREHEADER TEXT -->
            //         <div style=\"display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;\"> We're thrilled to have you here! Get ready to dive into your new account. </div>
            //         <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
            //             <!-- LOGO -->
            //             <tr>
            //                 <td bgcolor=\"#fc8955\" align=\"center\">
            //                     <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
            //                         <tr>
            //                             <td align=\"center\" valign=\"top\" style=\"padding: 40px 10px 40px 10px;\"> </td>
            //                         </tr>
            //                     </table>
            //                 </td>
            //             </tr>
            //             <tr>
            //                 <td bgcolor=\"#fc8955\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">
            //                     <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"center\" valign=\"top\" style=\"padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;\">
            //                                 <h1 style=\"font-size: 48px; font-weight: 400; margin: 1rem; padding-bottom: 0px;\">Welcome!</h1> 
            //                                 <p style=\"margin: 0; padding-bottom: 5px; font-size: 35px;\">".$newUserFirstName."</p>
            //                                 <img src=\" https://img.icons8.com/clouds/100/000000/handshake.png\" width=\"125\" height=\"120\" style=\"display: block; border: 0px;\" />
            //                             </td>
            //                         </tr>
            //                     </table>
            //                 </td>
            //             </tr>
            //             <tr>
            //                 <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">
            //                     <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"left\" style=\"justify-content: center; padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
            //                                 <p style=\"margin: 0; text-align: center;\">We're excited to have you get started. First, you need to confirm your account. Just press the button below.</p>
            //                             </td>
            //                         </tr>
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"left\">
            //                                 <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            //                                     <tr>
            //                                         <td bgcolor=\"#ffffff\" align=\"center\" style=\"padding: 20px 30px 60px 30px;\">
            //                                             <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            //                                                 <tr>
            //                                                     <td align=\"center\" style=\"border-radius: 3px;\" bgcolor=\"#fc8955\"><a href=\"https://careforbharat.in/portal/verify.php?email=".$newUserEmail."&hash=".$hash."\" target=\"_blank\" style=\"font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;\">Confirm Account</a></td>
            //                                                 </tr>
            //                                             </table>
            //                                         </td>
            //                                     </tr>
            //                                 </table>
            //                             </td>
            //                         </tr>
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 0px 30px 0px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
            //                                 <p style=\"margin: 0;\">If that doesn't work, then click on the following link:</p>
            //                             </td>
            //                         </tr>
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"left\" style=\"justify-content: center; padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
            //                                 <p style=\"margin: 0; text-align: center;\"><a href=\"https://careforbharat.in/portal/verify.php?email=".$newUserEmail."&hash=".$hash."\" target=\"_blank\" style=\"color: #fc8955;\">https://careforbharat.in/verifyEmail</a></p>
            //                             </td>
            //                         </tr>
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 0px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
            //                                 <p style=\"margin: 0;\">If you have any questions, then please email us at <a href=\"mailto:i@careforbharat.in\" target=\"_blank\" style=\"color: #777777;\">i@careforbharat.in</a> or call us at <a href=\"tel:8421776790\" target=\"_blank\" style=\"color: #777777;\">(+91) 8421776790</a> Mon-Fri 10am-6pm, we're always happy to help out.</p>
            //                             </td>
            //                         </tr>
            //                         <tr>
            //                             <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
            //                                 <p style=\"margin: 0;\">Cheers</p>
            //                             </td>
            //                         </tr>
            //                     </table>
            //                 </td>
            //             </tr>
            //             <tr>
            //                 <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 30px 10px 0px 10px;\">
            //                     <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
            //                         <tr>
            //                             <td bgcolor=\"#FFECD1\" align=\"center\" style=\"padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
            //                                 <h2 style=\"font-size: 20px; font-weight: 400; color: #111111; margin: 0;\">Need more help?</h2>
            //                                 <p style=\"margin: 0;\"><a href=\"https://careforbharat.in\" target=\"_blank\" style=\"color: #FFA73B;\">We&rsquo;re here to help you out</a></p>
            //                             </td>
            //                         </tr>
            //                     </table>
            //                 </td>
            //             </tr>
            //             <tr>
            //                 <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">
            //                     <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
            //                         <tr>
            //                             <td bgcolor=\"#f4f4f4\" align=\"left\" style=\"padding: 0px 30px 30px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;\"> <br>
            //                                 <!-- <p style=\"margin: 0;\">If these emails get annoying, please feel free to <a href=\"#\" target=\"_blank\" style=\"color: #111111; font-weight: 700;\">unsubscribe</a>.</p> -->
            //                             </td>
            //                         </tr>
            //                     </table>
            //                 </td>
            //             </tr>
            //         </table>
            //     </body>

            //     </html>
                
            //     ";
                
            //     $mail->AltBody = 'Welcome! '.$newUserFirstName.',<br/>We&rsquo;re excited to have you get started. First, you need to confirm your account. Just click the link given below.<br/>https://careforbharat.in/portal/verify.php?email='.$newUserEmail.'<br/><br/>If you have any questions, then please email us at <a href=\"mailto:i@careforbharat.in\" target=\"_blank\" style=\"color: #777777;\">i@careforbharat.in</a> or call us at <a href=\"tel:8421776790\" target=\"_blank\" style=\"color: #777777;\">(+91) 8421776790</a> Mon-Fri 10am-6pm, we&rsquo;re always happy to help out.';
            //     if ($mail->send()) {
            //         // echo "<script>alert('Message could not be sent! Mailer Error: ".$mail->ErrorInfo." !');</script>";
            //         // echo "<script>alert('Mailer Error: ' . $mail->ErrorInfo . ' !');</script>";
            //         header("Location: index.php");
            //         // exit();
            //     }
            // }
        }
    }
}


?>

<!-- Front End -->
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- main css -->
    <link rel="stylesheet" type="text/css" href="./css/login.css">
    <title>Signup | Member</title>
</head>
<body>
    <div class="container">
        <!-- <p class="login-text">Signup with Social Account</p>
        <div class="login-social">
            <a href="#" class="facebook"><i class="fa fa-facebook"></i>Facebook</a>
            <a href="#" class="twitter"><i class="fa fa-twitter"></i>Twitter</a>
            <a href="#" class="google-plus"><i class="fa fa-google-plus"></i>Google+</a>
            <a href="#" class="linkedin"><i class="fa fa-linkedin"></i>Linkedin</a>
        </div> -->
        <!-- 
            Form to accept user details. 
            - Action will be forwarded to signup.php page.
            - method: POST

            #### Accepts:
            |Name of value| name |
            |---|---|----|
            |First Name|firstName|
            |Last Name|lastName|
            |Email|email|
            |Password|password|

            #### Button
            Sign-UP: signup 
        -->
        <form action="./signup.php" method="POST" class="login-email">
            <p class='login-text'>Sign-Up</p>
            <div class="input-group">
                <input type="text" placeholder="First Name" name="firstName" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="text" placeholder="Last Name" name="lastName" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="email" placeholder="Email" name="email" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="tel" placeholder="Phone Number(10 digits)" name="mobile" autocomplete="off" pattern="[0-9]{10}"required>
            </div>
            <input type="hidden" id="fee" name="fee" value="100">
            <div class="input-group">
                <button class="btn rzp-button1" name="signup">Sign-Up</button>
            </div>
        </form>
    </div>
</body>
</html>