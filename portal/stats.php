<?php  
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
    }

    include './config.php';

    $today = date('Y-m-d');
    // Mental Health Count
    $sql = "SELECT * FROM `events` WHERE ((`eventInitiative` LIKE 'Mental Health') AND eventDate<'$today')";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $tHealth = mysqli_num_rows($resultEvents);

    // Mission Shikha Count
    $sql = "SELECT * FROM `events` WHERE ((`eventInitiative` LIKE 'Mission Shiksha') AND eventDate<'$today')";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $tShiksha = mysqli_num_rows($resultEvents);

    // Animal Safet Count
    $sql = "SELECT * FROM `events` WHERE ((`eventInitiative` LIKE 'Animal Safety') AND eventDate<'$today')";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $tAnimal = mysqli_num_rows($resultEvents);
    
    // Art & Craft Count
    $sql = "SELECT * FROM `events` WHERE ((`eventInitiative` LIKE 'Art & Craft') AND eventDate<'$today')";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $tArt = mysqli_num_rows($resultEvents);

    // Environment Count
    $sql = "SELECT * FROM `events` WHERE ((`eventInitiative` LIKE 'Environment') AND eventDate<'$today')";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $tEnvironment = mysqli_num_rows($resultEvents);

    // Sex Education Count
    $sql = "SELECT * FROM `events` WHERE ((`eventInitiative` LIKE 'Sex Education') AND eventDate<'$today')";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $tSexEd = mysqli_num_rows($resultEvents);

    // Others Count
    $sql = "SELECT * FROM `events` WHERE eventDate<'$today'";
    $resultEvents = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $totalEvents = mysqli_num_rows($resultEvents);
    $tOther = $totalEvents-($tHealth+$tShiksha+$tAnimal+$tArt+$tEnvironment+$tSexEd);

    $max = max($tEnvironment, $tAnimal, $tArt, $tHealth, $tShiksha, $tSexEd, 1);
    
    // relative value to display in graph

    $tgEnvironment =100-($tEnvironment/$max)*90;
    $tgAnimal =100-($tAnimal/$max)*90;
    $tgArt =100-($tArt/$max)*90;
    $tgHealth =100-($tHealth/$max)*90;
    $tgShiksha =100-($tShiksha/$max)*90;
    $tgSexEd =100-($tSexEd/$max)*90;

    // marshals count
    $query = "SELECT COUNT(*) FROM `users`";
    $res = mysqli_query($mysqli,$query) or die('SQL Failed');
    $oe = [];
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            $tMarshals = $row['COUNT(*)'];
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <title>Stats Page</title>
        <link rel="stylesheet" href="./css/main.css">
        <link rel="stylesheet" href="./css/stats.css">
        <link rel="stylesheet" href="./css/header.css">	
        <link rel="stylesheet" href="./css/utils.css">
        <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
        <script src="./js/sliderAccordian.js" defer></script>
    </head>
    <body>
        
        <?php
            include './header.php';
        ?>
        <h3 class="achHeading">Initiative Stats</h3>

        <div class="graphBox"><!-- 2 -2 28 36 -->
        <svg class="circle-container" viewBox="2 -5 28 42" xmlns="http://www.w3.org/2000/svg">
            <circle class="circle-container__background" r="20" cx="16" cy="16"></circle>
            <circle
              class="ringA circle-container__progress"
              r="20"
              cx="16"
              cy="16"
              style="stroke-dashoffset: <?php echo $tgSexEd?>" pathlength="100"
            ></circle>

            <circle class="circle-container__background" r="17" cx="16" cy="16"></circle>
            <circle
              class="ringB circle-container__progress"
              r="17"
              cx="16"
              cy="16"
              style="stroke-dashoffset: <?php echo $tgEnvironment?>" pathlength="100"
            ></circle>

            <circle class="circle-container__background" r="14" cx="16" cy="16"></circle>
            <circle
              class="ringF circle-container__progress"
              r="14"
              cx="16"
              cy="16"
              style="stroke-dashoffset: <?php echo $tgArt?>" pathlength="100"
            ></circle>

            <circle class="circle-container__background" r="11" cx="16" cy="16"></circle>
            <circle
              class="ringC circle-container__progress"
              r="11"
              cx="16"
              cy="16"
              style="stroke-dashoffset: <?php echo $tgAnimal?>" pathlength="100"
            ></circle>

            <circle class="circle-container__background" r="8" cx="16" cy="16"></circle>
            <circle
              class="ringD circle-container__progress"
              r="8"
              cx="16"
              cy="16"
              style="stroke-dashoffset: <?php echo $tgShiksha?>" pathlength="100"
            ></circle>

            <circle class="circle-container__background" r="5" cx="16" cy="16"></circle>
            <circle
              class="ringE circle-container__progress"
              r="5"
              cx="16"
              cy="16"
              style="stroke-dashoffset: <?php echo $tgHealth?>" pathlength="100"
            ></circle>
          </svg>
          <div class="legends">
            <div class="eventRow">
                <div class="eventColor" style="background-color: #CB8FBD;"></div>
                <div class="eventName">Mental Health</div>
            </div>
            <div class="eventRow">
                <div class="eventColor" style="background-color: #2EC5B6;"></div>
                <div class="eventName">Mission Shiksha</div>
            </div>
            <div class="eventRow">
                <div class="eventColor" style="background-color: #E01518;"></div>
                <div class="eventName">Animal Safety</div>
            </div>
            <div class="eventRow">
                <div class="eventColor" style="background-color: #3498DB;"></div>
                <div class="eventName">Art & Craft</div>
            </div>
            <div class="eventRow">
                <div class="eventColor" style="background-color: #41D950;"></div>
                <div class="eventName">Environment</div>
            </div>
            <div class="eventRow">
                <div class="eventColor" style="background-color: #FFBE00;"></div>
                <div class="eventName">Sex Education</div>
            </div>
          </div>
          </div>
          <div class="grapfData">
            <div class="gData1">
            <br>
            <h3 class="achHeading">Achievements</h3>
            <br>
            <div class="achievements">
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #fff;"></div>
                    <div class="eventName"><b>Events</b> </div>
                    <div class="eventDate"><b>Events Held</b></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #CB8FBD;"></div>
                    <div class="eventName">Mental Health</div>
                    <div class="eventDate"><?php echo $tHealth?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #2EC5B6;"></div>
                    <div class="eventName">Mission Shiksha</div>
                    <div class="eventDate"><?php echo $tShiksha?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #E01518;"></div>
                    <div class="eventName">Animal Safety</div>
                    <div class="eventDate"><?php echo $tAnimal?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #3498DB;"></div>
                    <div class="eventName">Art & Craft</div>
                    <div class="eventDate"><?php echo $tArt?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #41D950;"></div>
                    <div class="eventName">Environment</div>
                    <div class="eventDate"><?php echo $tEnvironment?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #FFBE00;"></div>
                    <div class="eventName">Sex Education</div>
                    <div class="eventDate"><?php echo $tSexEd?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="achColor" style="background-color: #D9D9D9;"></div>
                    <div class="eventName">Other</div>
                    <div class="eventDate"><?php echo ($tOther == 0) ? '-' : $tOther; ?></div>
                </div>
            </div>
          </div>
          <div class="gData2">
            <br>
            <h3 class="achHeading">Events Data</h3>
            <br>
            <div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="eventName">Total Events Held:</div>
                    <div class="eventDate"><?php echo $totalEvents?></div>
                </div>
                <div class="eventRow" style="margin-bottom: 0; margin-top: 0;">
                    <div class="eventName">Total Marshals:</div>
                    <div class="eventDate"><?php echo $tMarshals ?></div>
                </div>
            </div>
            </div>
          </div>

        <br>
        <?php include "./components/bottomNav.php";?>
        <script src="./js/sideBar.js"></script>
    </body>
</html>