<?php
    //untuk memasukkan .env ke environment

    function loadEnv($path){
        if (!file_exists($path)) {
            throw new Exception(".env tidak ada");
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            //lewatin semua comment
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            //pecah line dari file.env menjadi key dan value
            list($key, $value) = explode('=', $line, 2);
    
            //trim semua tanda petik
            $value = trim($value, '"\' ');
    
            // set semua valu dari .env ke superglobal ENV dan SERVER
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
?>