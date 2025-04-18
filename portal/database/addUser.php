<?php
    include '../config.php';
    require '../PHPMailer/PHPMailerAutoload.php';
    
    $newUserFirstName = $mysqli->escape_string($_POST['firstName']);
    $newUserLastName = $mysqli->escape_string($_POST['lastName']);
    $newUsername = $_POST['firstName'].$_POST['lastName'];
    $newUserFullName = $_POST['firstName']." ".$_POST['lastName'];
    $newUserEmail = $mysqli->escape_string($_POST['email']);
    $newUserDOB = date('Y-m-d', strtotime($_POST['dob']));
    $newUserGender = $mysqli->escape_string($_POST['gender']);
    $newUserAddress = $mysqli->escape_string($_POST['address']);
    $newUserCity = $mysqli->escape_string($_POST['city']);
    $newUserState = $mysqli->escape_string($_POST['state']);
    $newUserPIN = $mysqli->escape_string($_POST['pin']);
    $newUserMobile = $mysqli->escape_string($_POST['mobile']);
    $newUserTelegram = $_POST['telegram'];
    $newUserInstagram = $_POST['instagram'];
    $newUserAadhaar = $_POST['aadhaar'];
    $newUserPAN = $_POST['pan'];
    $newUserType = $mysqli->escape_string($_POST['userType']);
    $newUserInitiatives = "";
    $newUserInterests = "";
    $newUserCollege = "";

    // if(isset($_POST['studentSave'])){
    //     if(isset($_POST['aadhaar'])){
    //         $newUserAadhaar = $mysqli->escape_string($_POST['aadhaar']);
    //     }
    //     if(isset($_POST['pan'])){
    //         $newUserPAN = $mysqli->escape_string($_POST['pan']);
    //     }
    // }

    // if(isset($_POST['memberSave'])){
    //     if(isset($_POST['aadhaar'])){
    //         $newUserCollege = $_POST[''];
    //     }
    // }

    if(isset($_POST['memberSave']) || isset($_POST['studentSave'])){
        if(isset($_POST['contriMissionShiksha'])){
            $newUserInitiatives = $newUserInitiatives."MissionShiksha;";
        }
        if(isset($_POST['contriMentalHealth'])){
            $newUserInitiatives = $newUserInitiatives."MentalHealth;";
        }
        if(isset($_POST['contriAnimalSafety'])){
            $newUserInitiatives = $newUserInitiatives."AnimalSafety;";
        }
        if(isset($_POST['contriArtandCraft'])){
            $newUserInitiatives = $newUserInitiatives."ArtandCraft;";
        }
        if(isset($_POST['contriEnvironment'])){
            $newUserInitiatives = $newUserInitiatives."Environment;";
        }
        if(isset($_POST['contriSexEducation'])){
            $newUserInitiatives = $newUserInitiatives."SexEducation;";
        }
        
        if(isset($_POST['interestPublicRelation'])){
            $newUserInterests = $newUserInterests."PublicRelation;";
        }
        if(isset($_POST['interestSpeakingandCommunication'])){
            $newUserInterests = $newUserInterests."SpeakingandCommunication;";
        }
        if(isset($_POST['interestOperations'])){
            $newUserInterests = $newUserInterests."Operations;";
        }
        if(isset($_POST['interestSocialMediaManager'])){
            $newUserInterests = $newUserInterests."SocialMediaManager;";
        }
        if(isset($_POST['interestGraphicDesigner'])){
            $newUserInterests = $newUserInterests."GraphicDesigner;";
        }
        if(isset($_POST['interestContentWriter'])){
            $newUserInterests = $newUserInterests."ContentWriter;";
        }
        if(isset($_POST['interestAdminBodyManagement'])){
            $newUserInterests = $newUserInterests."AdminBodyManagement;";
        }
        if(isset($_POST['interestLegalandFinance'])){
            $newUserInterests = $newUserInterests."LegalandFinance;";
        }
    }

    date_default_timezone_set("Asia/Kolkata");
    $newUserRegistrationDate = date("Y-m-d");
    $newUserExpirationDate = date("Y-m-d", strtotime($newUserRegistrationDate."+365 days"));
    $password = $mysqli->escape_string(password_hash($newUserEmail, PASSWORD_BCRYPT));
    $hash = $mysqli->escape_string(hash('sha512', md5(rand(99,9999))));
    $sql_a = "INSERT INTO users (username, type, email, password, hash, firstName, lastName, registrationDate, expirationDate, profilepic, bio, college, dob, gender, address, city, state, pin, mobile, telegram, instagram, initiatives, interests, aadhaar, pan)" 
        . "VALUES ('$newUsername','$newUserType','$newUserEmail','$password', '$hash', '$newUserFirstName', '$newUserLastName', '$newUserRegistrationDate', '$newUserExpirationDate', './images/defaultprofile.png', 'Welcome!', '$newUserCollege', '$newUserDOB', '$newUserGender', '$newUserAddress', '$newUserCity', '$newUserState', '$newUserPIN', '$newUserMobile', '$newUserTelegram', '$newUserInstagram', '$newUserInitiatives', '$newUserInterests', '$newUserAadhaar', '$newUserPAN')";
    // $sql_b = "INSERT INTO payment (registrationDate, userEmail, order_id, payment_id, amount_paid) VALUES ('$newUserRegistrationDate', '$newUserEmail', '$razorpay_order_id', '$razorpay_payment_id', '$amount')";
    $sql_b = "INSERT INTO stats (userEmail, username, type) VALUES ('$newUserEmail', '$newUsername', '$newUserType')";

    if($mysqli->query($sql_a) && $mysqli->query($sql_b)){
        $mail = new PHPMailer;
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'i@careforbharat.in';
        $mail->Password = 'Bharat@Buzz#TY2';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = '465';

        // Email settings
        $mail->setFrom('i@careforbharat.in', 'Care for Bharat');
        $mail->addAddress($newUserEmail, $newUserFullName);
        // $mail->addAddress('ellen@example.com');
        $mail->addReplyTo('i@careforbharat.in', 'Care for Bharat');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('');
        // $mail->addAttachment($path);
        // $mail->addAttachment($offerletter, 'new.jpg');
        $mail->isHTML(true);

        $mail->Subject = 'Please Verify your Email | careforbharat.in';
        $mail->Body    = "
        
        <!DOCTYPE html>
        <html>

        <head>
            <title></title>
            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />
            <style type=\"text/css\">
                @media screen {
                    @font-face {
                        font-family: 'Lato';
                        font-style: normal;
                        font-weight: 400;
                        src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
                    }

                    @font-face {
                        font-family: 'Lato';
                        font-style: normal;
                        font-weight: 700;
                        src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
                    }

                    @font-face {
                        font-family: 'Lato';
                        font-style: italic;
                        font-weight: 400;
                        src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
                    }

                    @font-face {
                        font-family: 'Lato';
                        font-style: italic;
                        font-weight: 700;
                        src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
                    }
                }

                /* CLIENT-SPECIFIC STYLES */
                body,
                table,
                td,
                a {
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                }

                table,
                td {
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                }

                img {
                    -ms-interpolation-mode: bicubic;
                }

                /* RESET STYLES */
                img {
                    border: 0;
                    height: auto;
                    line-height: 100%;
                    outline: none;
                    text-decoration: none;
                }

                table {
                    border-collapse: collapse !important;
                }

                body {
                    height: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 100% !important;
                }

                /* iOS BLUE LINKS */
                a[x-apple-data-detectors] {
                    color: inherit !important;
                    text-decoration: none !important;
                    font-size: inherit !important;
                    font-family: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                }

                /* MOBILE STYLES */
                @media screen and (max-width:600px) {
                    h1 {
                        font-size: 32px !important;
                        line-height: 32px !important;
                    }
                }

                /* ANDROID CENTER FIX */
                div[style*=\"margin: 16px 0;\"] {
                    margin: 0 !important;
                }
            </style>
        </head>

        <body style=\"background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;\">
            <!-- HIDDEN PREHEADER TEXT -->
            <div style=\"display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;\"> We're thrilled to have you here! Get ready to dive into your new account. </div>
            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                <!-- LOGO -->
                <tr>
                    <td bgcolor=\"#fc8955\" align=\"center\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
                            <tr>
                                <td align=\"center\" valign=\"top\" style=\"padding: 40px 10px 40px 10px;\"> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor=\"#fc8955\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"center\" valign=\"top\" style=\"padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;\">
                                    <h1 style=\"font-size: 48px; font-weight: 400; margin: 1rem; padding-bottom: 0px;\">Welcome!</h1> 
                                    <p style=\"margin: 0; padding-bottom: 5px; font-size: 35px;\">".$newUserFirstName."</p>
                                    <img src=\" https://img.icons8.com/clouds/100/000000/handshake.png\" width=\"125\" height=\"120\" style=\"display: block; border: 0px;\" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"left\" style=\"justify-content: center; padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
                                    <p style=\"margin: 0; text-align: center;\">We're excited to have you get started. First, you need to confirm your account. Just press the button below.</p>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"left\">
                                    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                        <tr>
                                            <td bgcolor=\"#ffffff\" align=\"center\" style=\"padding: 20px 30px 60px 30px;\">
                                                <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                    <tr>
                                                        <td align=\"center\" style=\"border-radius: 3px;\" bgcolor=\"#fc8955\"><a href=\"https://careforbharat.in/portal/verify.php?email=".$newUserEmail."&hash=".$hash."\" target=\"_blank\" style=\"font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;\">Confirm Account</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 0px 30px 0px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
                                    <p style=\"margin: 0;\">If that doesn't work, then click on the following link:</p>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"left\" style=\"justify-content: center; padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
                                    <p style=\"margin: 0; text-align: center;\"><a href=\"https://careforbharat.in/portal/verify.php?email=".$newUserEmail."&hash=".$hash."\" target=\"_blank\" style=\"color: #fc8955;\">https://careforbharat.in/verifyEmail</a></p>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 0px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
                                    <p style=\"margin: 0;\">If you have any questions, then please email us at <a href=\"mailto:i@careforbharat.in\" target=\"_blank\" style=\"color: #777777;\">i@careforbharat.in</a> or call us at <a href=\"tel:8421776790\" target=\"_blank\" style=\"color: #777777;\">(+91) 8421776790</a> Mon-Fri 10am-6pm, we're always happy to help out.</p>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
                                    <p style=\"margin: 0;\">Cheers</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 30px 10px 0px 10px;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
                            <tr>
                                <td bgcolor=\"#FFECD1\" align=\"center\" style=\"padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">
                                    <h2 style=\"font-size: 20px; font-weight: 400; color: #111111; margin: 0;\">Need more help?</h2>
                                    <p style=\"margin: 0;\"><a href=\"https://careforbharat.in\" target=\"_blank\" style=\"color: #FFA73B;\">We&rsquo;re here to help you out</a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">
                            <tr>
                                <td bgcolor=\"#f4f4f4\" align=\"left\" style=\"padding: 0px 30px 30px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;\"> <br>
                                    <!-- <p style=\"margin: 0;\">If these emails get annoying, please feel free to <a href=\"#\" target=\"_blank\" style=\"color: #111111; font-weight: 700;\">unsubscribe</a>.</p> -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>

        </html>
        
        ";
        $mail->AltBody = 'Welcome! '.$newUserFirstName.',<br/>We&rsquo;re excited to have you get started. First, you need to confirm your account. Just click the link given below.<br/>https://careforbharat.in/portal/verify.php?email='.$newUserEmail.'&hash='.$hash.'<br/><br/>If you have any questions, then please email us at <a href=\"mailto:i@careforbharat.in\" target=\"_blank\" style=\"color: #777777;\">i@careforbharat.in</a> or call us at <a href=\"tel:8421776790\" target=\"_blank\" style=\"color: #777777;\">(+91) 8421776790</a> Mon-Fri 10am-6pm, we&rsquo;re always happy to help out.';

        if (!$mail->send()) {
            echo "<script>alert('Message could not be sent! Mailer Error: ".$mail->ErrorInfo." !');</script>";
        } else {
            // echo "<script>alert('Message has been sent! to Recipient');</script>";
            // $_SESSION['message'] = "Please check your email and follow the instructions to reset your password!";
            // header("Location: success.php");
            // exit();
        }

        $mail = new PHPMailer;
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'i@careforbharat.in';
        $mail->Password = 'Bharat@Buzz#TY2';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = '465';

        // Email settings
        $mail->setFrom('i@careforbharat.in', 'Care for Bharat');
        $mail->addAddress($newUserEmail, $newUserFullName);
        // $mail->addAddress('ellen@example.com');
        $mail->addReplyTo('i@careforbharat.in', 'Care for Bharat');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('');
        // $mail->addAttachment($path);
        // $mail->addAttachment($offerletter, 'new.jpg'); 
        $mail->isHTML(true);

        $mail->Subject = 'Generate Credentials | careforbharat.in';
        $mail->Body    = "
        
        <!DOCTYPE html PUBLIC \-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\" style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0\">
        <head> 
        <meta charset=\"UTF-8\"> 
        <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
        <meta name=\"x-apple-disable-message-reformatting\"> 
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
        <meta content=\"telephone=no\" name=\"format-detection\"> 
        <title>Credential Generate</title> 
        <!--[if (mso 16)]>
            <style type=\"text/css\">
            a {text-decoration: none;}
            </style>
            <![endif]--> 
        <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--> 
        <!--[if gte mso 9]>
        <xml>
            <o:OfficeDocumentSettings>
            <o:AllowPNG></o:AllowPNG>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
        <![endif]--> 
        <style type=\"text/css\">
        #outlook a {
            padding:0;
        }
        .ExternalClass {
            width:100%;
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height:100%;
        }
        .es-button {
            mso-style-priority:100!important;
            text-decoration:none!important;
        }
        a[x-apple-data-detectors] {
            color:inherit!important;
            text-decoration:none!important;
            font-size:inherit!important;
            font-family:inherit!important;
            font-weight:inherit!important;
            line-height:inherit!important;
        }
        .es-desk-hidden {
            display:none;
            float:left;
            overflow:hidden;
            width:0;
            max-height:0;
            line-height:0;
            mso-hide:all;
        }
        .es-button-border:hover a.es-button, .es-button-border:hover button.es-button {
            background:#ffffff!important;
            border-color:#ffffff!important;
        }
        .es-button-border:hover {
            background:#ffffff!important;
            border-style:solid solid solid solid!important;
            border-color:#3d5ca3 #3d5ca3 #3d5ca3 #3d5ca3!important;
        }
        [data-ogsb] .es-button {
            border-width:0!important;
            padding:15px 20px 15px 20px!important;
        }
        @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1 { font-size:20px!important; text-align:center; line-height:120%!important } h2 { font-size:16px!important; text-align:left; line-height:120%!important } h3 { font-size:20px!important; text-align:center; line-height:120%!important } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:20px!important } h2 a { text-align:left } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:16px!important } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important } .es-menu td a { font-size:14px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:10px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:16px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:12px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:14px!important; display:block!important; border-left-width:0px!important; border-right-width:0px!important } .es-btn-fw { border-width:10px 0px!important; text-align:center!important } .es-adaptive table, .es-btn-fw, .es-btn-fw-brdr, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0!important } .es-m-p0r { padding-right:0!important } .es-m-p0l { padding-left:0!important } .es-m-p0t { padding-top:0!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-m-p5 { padding:5px!important } .es-m-p5t { padding-top:5px!important } .es-m-p5b { padding-bottom:5px!important } .es-m-p5r { padding-right:5px!important } .es-m-p5l { padding-left:5px!important } .es-m-p10 { padding:10px!important } .es-m-p10t { padding-top:10px!important } .es-m-p10b { padding-bottom:10px!important } .es-m-p10r { padding-right:10px!important } .es-m-p10l { padding-left:10px!important } .es-m-p15 { padding:15px!important } .es-m-p15t { padding-top:15px!important } .es-m-p15b { padding-bottom:15px!important } .es-m-p15r { padding-right:15px!important } .es-m-p15l { padding-left:15px!important } .es-m-p20 { padding:20px!important } .es-m-p20t { padding-top:20px!important } .es-m-p20r { padding-right:20px!important } .es-m-p20l { padding-left:20px!important } .es-m-p25 { padding:25px!important } .es-m-p25t { padding-top:25px!important } .es-m-p25b { padding-bottom:25px!important } .es-m-p25r { padding-right:25px!important } .es-m-p25l { padding-left:25px!important } .es-m-p30 { padding:30px!important } .es-m-p30t { padding-top:30px!important } .es-m-p30b { padding-bottom:30px!important } .es-m-p30r { padding-right:30px!important } .es-m-p30l { padding-left:30px!important } .es-m-p35 { padding:35px!important } .es-m-p35t { padding-top:35px!important } .es-m-p35b { padding-bottom:35px!important } .es-m-p35r { padding-right:35px!important } .es-m-p35l { padding-left:35px!important } .es-m-p40 { padding:40px!important } .es-m-p40t { padding-top:40px!important } .es-m-p40b { padding-bottom:40px!important } .es-m-p40r { padding-right:40px!important } .es-m-p40l { padding-left:40px!important } }
        </style> 
        </head> 
        <body style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0\"> 
        <div class=\"es-wrapper-color\" style=\"background-color:#FAFAFA\"> 
        <!--[if gte mso 9]>
                    <v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
                        <v:fill type=\"tile\" color=\"#fafafa\"></v:fill>
                    </v:background>
                <![endif]--> 
        <table class=\"es-wrapper\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top\"> 
            <tr style=\"border-collapse:collapse\"> 
            <td valign=\"top\" style=\"padding:0;Margin:0\"> 
            <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-header\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td class=\"es-adaptive\" align=\"center\" style=\"padding:0;Margin:0\"> 
                <table class=\"es-header-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#333333;width:600px\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#333333\" align=\"center\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;background-color:#b8e3ff\" bgcolor=\"#090808\" align=\"left\"> 
                    <!--[if mso]><table style=\"width:560px\" cellpadding=\"0\" 
                                cellspacing=\"0\"><tr><td style=\"width:270px\" valign=\"top\"><![endif]--> 
                    <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td class=\"es-m-p20b\" align=\"left\" style=\"padding:0;Margin:0;width:270px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-p0l es-m-txt-c\" align=\"left\" style=\"padding:0;Margin:0;font-size:0px\"><a href=\"https://careforbharat.in\" target=\"_blank\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#1376C8;font-size:14px\"><img src=\"https://careforbharat.in/icb_logo.png\" alt=\"I Care for Bharat Logo\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\" title=\"I Care for Bharat Logo\" width=\"111\"></a></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table> 
                    <!--[if mso]></td><td style=\"width:20px\"></td><td style=\"width:270px\" valign=\"top\"><![endif]--> 
                    <table class=\"es-right\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td align=\"left\" style=\"padding:0;Margin:0;width:270px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr class=\"es-mobile-hidden\" style=\"border-collapse:collapse\"> 
                            <td align=\"right\" class=\"es-m-txt-c es-m-p5\" style=\"padding:15px;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:30px;color:#000000;font-size:20px\"><strong>Credentials Generate</strong></p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table> 
                    <!--[if mso]></td></tr></table><![endif]--></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table> 
            <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td style=\"padding:0;Margin:0;background-color:#fafafa\" bgcolor=\"#fafafa\" align=\"center\"> 
                <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;background-color:transparent;background-position:left top\" bgcolor=\"transparent\" align=\"left\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:560px\"> 
                        <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-position:left top\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0\"><img src=\"https://pvuwmt.stripocdn.email/content/guids/CABINET_dd354a98a803b60e2f0411e893c82f56/images/23891556799905703.png\" alt=\"Unlocking Lock with Key\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;font-size:12px\" width=\"175\" title=\"Unlocking Lock with Key\"></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-top:15px;padding-bottom:15px\"><h1 style=\"Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333\"><strong style=\"background-color:transparent\">GENERATE CREDENTIALS?</strong></h1></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-left:40px;padding-right:40px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;font-size:16px\">Hi,&nbsp;".$newUserFullName."</p></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-right:35px;padding-left:40px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;font-size:16px\">We request you to generate&nbsp;your password! to get access to your&nbsp;account.</p></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-top:25px;padding-left:40px;padding-right:40px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;font-size:16px\">If did not make this request, just ignore this email. Otherwise, please click the button below to generate your password:</p></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"Margin:0;padding-left:10px;padding-right:10px;padding-top:40px;padding-bottom:40px\"><span class=\"es-button-border\" style=\"border-style:solid;border-color:#3D5CA3;background:#FFFFFF;border-width:2px;display:inline-block;border-radius:10px;width:auto\"><a href=\"https://careforbharat.in/portal/reset.php?email=".$newUserEmail."&hash=".$hash."\" class=\"es-button\" target=\"_blank\" style=\"mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#3D5CA3;font-size:14px;border-style:solid;border-color:#FFFFFF;border-width:15px 20px 15px 20px;display:inline-block;background:#FFFFFF;border-radius:10px;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-weight:bold;font-style:normal;line-height:17px;width:auto;text-align:center\">GENERATE PASSWORD</a></span></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:40px;padding-right:40px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;font-size:16px\">If that doesn't work, then please click&nbsp;the following link:&nbsp;<br><a href=\"https://careforbharat.in/portal/reset.php?email=".$newUserEmail."&hash=".$hash."\" target=\"_blank\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#0B5394;font-size:16px\">https://careforbharat.in/generateCredential</a></p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table></td> 
                    </tr> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td align=\"left\" style=\"padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:20px\"> 
                    <!--[if mso]><table style=\"width:580px\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"width:242px\" valign=\"top\"><![endif]--> 
                    <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td align=\"left\" style=\"padding:0;Margin:0;width:242px\"> 
                        <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-position:center center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-txt-c\" align=\"right\" style=\"padding:0;Margin:0;padding-top:15px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#666666;font-size:16px\"><strong>Follow us:</strong></p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table> 
                    <!--[if mso]></td><td style=\"width:20px\"></td><td style=\"width:318px\" valign=\"top\"><![endif]--> 
                    <table class=\"es-right\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td align=\"left\" style=\"padding:0;Margin:0;width:318px\"> 
                        <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-position:center center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-txt-c\" align=\"left\" style=\"padding:0;Margin:0;padding-bottom:5px;padding-top:10px;font-size:0\"> 
                            <table class=\"es-table-not-adapt es-social\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                                <tr style=\"border-collapse:collapse\"> 
                                <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;padding-right:10px\"><a target=\"_blank\" href=\"https://www.facebook.com/login/?next=https%3A%2F%2Fwww.facebook.com%2FAlways-Sahi-Solutions-Private-Limited-100805488788703\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#0B5394;font-size:16px\"><img src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/circle-colored/facebook-circle-colored.png\" alt=\"Fb\" title=\"Facebook\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;padding-right:10px\"><a target=\"_blank\" href=\"https://www.instagram.com/alwayssahi\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#0B5394;font-size:16px\"><img src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/circle-colored/instagram-circle-colored.png\" alt=\"Ig\" title=\"Instagram\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;padding-right:10px\"><a target=\"_blank\" href=\"https://www.linkedin.com/company/always-sahi-solutions-private-limited/mycompany/?viewAsMember=true\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#0B5394;font-size:16px\"><img src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/circle-colored/linkedin-circle-colored.png\" alt=\"In\" title=\"Linkedin\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;padding-right:10px\"><a target=\"_blank\" href=\"https://twitter.com/bondsocially\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#0B5394;font-size:16px\"><img src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/circle-colored/twitter-circle-colored.png\" alt=\"Twitter\" title=\"Twitter\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0\"><a target=\"_blank\" href=\"https://chat.whatsapp.com/LdaQJeCqzY88e1Wb0jfnAe\" style=\"-webkit-te\xt-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#0B5394;font-size:16px\"><img src=\"https://pvuwmt.stripocdn.email/content/assets/img/messenger-icons/circle-colored/whatsapp-circle-colored.png\" alt=\"WhatsApp\" title=\"Whatsapp\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                </tr>  
                            </table></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table> 
                    <!--[if mso]></td></tr></table><![endif]--></td> 
                    </tr> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td style=\"Margin:0;padding-top:5px;padding-bottom:20px;padding-left:20px;padding-right:20px;background-position:left top\" align=\"left\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:560px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#666666;font-size:14px\">Contact us: <a target=\"_blank\" href=\"tel:8421776790\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#666666;font-size:14px\">+91 8421776790</a> | <a target=\"_blank\" href=\"mailto:i@careforbharat.in\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#666666;font-size:14px\">i@careforbharat.in</a></p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table> 
            <table class=\"es-footer\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td style=\"padding:0;Margin:0;background-color:#fafafa\" bgcolor=\"#fafafa\" align=\"center\"> 
                <table class=\"es-footer-body\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td style=\"Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;background-color:#010100\" bgcolor=\"#010100\" align=\"left\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:560px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"left\" style=\"padding:0;Margin:0;padding-top:5px;padding-bottom:5px\"><h2 style=\"Margin:0;line-height:19px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:16px;font-style:normal;font-weight:normal;color:#ffffff\"><strong>Have questions?</strong></h2></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"left\" style=\"padding:0;Margin:0;padding-bottom:5px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#ffffff;font-size:14px\">We are here to help, learn more about us <a target=\"_blank\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#ffffff;font-size:14px\" href=\"https://careforbharat.in\">https://careforbharat.in</a><br>or contact us&nbsp;<a target=\"_blank\" href=\"tel:8421776790\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#ffffff;font-size:14px\">+91 8421776790</a></p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table> 
            <table class=\"es-footer\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td style=\"padding:0;Margin:0;background-color:#fafafa\" bgcolor=\"#fafafa\" align=\"center\"> 
                <table class=\"es-footer-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"transparent\" align=\"center\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td align=\"left\" style=\"Margin:0;padding-bottom:5px;padding-top:15px;padding-left:20px;padding-right:20px\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:560px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"padding:0;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:18px;color:#666666;font-size:12px\">©2022 Bondsocially: All Rights Reserved</p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table></td> 
            </tr> 
        </table> 
        </div> 
        <grammarly-desktop-integration data-grammarly-shadow-root=\"true\"></grammarly-desktop-integration>  
        </body>
        </html>

        ";

        $mail->AltBody = "Hello ".$newUserFirstName.", <br/>We request you to generate your password! to get access to your account.<br/><br/>If did not make this request, just ignore this email. Otherwise, please click the link below to generate your password:<br/><a href=\"https://careforbharat.in/portal/reset.php?email=".$newUserEmail."&hash=".$hash."\" target=\"_blank\">https://careforbharat.in/reset</a><br/><br/>If you have any questions, then please email us at <a href=\"mailto:i@careforbharat.in\" target=\"_blank\">i@careforbharat.in</a> or call us at <a href=\"tel:8421776790\" target=\"_blank\">(+91) 8421776790</a> Mon-Fri 10am-6pm, we're always happy to help out.";

        if (!$mail->send()) {
            echo "<script>alert('Message could not be sent! Mailer Error: ".$mail->ErrorInfo." !');</script>";
        } else {
            // echo "<script>alert('Message has been sent! to Recipient');</script>";
            // $_SESSION['message'] = "Please check your email and follow the instructions to reset your password!";
            // header("Location: success.php");
            // exit();
        }

        $html = "
        <div class=\"card\">
        <div style=\"border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto; position: relative\">
            <i class=\"checkmark fas fa-check\" aria-hidden=\"true\"></i>
            </div>
                <h1>Success</h1> 
                <p>User registered successfully!</p><br/>
                <p>We've received your request;<br/> we'll notify you by email shortly!</p>
                <br/>
            </div>
        </div>
        </div>
        <br/><br/><br/>
        <p id='time' style=\"color: #FF0000; padding:0;\">Please wait for 10sec. You will be redirected to our website soon.</p>
        ";
        header("refresh:10;url=https://careforbharat.in/portal/home.php");
        echo $html;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv = "refresh" content = "10; url = https://www.google.co.in" /> -->
    <!-- font awesome -->
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap">
    <title>Verification</title>

    <style>
        body {
        text-align: center;
        padding: 40px 0;
        background: #ebf0f5;
        }
        h1 {
            color: #0e9e59;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }
        p {
            color: black;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-size: 20px;
            margin: 0;
        }
        i {
            color: #0e9e59;
            font-size: 10em;
            margin: 0;
            position: inherit;
            top: 50%;
            transform: translateY(-50%);
        }
        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #c8d0d8;
            display: inline-block;
            margin: 0 auto;
        }
        button {
            width: 100%;
            padding: 10px 10px;
            text-align: center;
            border: none;
            outline: none;
            border-radius: 30px;
            background-color: #FF0000;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: .3s;
        }
    </style>
</head>
<body>
</body>
<script>
    var timeLeft = 10;
    var elem = document.getElementById('time');
    var timerId = setInterval(countdown, 1000);
    function countdown() {
        if (timeLeft == -1) {
            clearTimeout(timerId);
        } else {
            elem.innerHTML = 'Please wait for '+ timeLeft + 'sec. You will be redirected to our website soon.';
            timeLeft--;
        }
    }
</script>
</html>