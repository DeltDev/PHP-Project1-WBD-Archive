<?php
    require '../includes/role-authorization-guard.php';
    checkAuthorization("jobseeker");
    require '../includes/apply-job-inc.php';
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    unset($_SESSION["error_message"]);
    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
    unset($_SESSION["success_message"]);

    function hasApplied($user_id, $lowongan_id){
        global $conn;
        $check_query = "SELECT * FROM lamaran WHERE user_id = $user_id AND lowongan_id = $lowongan_id";
        $res = pg_query($conn,$check_query);
        if(pg_num_rows($res)>0){
            return pg_fetch_assoc($res);
        }

        return false;
    }

    $existing_app = hasApplied($_SESSION["user_id"],$_GET["lowongan_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Lamaran</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">
</head>
<body>
    <a href="vacancy-details.php?lowongan_id=<?= $_GET["lowongan_id"];?>" class="back-btn"><img src="./public/assets/images/back-button.svg" alt="back" width="100px"></a>
    <a href="home-jobseeker.php" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Lamar Pekerjaan Ini</h1>
        <div id="error-message" class="message-box error-message <?php echo $errorMessage ? '' : 'hidden'; ?>"><?php echo $errorMessage; ?></div>
        <div id="success-message" class="message-box success-message <?php echo $successMessage ? '' : 'hidden'; ?>"><?php echo $successMessage; ?></div>
        <form class="login-form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <?php if($existing_app) :?>
                <p>Anda hanya dapat mengirimkan lamaran ke lowongan ini sebanyak <b>satu kali.</b></p>
                <p>Terima kasih telah mengirimkan lamaran ke pekerjaan ini!</p>
            <?php else:?>
                <label>Upload CV</label>
                <div class="upload-container">
                    <div class="file-upload">
                        <label for="cv-upload" class="file-label">
                            <div class="button-text">Pilih CV</div>
                        </label>
                        <input type="file" name="cv-upload" id="cv-upload" style="display: none;">
                    </div>
                    <span id="file-name1" class="file-name">Belum ada file yang dipilih</span>
                </div>
                <label>Upload Video</label>
                <div class="upload-container">
                    <div class="file-upload">
                        <label for="video-upload" class="file-label">
                            <div class="button-text">Pilih Video</div>
                        </label>
                        <input type="file" name="video-upload" id="video-upload" style="display: none;">
                    </div>
                    <span id="file-name2" class="file-name">Belum ada file yang dipilih</span>
                </div>

                <button type="submit" name="create" class="login-btn">Buat</button>
            <?php endif;?>
        </form>
    </div>
    <script src="./public/error-message.js"></script>
    <script src="./public/success-message.js"></script>
    <script src="./public/file-upload.js"></script>
</body>
</html>