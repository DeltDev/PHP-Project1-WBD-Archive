<?php 
    require '../db/db-connect.php';
    require 'export-data.php';
    require '../includes/role-authorization-guard.php';
    checkAuthorization("company");
    if (isset($_GET['lowongan_id'])) {
        $lowongan_id = $_GET['lowongan_id'];
        $_SESSION["prev"] = "vacancy-details-company.php?lowongan_id=" . $lowongan_id;
        //cek apakah lowongan_id ada
        $lowongan_id_check_query = "SELECT * FROM lowongan WHERE lowongan_id = $lowongan_id";
        $res = pg_query($conn,$lowongan_id_check_query);
        
        if(pg_num_rows($res) <=0){ //cek apakah ada lowongan dengan id tersebut
            //jika tidak ada lowongan dengan id tersebut, langsung error 404
            http_response_code(404);
            header("Location: 404-notfound.php");
            exit();
        }
        $lowongan_data = pg_fetch_assoc($res);
        // cek siapa yang mengakses halaman ini
        if ((int)$lowongan_data["company_id"] != $_SESSION["user_id"]) {
            http_response_code(401);
            header("Location: 401-unauthorized.php");
            exit();
        }

        //dapatkan semua detail lowongan
        $lamaran_query = "SELECT nama, lamaran_id, lowongan_id, status FROM lamaran JOIN \"user\" ON lamaran.user_id = \"user\".user_id WHERE lowongan_id = $lowongan_id";
        $result = pg_query($conn,$lamaran_query);
        $jumlah_lamaran = pg_num_rows($result) ;

        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $lamaran[] = array(
                    "nama" => $row["nama"],
                    "lamaran_id" => $row["lamaran_id"],
                    "lowongan_id" => $row["lowongan_id"],
                    "status" => $row["status"]
                );
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan</title>
    <link rel="stylesheet" href="./public/assets/css/vacancy-details-company.css">
    
</head>
<header>
<div class="container">
    <h1><?= $lowongan_data["posisi"];?></h1>
    <div class="data-container">
        <div class="text-container">
            <p>Jenis Pekerjaan: <?=$lowongan_data["jenis_pekerjaan"]; ?></p>
            <p>Jenis Lokasi: <?=$lowongan_data["jenis_lokasi"]; ?></p>
            <p>Deskripsi Pekerjaan: <?=$lowongan_data["deskripsi"]; ?></p>
            <p>Status Lowongan: <?= $lowongan_data["is_open"] == "t" ? "Buka" :"Tutup" ?></p>
        </div>
    </div>
    
    <div class="tombol-container">                        
        <!-- Tombol Edit -->
        <form id="editForm-details-company" action="set-session.php" method="post">
            <input type="hidden" name="lowongan_id" value="<?= $lowongan_id; ?>">
        </form>
        <a class="edit" href="javascript:void(0)" onclick="submitForm('editForm-details-company')">
            <img src="public/assets/images/edit.svg" alt="Edit">
        </a>

        <!-- Tombol Hapus -->
        <form id="deleteForm-details-company" action="set-session.php" method="post">
            <input type="hidden" name="hapus-details-company" value="<?= $lowongan_id; ?>">
        </form>
        <a class="trash" href="javascript:void(0)" onclick="confirmDeleteDetails('deleteForm-details-company')">
            <img src="public/assets/images/trash.svg" alt="Delete">
        </a>

        <!-- Jika is_open true, maka tampilkan tombol "Tutup" -->
        <?php if ($lowongan_data['is_open'] == "t") : ?>
            <form id="tutupForm" action="set-session.php" method="post">
                <input type="hidden" name="tutupForm" value="<?= $lowongan_id; ?>">
            </form>
            <a class="tutup" href="javascript:void(0)" onclick="tutupForm('tutupForm')">
                <img src="public/assets/images/close.svg" alt="Tutup">
            </a>
        <?php else : ?>
            <form id="bukaForm" action="set-session.php" method="post">
                <input type="hidden" name="bukaForm" value="<?= $lowongan_id; ?>">
            </form>
            <a class="tutup" href="javascript:void(0)" onclick="bukaForm('bukaForm')">
                <img src="public/assets/images/open.svg" alt="Buka">
            </a>
        <?php endif; ?>
    </div>
</div>
</header>
<body>
    <a href="home-company.php" class="back-btn"><img src="./public/assets/images/back-button.svg" alt="back" width="100px"></a>
    <a href="home-company.php" class="linkin-logo">LinkinPurry</a>


    <main id="main">
        <div class="content-wrapper">
            <?php if ($jumlah_lamaran > 0) : ?>
                <content id="content">
                    <?php for ($i = 0; $i < count($lamaran); $i++) :?>
                        <article class="card" id=<?= $lamaran[$i]["lamaran_id"];?>>
                            <div class="keterangan">
                                <h2><?= $lamaran[$i]["nama"];?></h2>

                            </div>
                            <a class="detail" href="application-details.php?lamaran_id=<?=$lamaran[$i]["lamaran_id"];?>&lowongan_id=<?=$lowongan_id;?>">Detail</a>

                            <div class="status">
                                <p class="<?= $lamaran[$i]["status"]; ?>"><?= $lamaran[$i]["status"]; ?></p>
                            </div>

                        </article>
                    <?php endfor ?>
                    <form action="" method="POST" >
                        <button type="submit" class="export-btn" name="unduh-data" value=<?= $_SESSION["user_id"]?>>Unduh Data Pelamar</button>
                    </form>
                </content>
            <?php else : ?>
                <p class="no-result">Belum Ada Pelamar</p>
            <?php endif ?>
        </div>

        
    </main>
    <script src="public/home.js"></script>
</body>
</html>