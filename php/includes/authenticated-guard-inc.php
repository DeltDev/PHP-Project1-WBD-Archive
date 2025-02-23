<?php
    session_start();
    if(isset($_SESSION["name"])){ //jika sudah login, jangan masuk ke page login lagi
        if($_SESSION["role"] === 'company'){ //login sbg company, redirect ke home company
            header("Location: home-company.php");
            exit();
        } else if($_SESSION["role"] === 'jobseeker'){//login sbg jobseeker, redirect ke home jobseeker
            header("Location: home-jobseeker.php");
            exit();
        }
    }
?>