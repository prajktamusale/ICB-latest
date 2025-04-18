<?php
    include '../config.php';
    $liked=$_GET['like'];
    $postID=$_GET['postID'];
    $userID=$_GET['userID'];
    $sql = "SELECT likes from publicstory WHERE id=$postID";
    $result = mysqli_query($mysqli, $sql) or die("Didn't get the post");
    $output = NULL;
    if(mysqli_num_rows($result)>0){
        $output = mysqli_fetch_array($result);
    }
    $likes = $output['likes'];
    if($liked=='false'){
        $likes = $likes + 1;

        $sql = "INSERT INTO postlikes (userID, postID) VALUES ($userID, $postID)";
        $result = mysqli_query($mysqli, $sql) or die('Error');

        $sql = "UPDATE publicstory SET likes=$likes WHERE id=$postID";
        $result = mysqli_query($mysqli, $sql) or die('Error');
    }
    else if($liked=='true'){
        $likes = $likes - 1;
        
        $sql = "DELETE FROM postlikes WHERE userID=$userID AND postID=$postID";
        $result = mysqli_query($mysqli, $sql) or die('Error');

        $sql = "UPDATE publicstory SET likes=$likes WHERE id=$postID";
        $result = mysqli_query($mysqli, $sql) or die('Error');
    }
    else{
        echo 'Error';
    }
    mysqli_close($mysqli);
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("Location: $previousPage");
?>