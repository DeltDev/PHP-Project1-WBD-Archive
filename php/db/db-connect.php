<?php
    require_once '../env/loadenv.php';
    loadEnv(dirname(__DIR__, 2) . '/.env');
    $host = $_ENV["DB_HOST"];
    $port = $_ENV["DB_PORT"];
    $user = $_ENV["DB_USER"];
    $password = $_ENV["DB_PASSWORD"];
    $dbname = $_ENV["DB_NAME"];
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
?>