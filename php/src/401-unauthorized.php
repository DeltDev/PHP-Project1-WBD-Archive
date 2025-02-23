<?php
    session_start();
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    if (http_response_code() !== 401) { //error unauthorized
        http_response_code(401);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <link rel="stylesheet" href="./public/assets/css/redirect-pages.css">
</head>
<body>
    <a href="#" class="linkin-logo">LinkinPurry</a>
    <div class="container">
        <h1>401 Unauthorized</h1>
        <p>Kamu tidak punya akses ke halaman ini.</p><br>
        <div class="links">
            <div class="links-sub">
                <a id="redirect" href=""></a>
            </div>
        </div>
    </div>
    <script>
        const loggedRole = "<?php echo isset($role) ? addslashes($role) : ''; ?>";
    </script>
    <script src = "./public/unauthorized.js"></script>
</body>
</html>