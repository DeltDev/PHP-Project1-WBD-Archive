<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: register-jobseeker.php");
    exit;
?>