<?php
    require '../includes/role-authorization-guard.php';
    require '../db/db-connect.php';
    checkAuthorization("company");

    if(isset($_GET['lamaran_id']) && isset($_GET['lowongan_id'])){
        $lamaran_id = $_GET['lamaran_id'];
        $lowongan_id = $_GET['lowongan_id'];
        $lamaran_res = findLamaranByID($lamaran_id,$lowongan_id);
        if(pg_num_rows($lamaran_res) <=0){ //tidak ada lamaran dengan lamaran id yang bersangkutan, kirim error 404
            http_response_code(404);
            header("Location: 404-notfound.php");
            exit();
        }
        $lamaran_data = pg_fetch_assoc($lamaran_res); //dapatkan data lamaran

        //dapatkan nama dan email user
        $user_id = $lamaran_data["user_id"];
        $user_query = "SELECT nama, email FROM \"user\" WHERE user_id = '$user_id'";
        $user_res = pg_query($conn,$user_query);
        if(pg_num_rows($user_res) <=0){ //user dengan id yang tertera di lamaran tidak ada, kirim error 404
            http_response_code(404);
            header("Location: 404-notfound.php");
            exit();
        }

        $user_data = pg_fetch_assoc($user_res);
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["final"])){
            $acc_decision = $_POST["acc-decision"];
            $acc_reason = $_POST["acc-reason"];
            $acc_decision = pg_escape_string($conn,$acc_decision);
            $acc_reason = pg_escape_string($conn,$acc_reason);
            //update status lamaran diterima/ditolak
            $update_query = "UPDATE lamaran SET status ='$acc_decision', status_reason = '$acc_reason' WHERE lamaran_id = $1 AND lowongan_id = $2";
            $res = pg_query_params($conn,$update_query,array($lamaran_id,$lowongan_id));

            //stop biar gak bisa resend post lagi
            if($res){
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
        }
    } else {
        http_response_code(404);
        header("Location: 404-notfound.php");
        exit();
    }
    function findLamaranByID($lamaran_id,$lowongan_id){ //cari lamaran berdasarkan id lamaran
        global $conn;

        $search_query = "SELECT * FROM lamaran WHERE lamaran_id = $1 AND lowongan_id = $2";
        return pg_query_params($conn,$search_query,array($lamaran_id,$lowongan_id));
    }
    $cur_lamaran = pg_fetch_assoc(findLamaranByID($_GET['lamaran_id'],$_GET['lowongan_id']));
    if($cur_lamaran['status'] !== 'waiting'){
        $errorMessage = 'Anda sudah melakukan finalisasi terhadap penerimaan lamaran ini!';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lamaran</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">
</head>
<body>
    <a href="vacancy-details-company.php?lowongan_id=<?= $_GET["lowongan_id"]; ?>" class="back-btn"><img src="./public/assets/images/back-button.svg" alt="back" width="100px"></a>
    <a href="home-company.php" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Detail Lamaran</h1>
        <div id="error-message" class="message-box error-message hidden"></div>
        <label>Detail Pelamar</label>
        <div class="data-container">
            <div class="text-container">
                <p>Nama: <?=$user_data["nama"]; ?></p>
                <p>E-mail: <?=$user_data["email"]; ?></p>
            </div>
        </div>
        <label>Berkas Lamaran</label>
        <div class="download-container">
            <div class="download-btn">
                <a href="<?= $lamaran_data['cv_path'] ?>" target="_blank">Unduh CV</a>
            </div>
        </div>
        <?php if($lamaran_data['video_path']):?>
            <video height="240" controls>
                <source src="<?=$lamaran_data["video_path"];?>" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>
        <?php endif;?>
        <?php if($cur_lamaran['status'] === 'waiting'):?>
            <form class="login-form" method="POST" action="">
                <label>Keputusan Akhir Perusahaan</label>
                <div class="radio-section">
                    <div class="radio-group">
                        <input type="radio" id="accepted" name="acc-decision" value="accepted" required checked="checked">
                        <label for="accepted" class="custom-radio">Terima</label>
                        <input type="radio" id="rejected" name="acc-decision" value="rejected">
                        <label for="rejected" class="custom-radio">Tolak</label>
                    </div>
                </div>
                <label>Alasan Penerimaan/Penolakan</label>
                <input type="text" id="acc-reason" name="acc-reason" placeholder="Alasan Penerimaan/Penolakan" required>
                <button type="submit" name="final" class="login-btn">Finalisasi</button>
            </form>
        <?php else:?>
            <label>Keputusan Akhir Perusahaan</label>
            <div class="data-container">
                <div class="text-container">
                    <p>Status: <?=$cur_lamaran["status"] === 'accepted' ? 'Diterima':'Ditolak'; ?></p>
                </div>
            </div>
            <label>Alasan Penerimaan/Penolakan</label>
            <div class="data-container">
                <div class="text-container">
                    <p>Alasan: <?=$cur_lamaran["status_reason"];?></p>
                </div>
            </div>
        <?php endif;?>
    </div>
    <script>
        const errorMessage = "<?php echo addslashes($errorMessage); ?>";
    </script>
    <script src="./public/error-message.js"></script>
</body>
</html>