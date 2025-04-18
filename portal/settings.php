<?php
/*
 Title: Settings.php provides user with account setting and support options.

 Dependencies:
 - None
*/

    // 1. session_status() checks for the status of session.
    if (session_status() == PHP_SESSION_NONE) {
        // If session_status is equal to PHP_SESSION_NONE
        // Then start the session using session_start()
        session_start();
    }
    // 2. Checking if session variable logged_in is set not null and logged_in value is true
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        // Redirect to index.php page for login.
        header("Location: ./index.php");
    }
    // 3. If session type variable is not  set  
    if (!isset($_SESSION['type'])){
        // Redirect to index.html page.
        header("Location: ./index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <title>CareforBharat</title>
    <!-- <link rel="stylesheet" href="css/main.css"> -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/profile-css/settings.css">
    <link rel="stylesheet" href="css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>

    <script src="./js/sliderAccordian.js" defer></script>
</head>

<body>
    <div class="flex-row">
        <?php
            include './header.php';
        ?>

        <!-- 
            Form to accept user details.
            - Action will be forwarded to `./database/changeSettings.php?userEmail=" . $_SESSION['email'] . "` page.
            - method: POST
            #### Accepts:
            |Name of value| name |
            |---|---|----|
            | Phone |`$Session['email']` |
            | Old Password | `currPassword`|
            | New Password |`updatedPassword`|
            | Confirm Password |`updatedPasswordConfirmation`|
            |Address|`$_SESSION['address']`|
            #### Button
            |Name of value| name |
            |---|---|----|
            |Save | |

         -->
        <h2>Settings</h2>

        <section class="settings">

            <div class="container3">
                <?php echo "<form id='settingsForm' action='./database/changeSettings.php?userEmail=" . $_SESSION['email'] . "' method='POST'>"; ?>
                    <div class="row input-container">
                        <div class="col-md-12">
                            <div class="styled-input wide">
                                <input name="mobile" type="phone" required value=<?php echo $_SESSION['mobile'] ?> />
                                <label class="settings-label">Phone</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="styled-input">
                                <input type="password" name="currPassword" required/>
                                <label class="settings-label">Old Password</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="styled-input" style="float:right;">
                                <input id='updatedPassword' type="password" name="updatedPassword" required />
                                <label class="settings-label">New Password</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="styled-input" style="float:right;">
                                <input id='confirmPassword' type="password" name="updatedPasswordConfirmation" required />
                                <label class="settings-label">Retype New Password</label>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="styled-input wide">
                                <textarea name="address" required><?php echo $_SESSION['address']?></textarea>
                                <label class="settings-label">Address</label>
                            </div>
                        </div>
                        <div class="col-xs-12" style="overflow: hidden;">
                            <button ref='submitSettings' class="btn-lrg submit-btn settingSubmit">Save</button>
                        </div>
                    </div>
                    <button id='submitSettings' class='d-none' type="submit"></button>
                </form>
            </div>


        </section>
        <br>


        <!-- 
            Form to accept user details.
            - Action will be forwarded to `./database/support.php?userEmail=" . $_SESSION['email'] . "&username=" . $_SESSION['username'] . "` page.
            - method: POST
            #### Accepts:
            |Name of value| name |
            |---|---|
            | Enquiry |`enquiry` |
            #### Button
            |Name of value| Type |
            |---|---|
            |Save | submit |

         -->

        <h2>Support</h2>

        <section class="support">
            <div class="container3">

            <?php echo "<form id='supportForm' action='./database/support.php?userEmail=" . $_SESSION['email'] . "&username=" . $_SESSION['username'] . "' method='POST'>"; ?>
                    <div class="row input-container">
                        <div class="col-xs-12">
                            <h3 style="margin: 20px;">Enquiry</h3>
                            <div class="styled-input wide">
                                <textarea name='enquiry' required></textarea>
                                <label class="settings-label">Type</label>
                            </div>
                        </div>
                        <div class="col-xs-12" style="overflow: hidden;">
                            <button ref='submitSupport' class="btn-lrg submit-btn">Save</button>
                        </div>

                    </div>
                    <button id="submitSupport" class='d-none' type="submit"></button>
                </form>

            </div>
        </section>
    </div>
    <?php include "./components/bottomNav.php";?>
    
    <script src="js/sideBar.js"></script>
    <script>
            const password = document.getElementById("updatedPassword"), confirm_password = document.getElementById("confirmPassword");
            const submitBtn = document.querySelector('settingSubmit');
            function validatePassword(){
                if(password.value != confirm_password.value) {
                    confirm_password.setCustomValidity("Passwords Didn't Match, Try Again");
                } else {
                    confirm_password.setCustomValidity('');
                }
            }
            password.onchange = validatePassword;
            confirm_password.onkeyup = validatePassword;
    </script>
</body>

</html>
