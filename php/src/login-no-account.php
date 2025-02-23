<?php
    session_start();
    $_SESSION["role"] = "jobseeker";

    header("Location: home-jobseeker.php");
    exit();
?>