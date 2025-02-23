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
    <title>Login sebagai Perusahaan</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">
</head>
<body>
    <a href="#" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Login sebagai Perusahaan</h1>
        <!--error message-->
        <div id="error-message" class="message-box error-message hidden"></div>
        <form class="login-form" action="" method="post" autocomplete="off">
            <label>E-mail</label>
            <input type="email" name="email" placeholder="E-mail" required value="">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password" required value="">
            <input type="hidden" name="role" value="company">
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>
        <div class="login-links">
            <div class="login-links-sub">
                <p>Anda bukan perusahaan? </p><a href="login-jobseeker.php">Login sebagai Jobseeker</a>
            </div>
            <div class="login-links-sub">
                <p>Belum punya akun perusahaan? </p><a href="register-company.php">Daftar</a>
            </div>
        </div>
    </div>
    <script>
        const errorMessage = "<?php echo addslashes($errorMessage); ?>";
    </script>
    <script src="./public/error-message.js"></script>
</body>
</html>
