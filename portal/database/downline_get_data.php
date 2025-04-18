<?php
    include "../config.php";
    
    $sql_get_downline = "select id, (firstName+' '+lastName) as fullName,  ";
    
    mysqli_close($mysqli);
?>