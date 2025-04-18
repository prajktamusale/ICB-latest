<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!isset($_SESSION['type'])){
        header("Location: ../index.html");
    }
    
    include './config.php';

    $sql = "SELECT * FROM trainings";
    $resultTrainings = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $outputTrainings = [];
    if(mysqli_num_rows($resultTrainings) > 0){
        while($row = mysqli_fetch_assoc($resultTrainings)){
            $outputTrainings[] = $row;
        }
    }

    $completedTrainings = [];
    $ongoingTrainings = [];
    for($x=0; $x<sizeof($outputTrainings); $x++){
        $trainingTableName = $outputTrainings[$x]['trainingTableName'];
        $trainingName = $outputTrainings[$x]['trainingName'];
        $email = $_SESSION['email'];
        $sql = "SELECT * FROM $trainingTableName WHERE enrolledUserEmail='$email'";
        $resultTrainingTable = mysqli_query($mysqli, $sql) or die("SQL Failed");
        $outputTrainingTable = NULL;
        if(mysqli_num_rows($resultTrainingTable) > 0){
            $outputTrainingTable = mysqli_fetch_array($resultTrainingTable);
            if($outputTrainingTable['enrolledUserCompleted'] != 0){
                $completedTrainings[] = (object) ['id' => $outputTrainingTable['id'], 'enrolledUsername' => $outputTrainingTable['enrolledUsername'], 'enrolledUserMobile' => $outputTrainingTable['enrolledUserMobile'], 'enrolledUserEmail' => $outputTrainingTable['enrolledUserEmail'], 'enrollmentDate' => $outputTrainingTable['enrollmentDate'], 'trainingTableName' => $trainingTableName, 'trainingName' => $trainingName];
            }
            else{
                $ongoingTrainings[] = (object) ['id' => $outputTrainingTable['id'], 'enrolledUsername' => $outputTrainingTable['enrolledUsername'], 'enrolledUserMobile' => $outputTrainingTable['enrolledUserMobile'], 'enrolledUserEmail' => $outputTrainingTable['enrolledUserEmail'], 'enrollmentDate' => $outputTrainingTable['enrollmentDate'], 'trainingTableName' => $trainingTableName, 'trainingName' => $trainingName];
            }
        }
    }
    
    mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareforBharat</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/training.css">
    <link rel="stylesheet" href="./css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <script src="./js/sliderAccordian.js" defer></script>
</head>

<body>
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>

    <div class="container">
        <div class="head">
            <div class="enrolledTrainingsHead">My Trainings</div>
            <div class="completedTrainingsHead active">Completed</div>
        </div>
        <div class="enrollments d-block">
            <?php 
                if(sizeof($ongoingTrainings)==0){
                    echo "<div class='empty'>You have not enrolled in any training yet</div>";
                }
                else {
                    for($x=0; $x<sizeof($ongoingTrainings); $x++){
                        echo "<div class='enrolledTraining'>
                                <div class='enrolledTrainingHeading'>" . $ongoingTrainings[$x]->trainingName . "</div>
                                <div class='enrolledTrainingData'>
                                    <img src='./images/play.png' alt=''>
                                </div>
                              </div>";
                    }
                }
            ?>
        </div>
        <div class="completedTrainings d-none">
            <?php
                if(sizeof($completedTrainings)==0){
                    echo "<div class='empty'>You have not completed any training yet</div>";
                }
                else {
                    for($x=0; $x<sizeof($completedTrainings); $x++){
                        echo "<div class='completedTraining'>
                                <div class='completedTrainingHeading'>" . $completedTrainings[$x]->trainingName . "</div>
                                <div class='completedTrainingData'>
                                    <img src='./images/download.svg' alt=''>
                                </div>
                              </div>";
                    }
                }
            ?>
        </div>

        <!-- book now -->

        <?php
            include './config.php';
            $display = '';
            if(sizeof($outputTrainings) == 0){
                $display = 'd-none';
            }
            echo "<div class='bookings " . $display . "'>" . "\n" .
                "<h2>Book Now</h2>" . "\n" . 
                "<div class='book-now'>" . "\n";
            for($x= 0; $x<sizeof($outputTrainings); $x++){
                $trainingTableName = $outputTrainings[$x]['trainingTableName'];
                $sql = "SELECT * FROM $trainingTableName WHERE enrolledUserEmail='$email'";
                $resultTrainingTable = mysqli_query($mysqli, $sql) or die("SQL Failed");
                if(mysqli_num_rows($resultTrainingTable) > 0){
                    echo "<div class='images'>" . "\n" . 
                    "<h3>" . $outputTrainings[$x]["trainingName"] . "</h3>" . "\n" .
                    "<button disabled style='cursor:default;'>Enrolled</button></div>";
                }
                else{
                    echo "<div class='images'>" . "\n" . 
                    "<h3>" . $outputTrainings[$x]["trainingName"] . "</h3>" . "\n" .
                    "<a href='./database/enrollTraining.php?training=" . $outputTrainings[$x]["trainingTableName"] . "&userEmail=" . $_SESSION['email'] . "'><button>Enroll</button></a></div>";
                }
                
            }
            echo "</div>";
            mysqli_close($mysqli);
        ?>
    </div>
    <script src="./js/sideBar.js"></script>
    <script src="./js/training.js"></script>
    <?php include "./components/bottomNav.php";?>
    <script src="./js/opportunity.js"></script>
</body>

</html>