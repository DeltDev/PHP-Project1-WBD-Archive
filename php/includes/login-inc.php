<?php
    require 'authenticated-guard-inc.php';
    require '../db/db-connect.php';
    
    function loginUser($role, $email, $password) {
        global $conn;
        //mencegah SQL Injection
        $email = pg_escape_string($conn, $email);
        $password = pg_escape_string($conn, $password);
        //cek apakah email terdaftar di database
        $email_check_query = "SELECT * FROM \"user\" WHERE email = '$email'";
        $user = pg_query($conn, $email_check_query);
        if (pg_num_rows($user) <= 0) {
            $_SESSION['error_message'] = 'E-mail belum terdaftar!';
            return;
        }
        //dapatkan data user untuk cek password
        $user_data = pg_fetch_assoc($user);
        $target_pass = $user_data["password"];
        //pake pepper berdasarkan role
        $pepper = $role === 'company' ? $_ENV["PEPPER_COMPANY"] : $_ENV["PEPPER_JOBSEEKER"];
        $peppered_pass = $password . $pepper;
        //cek apakah role user sama
        if ($user_data["role"] != $role) {
            $_SESSION['error_message'] = $role === 'company'
            ? "Anda login sebagai jobseeker, silakan ke halaman login jobseeker"
            : "Anda login sebagai perusahaan, silakan ke halaman login perusahaan";
            return;
        }
        //cek password
        if (password_verify($peppered_pass, $target_pass)) {
            //buat session
            $_SESSION["user_id"] = $user_data["user_id"];
            $_SESSION["name"] = $user_data["nama"];
            $_SESSION["role"] = $role;
            $_SESSION["is_login_success"] = true;
            $_SESSION["back_to_home"] = true;
            header('Location: login-success.php');
        } else {
            
            $_SESSION['error_message'] = 'Password salah!';
            return;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) { //terima dari post
        $email = $_POST["email"];
        $password = $_POST["password"];
        //cek apakah role sudah ada
        if (isset($_POST["role"])) {
            $role = $_POST["role"];
            loginUser($role, $email, $password);
            
        } else {
            $_SESSION['error_message'] = "Role user belum ada!";
        }
    }
?>