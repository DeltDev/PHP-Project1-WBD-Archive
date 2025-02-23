<?php
    require 'authenticated-guard-inc.php';
    require '../db/db-connect.php';
    
    function registerCompany($name, $email, $password, $password_conf,$location,$about){
        global $conn;
        //TODO Error handling 1. cek apakah konfirmasi password salah
        if($password != $password_conf){
            $_SESSION['error_message'] = "Gagal mendaftar: Konfirmasi Password berbeda dengan Password!";
            return;
        }
        //untuk menghindari SQL Injection
        $name = pg_escape_string($conn, $name);
        $email = pg_escape_string($conn, $email);
        $password = pg_escape_string($conn, $password);

        //TODO 2. hashing + peppering password pake bcrypt
        $pepper = $_ENV["PEPPER_COMPANY"]; //isi sendiri bebas peppernya apa tapi harus konsisten kalo mau testing
        $peppered_pass = $password . $pepper;
        $options = [
            'cost' => 12 //cost juga bebas tapi harus konsisten
        ];
        $hashed_pass = password_hash($peppered_pass, PASSWORD_BCRYPT, $options);

        //periksa apakah email sudah pernah terdaftar atau belum
        $email_check_query = "SELECT user_id FROM \"user\" WHERE email = '$email'";
        $check_result = pg_query($conn,$email_check_query);
        if(pg_num_rows($check_result) > 0){ //email sudah pernah terdaftar
            $_SESSION['error_message'] = "Gagal mendaftar: E-mail sudah terdaftar!";
            return;
        }

        //masukkan detail user perusahaan ke database
        $register_query = "INSERT INTO \"user\" (email, password, role, nama)  VALUES('$email','$hashed_pass','company','$name')";
        $res = pg_query($conn,$register_query);
        if(!$res){
            $_SESSION['error_message'] = "Gagal mendaftar: ' . pg_last_error($conn) . '";
            return;
        }
        //dapatkan user_id dulu karena di tabel company_detail user_id adalah foreign key ke user_id di tabel user
        $user_id_query = "SELECT user_id FROM \"user\" WHERE email='$email'";
        $user_id_result = pg_query($conn,$user_id_query);
        if(!$user_id_result){
            $_SESSION['error_message'] = "Gagal mendaftar: ' . pg_last_error($conn) . '";
            return;
        }
        //masukkan detail perusahaan ke database
        $row = pg_fetch_assoc($user_id_result);
        $user_id = $row['user_id'];
        $register_query2 = "INSERT INTO company_detail VALUES ('$user_id','$location','$about')";
        $res2 =pg_query($conn,$register_query2);
        if(!$res2){
            $_SESSION['error_message'] = "Gagal mendaftar: ' . pg_last_error($conn) . '";
            return;
        }
        $_SESSION['success_message'] = "Berhasil mendaftar sebagai perusahaan!";
        return;
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])){
        $name = $_POST["company-name"];
        $email = $_POST["company-email"];
        $password = $_POST["password"];
        $password_conf = $_POST["password-confirm"];
        $location = $_POST["company-location"];
        $about = $_POST["company-about"];
        registerCompany($name,$email,$password,$password_conf,$location,$about);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
?>