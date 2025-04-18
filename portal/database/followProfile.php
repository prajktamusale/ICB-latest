<?php
    include '../config.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function checkSameUser($follower, $following){
        if($follower==$following){
            header("Location: ../home.php");
        }
    }

    if(isset($_POST['follow'])){

        // Sanitizing the username input
        $userProfile = mysqli_real_escape_string($mysqli, $_POST['follow']);
        // Selecting the data from the database
        // $query = ""
        $follower = $_SESSION['username'];
        $following = mysqli_real_escape_string($mysqli, $_POST['follow']);
        $checking = mysqli_query($mysqli, "SELECT * FROM followers WHERE follower='".$follower."' and following='".$following."';");
        // echo $follower;
        // echo $following;
        checkSameUser($follower,$following);
        if(mysqli_num_rows($checking)==1){
            echo "Already following";
        }else{
            // $followingList = json_encode(array(0 => [$following]));
            $sql = "INSERT INTO followers values ('".$follower."', '".$following."');";
            if(mysqli_query($mysqli, $sql)){
                echo "Successfully followed";
                header("Location: ../userProfile.php?username=$following");
            }else{
                echo "Data couldn't be updated";
            }
        }
        // $userProfileResult = mysqli_query($mysqli, $query) or die('Some Error Occured');
        // echo $result;
    }else if(isset($_POST['unfollow'])){
        $userProfile = mysqli_real_escape_string($mysqli, $_POST['unfollow']);
        $follower = $_SESSION['username'];
        $following = mysqli_real_escape_string($mysqli, $_POST['unfollow']);
        $checking = mysqli_query($mysqli, "SELECT * FROM followers WHERE follower='".$follower."' and following='".$following."';");
        checkSameUser($follower,$following);
        if(mysqli_num_rows($checking)==0){
            echo "Not following";
        }else{
            // $followingList = json_encode(array(0 => [$following]));
            $sql = "DELETE FROM followers WHERE follower='".$follower."' and following='".$following."';";
            if(mysqli_query($mysqli, $sql)){
                echo "Successfully unfollowed.";
                header("Location: ../userProfile.php?username=$following");
            }else{
                echo "Data couldn't be updated";
            }
        }
    }
    else
    {
        echo "Didn't recive request";
    }
    mysqli_close($mysqli);
?>