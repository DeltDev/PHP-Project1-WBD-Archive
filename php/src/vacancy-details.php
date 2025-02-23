<?php 
    require '../db/db-connect.php';
    require '../includes/role-authorization-guard.php';
    checkAuthorization("jobseeker");

    if (isset($_GET['lowongan_id'])) {
        $lowongan_id = (int)$_GET['lowongan_id'];
        // echo($lowongan_id);
        //cek apakah lowongan_id ada
        $lowongan_id_check_query = "SELECT * FROM lowongan WHERE lowongan_id = $lowongan_id";
        $res = pg_query($conn,$lowongan_id_check_query);
        // $test = pg_fetch_assoc($res);
        // echo $test["posisi"];
        if(pg_num_rows($res) <=0){ //cek apakah ada lowongan dengan id tersebut
            //jika tidak ada lowongan dengan id tersebut, langsung error 404
            http_response_code(404);
            header("Location: 404-notfound.php");
            exit();
        }

        //dapatkan semua detail lowongan
        $lowongan_data = pg_fetch_assoc($res);
        //dapatkan data company lewat id company
        $company_id = $lowongan_data["company_id"];
        $company_id_check_query = "SELECT cd.user_id, nama, lokasi, about FROM \"user\" u JOIN company_detail cd ON cd.user_id = u.user_id WHERE cd.user_id = $company_id";
        $res = pg_query($conn,$company_id_check_query);

        if(pg_num_rows($res) <=0){ //cek apakah ada perusahaan dengan id tersebut
            //jika tidak ada company dengan id tersebut, langsung error 404
            http_response_code(404);
            header("Location: 404-notfound.php");
            exit();
        }
        //dapatkan semua detail company
        $company_data = pg_fetch_assoc($res);

        //dapatkan attachment lowongan
        
        $attachment_query = "SELECT file_path FROM attachment_lowongan WHERE lowongan_id = $lowongan_id";
        $res = pg_query($conn,$attachment_query);
        echo pg_num_rows($res);
        $attachments = [];

        while ($row = pg_fetch_assoc($res)) {
            $attachments[] = $row['file_path'];
        }
        if(count($attachments) === 0){ //cek apakah ada attachment
            //jika tidak ada attachment langsung error 404
            http_response_code(404);
            header("Location: 404-notfound.php");
            exit();
        }
        $attachment_data = pg_fetch_assoc($res);
    } else {
        http_response_code(404);
        header("Location: 404-notfound.php");
        exit();
    }

    function hasApplied($user_id, $lowongan_id){
        global $conn;
        $check_query = "SELECT * FROM lamaran WHERE user_id = $user_id AND lowongan_id = $lowongan_id";
        $res = pg_query($conn,$check_query);
        if(pg_num_rows($res)>0){
            return pg_fetch_assoc($res);
        }

        return false;
    }

    $existing_app = (isset($_SESSION["user_id"])) ? hasApplied($_SESSION["user_id"],$_GET["lowongan_id"]) : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan</title>
    <link rel="stylesheet" href="./public/assets/css/redirect-pages.css">
    
</head>
<body>
    <?php if (isset($_SESSION["prev"])) : ?>
        <a href="<?= $_SESSION["prev"]; ?>" class="back-btn"><img src="./public/assets/images/back-button.svg" alt="back" width="100px"></a>
    <?php else : ?>
        <a href="home-jobseeker.php" class="back-btn"><img src="./public/assets/images/back-button.svg" width="100px"></a>
    <?php endif ?>
    <a href="home-jobseeker.php" class="linkin-logo">LinkinPurry</a>
    <div class="container">
        <h1>Detail Lowongan Kerja</h1>
        <div class="data-container">
            
            <div class="text-container">
                <p>Posisi Pekerjaan: <?=$lowongan_data["posisi"]; ?></p>
                <p>Jenis Pekerjaan: <?=$lowongan_data["jenis_pekerjaan"]; ?></p>
                <p>Jenis Lokasi: <?=$lowongan_data["jenis_lokasi"]; ?></p>
                <p>Deskripsi Pekerjaan: <?=$lowongan_data["deskripsi"]; ?></p>
                <p>Dibuat pada: <?= date('d-m-Y H:i:s', strtotime($lowongan_data["created_at"])); ?></p>
                <p>Diperbarui pada: <?= date('d-m-Y H:i:s', strtotime($lowongan_data["updated_at"])); ?></p>
                <p>Status Lowongan: <?= $lowongan_data["is_open"] ? "Buka" :"Tutup" ?></p>
            </div>

            <div class="image-container">
                <?php foreach ($attachments as $attachment): ?>
                    <img src="<?= htmlspecialchars($attachment); ?>" alt="attachment">
                <?php endforeach; ?>
            </div>
        </div>
        
        
        <h1>Detail Perusahaan Pembuat Lowongan</h1>
        <div class="data-container">
            
            <div class="text-container">
                <p>Nama Perusahaan: <?=$company_data["nama"]; ?></p>
                <p>Lokasi Perusahaan: <?=$company_data["lokasi"]; ?></p>
                <p>Tentang Perusahaan: <?=$company_data["about"]; ?></p>
            </div>
        </div>
        <?php if ($existing_app):?>
            <h1>Detail Lamaran</h1>
            <div class="download-container">
                <div class="download-btn">
                    <a href="<?= $existing_app['cv_path'] ?>" target="_blank">Unduh CV</a>
                </div>
                <?php if ($existing_app['video_path']): ?>
                    <div class="download-btn">
                        <a href="<?= $existing_app['video_path'] ?>" target="_blank">Unduh Video</a>
                    </div>
                <?php endif; ?>
            </div>
            <h1>Status Lamaran</h1>
            <div class="data-container">
                <div class="text-container">
                    <p>Status: <?=$existing_app["status"] === "accepted" ? "Lamaran diterima" : ($existing_app["status"]==="waiting" ? "Lamaran masih menunggu untuk ditanggapi" : "Lamaran")?></p>
                    <p>Alasan <?=$existing_app["status"] === "accepted" ? "diterima" : ($existing_app["status"]==="waiting" ? "belum ditanggapi" : "ditolak")?>: <?=$existing_app["status_reason"]?></p>
                </div>
            </div>


        <?php else: ?>
            <div class="apply-btn">
                <?php if (!isset($_SESSION["name"])) : ?>
                    <a href="#" onclick="alert('Mohon login atau daftar dahulu')">Lamar</a>
                <?php else: ?>
                    <a href="apply-job.php?lowongan_id=<?= $lowongan_id;?>">Lamar</a>
                <?php endif ?>
            </div>
        <?php endif; ?>
        
    </div>
</body>
</html>