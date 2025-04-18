<?php
    include '../config.php';
    
    if(isset($_POST['username'])){
        $username = $mysqli->escape_string($_POST['username']);

        if(!empty($username)){
            $result = $mysqli->query("SELECT * FROM users WHERE username='$username'");
            if ($result->num_rows == 0) {
                echo "Username Available";
            } else {
                echo "Username Not Available";
            }
            mysqli_close($mysqli);
        }
    }
?>