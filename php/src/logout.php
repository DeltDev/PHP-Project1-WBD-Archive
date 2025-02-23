<?php
    session_start();
    // if (!isset($_SESSION["role"])) { //orang yang belum login gak boleh visit page ini
    //     http_response_code(401);
    //     header("Location: 401-unauthorized.php");
    //     exit();
    // }
    session_unset();
    session_destroy();
    header("Location: login-jobseeker.php");
    exit;
?>