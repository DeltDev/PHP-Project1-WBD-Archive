<?php
//needs further debugging
function errorAlertRefresh($code, $message = ''){
    http_response_code($code);

    switch ($code) {
        case 400:
            $message = $message ?: 'Bad Request';
            break;
        case 401:
            $message = $message ?: 'Unauthorized';
            break;
        case 403:
            $message = $message ?: 'Forbidden';
            break;
        case 404:
            $message = $message ?: 'Not Found';
            break;
        case 500:
            $message = $message ?: 'Internal Server Error';
            break;
        default:
            $message = $message ?: 'An error occurred';
            break;
    }

    echo "<script>alert(\"Error $code $message\")</script>";
    header('Refresh:0');
    exit();
}
    header("Location: index.php");
    die();
?>