<?php
    require 'authenticated-guard-inc.php';
    require '../db/db-connect.php';
    
    function registerJobseeker($name, $email, $password, $password_conf){
        global $conn;
        //TODO Error handling 1. verifikasi sudah input atau belum
        if(empty($name)){ //belum isi nama
            $_SESSION['error_message'] = "Gagal mendaftar: Anda belum mengisi nama!";
            return;
        }
        if(empty($email)){ //belum isi email
            $_SESSION['error_message'] = "Gagal mendaftar: Anda belum mengisi e-mail!";
            return;
        }
        if(empty($password)){ //belum isi password
            $_SESSION['error_message'] = "Gagal mendaftar: Anda belum mengisi password!";
            return;
        }
        if(empty($password_conf)){ //belum isi konfirmasi password
            $_SESSION['error_message'] = "Gagal mendaftar: Anda belum mengisi konfirmasi password!";
            return;
        }
        //TODO Error handling 2. verifikasi format email sudah benar atau belum
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $_SESSION['error_message'] = "Gagal mendaftar: Format E-mail Anda salah!";
            return;
        }
        //TODO Error handling 3. cek apakah konfirmasi password salah
        if($password != $password_conf){
            $_SESSION['error_message'] = "Gagal mendaftar: Konfirmasi Password berbeda dengan Password!";
            return;
        }
        //menghindari SQL dan code injection
        $name = pg_escape_string($conn, $name);
        $email = pg_escape_string($conn, $email);
        $password = pg_escape_string($conn, $password);
        
        //TODO 4. hashing + peppering password pake bcrypt
        $pepper = $_ENV["PEPPER_JOBSEEKER"]; //isi sendiri bebas peppernya apa tapi harus konsisten kalo mau testing
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
        //masukkan user baru yang terdaftar ke database
        $register_query = "INSERT INTO \"user\" (email, password, role, nama)  VALUES('$email','$hashed_pass','jobseeker','$name')";
        $res = pg_query($conn,$register_query);
        if(!$res){
            $_SESSION['error_message'] = "Gagal mendaftar: ' . pg_last_error($conn) . '";
            return;
        }

        $_SESSION['success_message'] = "Berhasil mendaftar sebagai jobseeker!";
        return;
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_conf = $_POST["password-confirm"];
        registerJobseeker($name,$email,$password,$password_conf);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
?>