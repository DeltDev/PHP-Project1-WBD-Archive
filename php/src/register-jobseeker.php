<?php
    require '../includes/register-jobseeker-inc.php';
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    unset($_SESSION["error_message"]);
    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
    unset($_SESSION["success_message"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar sebagai Jobseeker</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">
</head>
<body>
    <a href="#" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Daftar sebagai Jobseeker</h1>
        <div id="error-message" class="message-box error-message hidden"></div>
        <div id="success-message" class="message-box success-message hidden"></div>
        <form class="login-form" action="" method="post" autocomplete="off">
            <label for="">Nama</label>
            <input type="text" name="name" placeholder="Nama" value="">
            <label for="">E-mail</label>
            <input type="email" name="email" placeholder="E-mail" required value="">
            <label for="">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required value="">
            <div id="password-verify" class="msg-box">
                <h3>Password harus mengandung:</h3>
                <ul>
                    <li id="length-check">8 karakter</li>
                    <li id="uppercase-check">Setidaknya ada 1 huruf kapital</li>
                    <li id="lowercase-check">Setidaknya ada 1 huruf kecil</li>
                    <li id="symbol-check">Setidaknya ada 1 simbol dari salah satu simbol berikut: !@#$%^&*(),.?":{}|<></li>
                    <li id="number-check">Setidaknya ada 1 angka</li>
                </ul>
            </div>
            <label for="">Konfirmasi Password</label>
            <input type="password" id="password-confirm" name="password-confirm" placeholder="Konfirmasi Password" required value="">
            <div id="password-match" class="conf-msg-box"></div>
            <button type="submit" name="register" class="login-btn">Daftar</button>
        </form>
        <div class="login-links">
            <div class="login-links-sub">
                <p>Anda bukan pencari kerja? </p><a href="register-company.php">Daftar sebagai perusahaan</a>
            </div>
            <div class="login-links-sub">
                <p>Sudah punya akun jobseeker? </p><a href="login-jobseeker.php">Login</a>
            </div>
        </div>
    </div>
    <script>
        const errorMessage = "<?php echo addslashes($errorMessage); ?>";
        const successMessage = "<?php echo addslashes($successMessage); ?>";
    </script>
    <script src="./public/error-message.js"></script>
    <script src="./public/success-message.js"></script>
    <script src="./public/password-validation.js"></script>
</body>
</html>