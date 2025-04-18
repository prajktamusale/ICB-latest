<?php
    $userFullName = $_POST['userFullName'];
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>careforbharat</title>
</head>
<body>
    <?php
        echo"<div id='user_full_name' style='display:none;'>$userFullName</div>
        <div id='event_name' style='display:none;text-transform:uppercase;'>$eventName</div>
        <div id='event_date' style='display:none;'>$eventDate</div>"
    ?>
    <script src="https://unpkg.com/pdf-lib@1.4.0"></script>
    <script src="../js/FileSaver.js"></script>
    <script src="https://unpkg.com/@pdf-lib/fontkit@0.0.4"></script>
    <script src="../js/certificate.js"></script>
</body>
</html>
<?php
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("refresh:2;url=$previousPage");
?>