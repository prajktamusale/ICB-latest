<script>
    setInterval(()=>{
        let current_notifiaction = <?php echo $_SESSION['message'] ?>;
    
        // Checking if the current notification has been updated
        if(localStorage.getItem("notification-prev")!=current_notifiaction){
            localStorage.setItem("notification-prev", current_notifiaction);
    }
    }, 2000);
    let current_notifiaction = <?php echo $_SESSION['message'] ?>;
    
</script>

<div class="notification">
    <?php 
        if(isset($_SESSION["message"]))
            echo $_SESSION["message"];
    ?>
</div>
