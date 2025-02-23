<?php
    session_start();

    if(!isset($_SESSION["is_login_success"])){ //jika sudah pernah visit page ini, jangan masuk ke page ini lagi
        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] === 'company') {
                header("Location: home-company.php");
                exit();
            } else if ($_SESSION["role"] === 'jobseeker') {
                header("Location: home-jobseeker.php");
                exit();
            }
        } else {
            header("Location: login-jobseeker.php");
            exit();
        }
    }
    unset($_SESSION["is_login_success"]);
    $role = $_SESSION["role"];
    $name = $_SESSION["name"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login berhasil</title>
    <link rel="stylesheet" href="./public/assets/css/redirect-pages.css">
</head>
<body>
    <a href="#" class="linkin-logo">LinkinPurry</a>
    <div class="container">
        <h1>Login berhasil!</h1>
        <div class="links">
            <div class="links-sub">
                <p id="log-success-msg"></p><a id="home-link" href=""></a>
            </div>
        </div>
    </div>
    <script>
        const loggedRole = "<?php echo addslashes($role); ?>";
        const loggedName = "<?php echo addslashes($name); ?>";
    </script>
    <script src = "./public/login-success.js"></script>
</body>
</html>