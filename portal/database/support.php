<?php
    session_start();
    include '../config.php';
    require '../PHPMailer/PHPMailerAutoload.php';

    $userEmail = $_GET['userEmail'];
    $username = $_GET['username'];
    $enquiry = $_POST['enquiry'];
    $sql = "INSERT INTO support (userEmail, username, enquiry) VALUES ('$userEmail', '$username', '$enquiry')";
    if($mysqli->query($sql)){
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
        $mail->addReplyTo($userEmail, $_SESSION['full_name']);
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('');
        // $mail->addAttachment($path);
        // $mail->addAttachment($offerletter, 'new.jpg'); 
        $mail->isHTML(true);

        $mail->Subject = 'Support Ticket Generated | careforbharat.in';  
        $mail->Body    = "
        
        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\" style=\"width:100%;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0\">
        <head> 
        <meta charset=\"UTF-8\"> 
        <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
        <meta name=\"x-apple-disable-message-reformatting\"> 
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
        <meta content=\"telephone=no\" name=\"format-detection\"> 
        <title>New Template</title> 
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
        <!--[if !mso]><!-- --> 
        <link href=\"https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i\" rel=\"stylesheet\"> 
        <!--<![endif]--> 
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
        [data-ogsb] .es-button {
            border-width:0!important;
            padding:15px 25px 15px 25px!important;
        }
        @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1 { font-size:30px!important; text-align:center; line-height:120%!important } h2 { font-size:26px!important; text-align:center; line-height:120%!important } h3 { font-size:20px!important; text-align:center; line-height:120%!important } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:26px!important } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important } .es-menu td a { font-size:16px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:16px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:16px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:16px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:20px!important; display:block!important; border-width:15px 25px 15px 25px!important } .es-btn-fw { border-width:10px 0px!important; text-align:center!important } .es-adaptive table, .es-btn-fw, .es-btn-fw-brdr, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } }
        </style> 
        </head> 
        <body style=\"width:100%;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;background:#f4f4f4;\"> 
        <div class=\"es-wrapper-color\" style=\"background-color:#F4F4F4\"> 
        <!--[if gte mso 9]>
                    <v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
                        <v:fill type=\"tile\" color=\"#f4f4f4\"></v:fill>
                    </v:background>
                <![endif]--> 
        <table class=\"es-wrapper\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top\"> 
            <tr class=\"gmail-fix\" height=\"0\" style=\"border-collapse:collapse\"> 
            <td style=\"padding:0;Margin:0\"> 
            <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:600px\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"padding:0;Margin:0;line-height:1px;min-width:600px\" height=\"0\"><img src=\"https://pvuwmt.stripocdn.email/content/guids/CABINET_837dc1d79e3a5eca5eb1609bfe9fd374/images/41521605538834349.png\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;max-height:0px;min-height:0px;min-width:600px;width:600px\" alt width=\"600\" height=\"1\"></td> 
                </tr> 
            </table></td> 
            </tr> 
            <tr style=\"border-collapse:collapse\"> 
            <td valign=\"top\" style=\"padding:0;Margin:0\"> 
            <table class=\"es-header\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:#EC6D64;background-repeat:repeat;background-position:center top\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td align=\"center\" bgcolor=\"#000000\" style=\"padding:0;Margin:0;background-color:#b8e3ff\"> 
                <table class=\"es-header-body\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bgcolor=\"#000000\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#b8e3ff;width:600px\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td align=\"left\" style=\"Margin:0;padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:20px\"> 
                    <!--[if mso]><table style=\"width:580px\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"width:280px\" valign=\"top\"><![endif]--> 
                    <table cellspacing=\"0\" cellpadding=\"0\" align=\"left\" class=\"es-left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td class=\"es-m-p20b\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:280px\"> 
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"left\" style=\"padding:0px;Margin:0;font-size:0px\"><a href=\"https://careforbharat.in\" target=\"_blank\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#111111;font-size:24px\"><img src=\"https://careforbharat.in/icb_logo.png\" alt=\"I Care for Bharat Logo\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\" width=\"140px\" title=\"I Care for Bharat Logo\"></a></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table> 
                    <!--[if mso]></td><td style=\"width:20px\"></td><td style=\"width:280px\" valign=\"top\"><![endif]--> 
                    <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-right\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td align=\"left\" style=\"padding:0;Margin:0;width:280px\"> 
                        <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"right\" class=\"es-m-txt-r\" style=\"padding:0;Margin:0;padding-top:15px;padding-bottom:20px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:41px;color:#000000;font-size:27px\"><strong>Support Ticket</strong></p></td> 
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
                <td style=\"padding:0;Margin:0;background-color:#b8e3ff\" bgcolor=\"#000000\" align=\"center\"> 
                <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td align=\"left\" style=\"padding:0;Margin:0\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:600px\"> 
                        <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#ffffff;border-radius:4px\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" role=\"presentation\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td align=\"center\" style=\"Margin:0;padding-bottom:5px;padding-left:30px;padding-right:30px;padding-top:35px\"><h1 style=\"Margin:0;line-height:58px;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:48px;font-style:normal;font-weight:normal;color:#111111\">Support Needed!</h1></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td bgcolor=\"#ffffff\" align=\"center\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0\"> 
                            <table width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                                <tr style=\"border-collapse:collapse\"> 
                                <td style=\"padding:0;Margin:0;border-bottom:1px solid #ffffff;background:#FFFFFF none repeat scroll 0% 0%;height:1px;width:100%;margin:0px\"></td> 
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
            <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td align=\"center\" style=\"padding:0;Margin:0\"> 
                <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td align=\"left\" style=\"padding:0;Margin:0\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:600px\"> 
                        <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" role=\"presentation\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-txt-l\" bgcolor=\"#ffffff\" align=\"left\" style=\"Margin:0;padding-bottom:15px;padding-top:20px;padding-left:30px;padding-right:30px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:27px;color:#666666;font-size:18px\">Hello CareforBharat,</p></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-txt-l\" bgcolor=\"#ffffff\" align=\"center\" style=\"Margin:0;padding-bottom:15px;padding-top:20px;padding-left:70px;padding-right:70px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:27px; padding-top:10px; padding-bottom:10px; color:#111111; background-color:#049ef7; border-radius: 7px; font-size:18px; font-weight:bold; \">This ticket has been generated by<br><span style=\"color: #ffffff\">ID - ".$userEmail."</span></p></td> 
                            </tr> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-txt-l\" bgcolor=\"#FFffff\" align=\"left\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:30px;padding-right:30px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:22px;color:#666666;font-size:18px\">Description&#58; <br/>".$enquiry."</p></td> 
                            </tr> 
                        </table></td> 
                        </tr> 
                    </table></td> 
                    </tr> 
                </table></td> 
                </tr> 
            </table> 
            <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\"> 
                <tr style=\"border-collapse:collapse\"> 
                <td align=\"center\" style=\"padding:0;Margin:0\"> 
                <table class=\"es-content-body\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px\"> 
                    <tr style=\"border-collapse:collapse\"> 
                    <td align=\"left\" style=\"padding:0;Margin:0\"> 
                    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"> 
                        <tr style=\"border-collapse:collapse\"> 
                        <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:600px\"> 
                        <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:4px;background-color:#111111\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#111111\" role=\"presentation\"> 
                            <tr style=\"border-collapse:collapse\"> 
                            <td class=\"es-m-txt-c\" align=\"center\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:30px;padding-right:30px\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:27px;color:#ffffff;font-size:18px\">&#169;2022 Bondsocially: All Rights Reserved</p><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:27px;color:#ffffff;font-size:18px\"></p></td> 
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

        $mail->AltBody = "Hello, someone raised support! <br/><br/>Ticket generated by user associated with ".$userEmail." email.<br/><br/>Dsscription&#58; <br/>".$enquiry." ";

        if (!$mail->send()) {
            echo "<script>alert('Message could not be sent! Mailer Error: ".$mail->ErrorInfo." !');</script>";
            // echo "<script>alert('Mailer Error: ' . $mail->ErrorInfo . ' !');</script>";
            header("Location: ../home.php");
        } else {
            echo "<script>alert('Message has been sent! to Recipient');</script>";
            header("Location: ../home.php");
        }
    }
    header("Location: ../home.php");
?>