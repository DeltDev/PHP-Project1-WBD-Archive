<?php
    require '../includes/register-company-inc.php';
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
    <title>Daftar sebagai Perusahaan</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">

    <!--Quill JS-->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>
<body>
    
    <a href="#" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Daftar sebagai Perusahaan</h1>
        <div id="error-message" class="message-box error-message hidden"></div>
        <div id="success-message" class="message-box success-message hidden"></div>
        <form class="login-form" action="" method="post" autocomplete="off">
            <label for="">Nama Perusahaan</label>
            <input type="text" name="company-name" placeholder="Nama" required value="">
            <label for="">E-mail Perusahaan</label>
            <input type="email" name="company-email" placeholder="E-mail" required value="">
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
            <label for="">Lokasi Perusahaan</label>
            <input type="text" name="company-location" placeholder="Lokasi Perusahaan" required value="">
            <label for="company-about">Tentang Perusahaan</label>
            <div id="editor-container" style="height: 200px; width: 500px;" ></div>
            <input type="hidden" id="company-about" name="company-about">
            <button type="submit" name="register" class="login-btn">Daftar</button>
        </form>
        <div class="login-links">
            <div class="login-links-sub">
                <p>Anda bukan perusahaan? </p><a href="register-jobseeker.php">Daftar sebagai pencari kerja</a>
            </div>
            <div class="login-links-sub">
                <p>Sudah punya akun perusahaan? </p><a href="login-company.php">Login</a>
            </div>
        </div>
    </div>
    <script>
        const errorMessage = "<?php echo addslashes($errorMessage); ?>";
        const successMessage = "<?php echo addslashes($successMessage); ?>";
    </script>
    <script src="./public/error-message.js"></script>
    <script src="./public/success-message.js"></script>
    <script>
        var rte = new Quill('#editor-container',
            {
                theme: 'snow',
                placeholder: 'Tentang Perusahaan...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['clean']
                    ]
                }
            }
        );
        
        document.querySelector('form').onsubmit = function() {
            var jobDescInput = document.querySelector('input[name=company-about]');
            jobDescInput.value = rte.root.innerHTML;
        };
    </script>
    <script src="./public/password-validation.js"></script>
</body>
</html>