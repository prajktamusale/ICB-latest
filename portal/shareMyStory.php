<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!isset($_SESSION['type'])){
        header("Location: ./index.php");
    }
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
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
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/shareMyStory.css">
    <link rel="stylesheet" href="./css/utils.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
</head>

<body>
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>

    <!-- shareMyStory -->

<!-- User Profile -->
    <div class="container">
        <!-- Getting user profile picture from the directory -->
        <?php 
            echo "<div class='profilePicture pp-sms'>" . "\n" .
                     "<img src='".$_SESSION['profile']."' style='border-color: " . $borderColor . ";' alt=''>" . "\n" .
                     "<img class='check right-tick " . $display . "' src='" . $checkSrc . "' alt=''>" . "\n" .
                 "</div>";
        ?>
        <!-- Display username  -->
        <div class="name">
            <h2> <?php echo $_SESSION['username']?></h2>
        </div>
        <!-- Display user account type -->
        <div class="role">
            <h2><?php echo ucwords($_SESSION['type']); ?></h2>
        </div>

        <!-- Option to add post -->
        <div class="sm-container">
            <div class="add-post">
                <h3>Add Post</h3>
                <img src="./images/plus.png" class="postButton" alt="Add post">
            </div>
        </div>
        <!-- Pop up: Porovind the user with option for posting new post -->
        <div class="post-something">
            <!-- The post is send to shareMySytory.php file in database where the data is validated and added to database -->
            <?php echo "<form action='./database/shareMyStory.php?username=$username&email=$email' method='POST' enctype='multipart/form-data'>" ?>
            <!-- Accepting the user image post -->
            <div class="images-input">
                <label for="post-image-message" class="upload-image">Add your post here</label>
                <br>
                <!-- <input type="file"> -->
                <label for="add-images" id="addimage" class="center-label"><div>ADD IMAGE</div>
                    <img src="https://cdn-icons-png.flaticon.com/128/3342/3342137.png" />
                    <input name="add-images" class="add-images" type='file' name='file'  id='newPost' accept='image/png, image/jpg, image/jpeg'>
                </label>

            </div>
            <!-- <br> -->
            <!-- Caption to be added to the post -->
            <div>
                <textarea name='caption' placeholder="Enter Text" rows="20" id="comment_text" cols="40" class="ui-autocomplete-input" ></textarea>
            </div>   
            <input type="submit" class="btn">
            </form>
        </div>
        <div class="no-post-yet">
            <h2>No Post Yet</h2>
        </div>
    </div>

    <?php include "./components/bottomNav.php";?>

    <script src="./js/shareMyStory.js"></script>
    <script src="./js/sideBar.js"></script>
    <script>
        const images = document.getElementById('addimage');

        images.addEventListener('click', () => {
            // let inputImage =document.querySelector("input[type='file']").files[0];
            const addImage=document.getElementById('newPost');
            console.log(addImage);
            addImage.click();
        })
    </script>
</body>

</html>