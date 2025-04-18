<?php

require "config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
    header("Location: ./index.php");
}
if (!(isset($_SESSION['type']))){
    header("Location: ./index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['memberSave'])) {
        $newUserEmail = $_SESSION['email'];
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
        $newUserInitiatives = "";
        $newUserInterests = "";
        $newUserCollege = "";

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

        $sql_a = "UPDATE users SET dob = '$newUserDOB', gender = '$newUserGender', address = '$newUserAddress', city = '$newUserCity', state = '$newUserState', pin = '$newUserPIN', mobile = '$newUserMobile', telegram = '$newUserTelegram', instagram = '$newUserInstagram', initiatives = '$newUserInitiatives', interests = '$newUserInterests', aadhaar = '$newUserAadhaar', pan = '$newUserPAN'  WHERE email = '$newUserEmail';";

        if($mysqli->query($sql_a)){
            $_SESSION['message'] = "Member details saved successfully!";
            header("location: ./success.php");
        }else{
            $_SESSION['message'] = "Member details could not be saved! Please try again later.";
            header("location: ./error.php");
        }
    }
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
    <title>Member Registration</title>
    <link rel="stylesheet" href="./css/membersRegistration.css">
</head>

<body>
    <h1 id="title">Members Registration</h1>
    <form id="survey-form" action="signupDetail.php" method="POST">
        <!-- DOB : -->
        <div class="form-group">
            <label id="number-label" for="number" class="required">DOB </label>
            <input type="date" name="dob" id="dob" required>
            <span class="validity"></span>
        </div>

        <!-- gender  -->
        <div class="form-group">
            <label id="role-label" for="dropdown" class="required">Gender </label>
            <select id="dropdown" name="gender" required>
                <option value="" disabled selected>Select an option</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- address -->
        <div class="form-group" class="required">
            <label id="textarea-label" for="comments" class="required">Current Address </label>
            <textarea id="comments" placeholder="address" cols="30" rows="5" name="address" required></textarea>
        </div>

        <!-- city -->
        <div class="form-group">
            <label id="name-label" for="name" class="required">City </label>
            <input id="name" type="text" name="city" required placeholder="city">
            <span class="validity"></span>
        </div>

        <!-- State -->
        <div class="form-group">
            <label id="name-label" for="name" class="required">State </label>
            <input id="name" type="text" name="state" required placeholder="state">
            <span class="validity"></span>
        </div>

        <!-- pin -->

        <div class="form-group">
            <label id="name-label" for="name" class="required">PIN </label>
            <input id="name" type="number" name="pin" min="100000" pattern="[0-9]{6}" required placeholder="6 digit area pin code">
            <span class="validity"></span>
        </div>

        <!-- whatsapp mobile number -->
        <div class="form-group">
            <label id="name-label" for="name" class="required">Mobile </label>
            <input id="name" type="number" name="mobile" min="6000000000" pattern="[0-9]{10}" required placeholder="add 10 digit WhatsApp Number only">
            <span class="validity"></span>
        </div>

        <!-- telegram -->
        <div class="form-group">
            <label id="name-label" for="name">Telegram Username&nbsp;&nbsp;</label>
            <input id="name" type="text" name="telegram" placeholder="add Telegram Username without @">
            <span class="validity"></span>
        </div>

        <!-- instagram -->
        <div class="form-group">
            <label id="name-label" for="name">Instagram Username&nbsp;&nbsp;</label>
            <input id="name" type="text" name="instagram" placeholder="add Instagram Username without @">
            <span class="validity"></span>
        </div>

        <!-- contribution -->
        <div class="form-group">
            <p>Initiatives I will contribute for</p>
            <div class="input-group">
                <label><input type="checkbox" name="contriMissionShiksha" value="Mission-Shiksha"> Mission Shiksha</label>
                <label><input type="checkbox" name="contriMentalHealth" value="Mental-Health"> Mental Health</label>
                <label><input type="checkbox" name="contriAnimalSafety" value="Animals-Safety"> Animals Safety</label>
                <label><input type="checkbox" name="contriArtandCraft" value="Art-&-Craft"> Art & Craft</label>
                <label><input type="checkbox" name="contriEnvironment" value="Environment"> Environment</label>
                <label><input type="checkbox" name="contriSexEducation" value="Sex-Education"> Sex Education</label>
            </div>
        </div>

        <!-- Interests -->
        <div class="form-group">
            <p>Interests</p>
            <div class="input-group">
                <label><input type="checkbox" name="interestPublicRelation" value="Public-Relations"> Public Relations</label>
                <label><input type="checkbox" name="interestSpeakingandCommunication" value="Speaking-&-Communication"> Speaking & Communication</label>
                <label><input type="checkbox" name="interestOperations" value="Operations"> Operations</label>
                <label><input type="checkbox" name="interestSocialMediaManager" value="Social-Media-Manager"> Social Media Manager</label>
                <label><input type="checkbox" name="interestGraphicDesigner" value="Graphic-Designer"> Graphic Designer</label>
                <label><input type="checkbox" name="interestContentWriter" value="Content-Writer"> Content Writer</label>
                <label><input type="checkbox" name="interestAdminBodyManagement" value="Admin-Body-Management"> Admin Body Management</label>
                <label><input type="checkbox" name="interestLegalandFinance" value="Logal-&-Finance"> Legal & Finance</label>
            </div>
        </div>

        <!-- aadhaar card number -->
        <div class="form-group">
            <label id="name-label" for="name" class="required">Aadhaar Card </label>
            <input id="name" type="number" name="aadhaar" required placeholder="12 digit aadhaar number">
            <span class="validity"></span>
        </div>

        <!-- pan card number -->
        <div class="form-group">
            <label id="name-label" for="name">PAN </label>
            <input id="name" type="text" name="pan" placeholder="pan card number {format: AJCHB7489N}">
            <span class="validity"></span>
        </div>

        <!-- terms and condition -->
        <!-- <div class="form-group terms">
            <div class="input-group">
                <label class="required"><input type="checkbox" name="termsandconditions" value="events" required checked disabled> I agree to all the <a href="">Terms & Conditions</a> </label>
            </div>
        </div> -->
        <div class="form-group">
            <button id="rzp-button1" class="button" type="submit" name="memberSave">Save and Continue</button>
        </div>
    </form>
</body>
</html>