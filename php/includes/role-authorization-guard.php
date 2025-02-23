<?php
    session_start();
    function checkAuthorization($permitted_role){
        if(!isset($_SESSION["role"]) || $_SESSION["role"] !== $permitted_role){
            http_response_code(401);
            header("Location: 401-unauthorized.php");
            exit();
        }
    }
?>