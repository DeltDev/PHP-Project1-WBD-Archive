<?php
    require '../includes/login-inc.php';
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    unset($_SESSION["error_message"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login sebagai Jobseeker</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">
</head>
<body>
    <a href="#" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Login sebagai Jobseeker</h1>
        <!--error message-->
        <div id="error-message" class="message-box error-message hidden"></div>

        <form class="login-form" action="" method="post" autocomplete="off">
            <label>E-mail</label>
            <input type="email" name="email" placeholder="Email" required value="">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password" required value="">
            <input type="hidden" name="role" value="jobseeker">
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>
        <div class="login-links">
            <div class="login-links-sub">
                <p>Anda bukan pencari kerja? </p><a href="login-company.php">Login sebagai perusahaan</a>
            </div>
            <div class="login-links-sub">
                <p>Belum punya akun jobseeker? </p><a href="register-jobseeker.php">Daftar</a>
            </div>
            <div class="login-links-sub">
                <p>Masuk tanpa akun? </p><a href="login-no-account.php">Masuk</a>
            </div>
        </div>
    </div>
    <script>
        const errorMessage = "<?php echo addslashes($errorMessage); ?>";
    </script>
    <script src="./public/error-message.js"></script>
</body>
</html>