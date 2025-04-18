<?php
    session_start();    
    include '../config.php'; 
    require '../PHPMailer/PHPMailerAutoload.php';
    
    $today = date('d-m-Y');
    $raisedUserEmail = $_SESSION['email'];
    $raisedUsername = $_SESSION['username'];
    $name = $mysqli->escape_string($_POST['patientName']);
    $emergencyType = $mysqli->escape_string($_POST['emergencyType']);
    $place = $mysqli->escape_string($_POST['place']);
    $address = $mysqli->escape_string($_POST['patientAddress']);
    $mobile = $mysqli->escape_string($_POST['patientMobile']);
    $description = $mysqli->escape_string($_POST['description']);

    $sql = "INSERT INTO alert (alertRaisedByUserEmail, alertRaisedByUsername, patientName, emergencyType, place, patientAddress, patientMobile, description) VALUES ('$raisedUserEmail','$raisedUsername','$name','$emergencyType', '$place', '$address', '$mobile', '$description');";

    if ( $mysqli->query($sql) ){

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
        $mail->addAddress('deshsevamai@bondsocially.org', 'Desh Seva Mai Bondsocially');
        // $mail->addAddress('ellen@example.com');
        $mail->addReplyTo($raisedUserEmail, $_SESSION['full_name']);
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('');
        // $mail->addAttachment($path);
        // $mail->addAttachment($offerletter, 'new.jpg'); 
        $mail->isHTML(true);

        $mail->Subject = 'New Alert Raised | careforbharat.in';  
        $mail->Body    = "
        
        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\" style=\"font-family:arial, 'helvetica neue', helvetica, sans-serif\"> 
        <head> 
        <meta charset=\"UTF-8\"> 
        <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
        <meta name=\"x-apple-disable-message-reformatting\"> 
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
        <meta content=\"telephone=no\" name=\"format-detection\"> 
        <title>Alert</title><!--[if (mso 16)]>
            <style type=\"text/css\">
            a {text-decoration: none;}
            </style>
            <![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]>
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
        [data-ogsb] .es-button {
            border-width:0!important;
            padding:10px 20px 10px 20px!important;
        }
        .es-button-border:hover a.es-button, .es-button-border:hover button.es-button {
            background:#56d66b!important;
            border-color:#56d66b!important;
        }
        .es-button-border:hover {
            border-color:#42d159 #42d159 #42d159 #42d159!important;
            background:#56d66b!important;
        }
        @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1, h2, h3, h1 a, h2 a, h3 a { line-height:120% } h1 { font-size:30px!important; text-align:left } h2 { font-size:24px!important; text-align:left } h3 { font-size:20px!important; text-align:left } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important; text-align:left } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:24px!important; text-align:left } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important; text-align:left } .es-menu td a { font-size:14px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:inline-block!important } a.es-button, button.es-button { font-size:18px!important; display:inline-block!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; max-height:inherit!important } }
        </style> 
        </head> 
        <body data-new-gr-c-s-loaded=\"14.1020.0\" style=\"width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0\"> 
        <div class=\"es-wrapper-color\" style=\"background-color:#F6F6F6\"><!--[if gte mso 9]>
                    <v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
                        <v:fill type=\"tile\" color=\"#f6f6f6\"></v:fill>
                    </v:background>
                <![endif]--> 
        <table class=\"es-wrapper\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top\"> 
            <tr> 
            <td valign=\"top\" style=\"padding:0;Margin:0\"> 
            <table class=\"es-header\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top\"> 
                <tr> 
                <td align=\"center\" bgcolor=\"#f4b459\" style=\"padding:0;Margin:0;background-color:#f4b459\"> 
                <table class=\"es-header-body\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px\"> 
                    <tr> 
                    <td align=\"left\" bgcolor=\"#f4b459\" style=\"padding:20px;Margin:0;background-color:#f4b459\"><!--[if mso]><table style=\"width:560px\" cellpadding=\"0\"
                                    cellspacing=\"0\"><tr><td style=\"width:150px\" valign=\"top\"><![endif]--> 
                    <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left\"> 
                        <tr> 
                        <td class=\"es-m-p0r es-m-p20b\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:150px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr> 
                            <td align=\"center\" style=\"padding:0;Margin:0;font-size:0px\"><img class=\"adapt-img\" src=\"https://careforbharat.in/icb_logo.png\" alt style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\" width=\"150\" height=\"73\"></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table><!--[if mso]></td><td style=\"width:20px\"></td><td style=\"width:390px\" valign=\"top\"><![endif]--> 
                    <table cellspacing=\"0\" cellpadding=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr> 
                        <td align=\"left\" style=\"padding:0;Margin:0;width:390px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr> 
                            <td align=\"right\" style=\"padding:0;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#333333;font-size:16px\"><u><strong>Date: ".$today."</strong></u></p></td> 
                            </tr> 
                            <tr> 
                            <td align=\"right\" style=\"padding:0;Margin:0;padding-top:10px;font-size:0\"> 
                            <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-table-not-adapt es-social\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                                <tr> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:20px\"><a target=\"_blank\" href=\"https://www.facebook.com/Bondsocially/\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:14px\"><img title=\"Facebook\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-colored-bordered/facebook-logo-colored-bordered.png\" alt=\"Fb\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:20px\"><a target=\"_blank\" href=\"https://www.instagram.com/bondsocially/\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:14px\"><img title=\"Instagram\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-colored-bordered/instagram-logo-colored-bordered.png\" alt=\"Inst\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:20px\"><a target=\"_blank\" href=\"https://www.linkedin.com/company/bondsocially-foundation/\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:14px\"><img title=\"Linkedin\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-colored-bordered/linkedin-logo-colored-bordered.png\" alt=\"In\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:20px\"><a target=\"_blank\" href=\"https://twitter.com/bondsocially\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:14px\"><img title=\"Twitter\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-colored-bordered/twitter-logo-colored-bordered.png\" alt=\"Tw\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:20px\"><a target=\"_blank\" href=\"https://www.youtube.com/channel/UCiV_ieJa2s5trGcoB-qsKHQ\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:14px\"><img title=\"Youtube\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-colored-bordered/youtube-logo-colored-bordered.png\" alt=\"Yt\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0\"><a target=\"_blank\" href=\"https://chat.whatsapp.com/LdaQJeCqzY88e1Wb0jfnAe\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:14px\"><img title=\"Whatsapp\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/messenger-icons/logo-colored-bordered/whatsapp-logo-colored-bordered.png\" alt=\"Whatsapp\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></a></td> 
                                </tr> 
                            </table></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table><!--[if mso]></td></tr></table><![endif]--></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table> 
            <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\"> 
                <tr> 
                <td align=\"center\" bgcolor=\"#ffffff\" style=\"padding:0;Margin:0;background-color:#ffffff\"> 
                <table class=\"es-content-body\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px\"> 
                    <tr> 
                    <td align=\"left\" style=\"Margin:0;padding-top:5px;padding-left:20px;padding-right:20px;padding-bottom:30px\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:560px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-left:40px;padding-right:40px;font-size:0px\"><img class=\"adapt-img\" src=\"https://pvuwmt.stripocdn.email/content/guids/CABINET_b7c5ed8c639f20b7ce93238ffd1e8bff/images/2842748.jpeg\" alt style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\" width=\"480\" height=\"411\"></td> 
                            </tr> 
                            <tr> 
                            <td align=\"left\" style=\"padding:0;Margin:0;padding-top:5px;padding-bottom:5px\"><h2 style=\"Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333\"><strong>Alert Raised!</strong></h2></td> 
                            </tr> 
                            <tr> 
                            <td align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-bottom:10px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Hello,<br><br>We've received a new alert from the account associated with following details:</p> 
                            <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Member Username</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$raisedUsername."</p></li> 
                                </ul></li> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Member Email</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$raisedUserEmail."</p></li> 
                                </ul></li> 
                            </ul><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\"><br>Details regarding same are mentioned below:</p> 
                            <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Patient Name</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$name."</p></li> 
                                </ul></li> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Emergency Type</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$emergencyType."</p></li> 
                                </ul></li> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Place</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$place."</p></li> 
                                </ul></li> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Address</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$address."</p></li> 
                                </ul></li> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Patient/Contact Person's Mobile</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$mobile."</p></li> 
                                </ul></li> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">Description&nbsp;</p> 
                                <ul> 
                                <li style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;Margin-bottom:15px;margin-left:0;color:#333333;font-size:14px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:17px;color:#333333;font-size:14px\">".$description."</p></li> 
                                </ul></li> 
                            </ul></td> 
                            </tr> 
                            <tr> 
                            <td align=\"center\" style=\"padding:20px;Margin:0;font-size:0\"> 
                            <table border=\"0\" width=\"100%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                                <tr> 
                                <td style=\"padding:0;Margin:0;border-bottom:2px solid #f4b459;background:unset;height:1px;width:100%;margin:0px\"></td> 
                                </tr> 
                            </table></td> 
                            </tr> 
                            <tr> 
                            <td align=\"left\" style=\"padding:0;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px\">Please confirm the details, and raise an announcement regarding this, or contact helpline services to help the patient.<span style=\"font-family:tahoma, verdana, segoe, sans-serif\"><span style=\"font-size:16px\"></span></span></p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table> 
            <table class=\"es-footer\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top\"> 
                <tr> 
                <td align=\"center\" bgcolor=\"#f4b459\" style=\"padding:0;Margin:0;background-color:#f4b459\"> 
                <table class=\"es-footer-body\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px\"> 
                    <tr> 
                    <td align=\"left\" bgcolor=\"#f4b459\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;background-color:#f4b459\"> 
                    <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr> 
                        <td align=\"left\" style=\"padding:0;Margin:0;width:560px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr> 
                            <td align=\"center\" style=\"padding:0;Margin:0;padding-bottom:15px;font-size:0\"> 
                            <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-table-not-adapt es-social\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                                <tr> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:10px\"><img title=\"Facebook\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-white/facebook-logo-white.png\" alt=\"Fb\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:10px\"><img title=\"Instagram\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-white/instagram-logo-white.png\" alt=\"Inst\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:10px\"><img title=\"Linkedin\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-white/linkedin-logo-white.png\" alt=\"In\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:10px\"><img title=\"Twitter\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-white/twitter-logo-white.png\" alt=\"Tw\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0;padding-right:10px\"><img title=\"Youtube\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/social-icons/logo-white/youtube-logo-white.png\" alt=\"Yt\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td> 
                                <td align=\"center\" valign=\"top\" style=\"padding:0;Margin:0\"><img title=\"Whatsapp\" src=\"https://pvuwmt.stripocdn.email/content/assets/img/messenger-icons/logo-white/whatsapp-logo-white.png\" alt=\"Whatsapp\" height=\"32\" width=\"32\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td> 
                                </tr> 
                            </table></td> 
                            </tr> 
                            <tr> 
                            <td align=\"center\" style=\"padding:0;Margin:0\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'lucida sans unicode', 'lucida grande', sans-serif;line-height:18px;color:#ffffff;font-size:12px\">You are receiving this email because you have visited our site or asked us about the regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).<br><a target=\"_blank\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#FFFFFF;font-size:12px;font-family:'lucida sans unicode', 'lucida grande', sans-serif\" href=\"https://bondsocially.org/\">Privacy Policy</a></p></td> 
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
        </body>
        </html>
        
        ";

        $mail->AltBody = "Hello, We&#39;ve received a new alert from the account associated with following details&#58; <br/><br/>Member Username&#58; ".$raisedUsername."<br/>Member Email&#58; ".$raisedUserEmail." <br/><br/>Details regarding same are mentioned below&#58; <br/><br/>Patient Name&#58; ".$name." <br/><br/>Emergency Type&#58; ".$emergencyType." <br/><br/>Place&#58; ".$place." <br/><br/>Address&#58; ".$address."<br/><br/>Patient/Contact Person Mobile&#58; ".$mobile."<br/><br/>Description&#58; ".$description." <br/><br/><br/>Please confirm the details, and raise an announcement regarding this, or contact helpline services to help the patient. ";

        if (!$mail->send()) {
            echo "<script>alert('Message could not be sent! Mailer Error: ".$mail->ErrorInfo." !');</script>";
            // echo "<script>alert('Mailer Error: ' . $mail->ErrorInfo . ' !');</script>";
            header("Location: ../home.php");
        } else {
            echo "<script>alert('Message has been sent! to Recipient');</script>";
            header("Location: ../home.php");
        }
    }

?>