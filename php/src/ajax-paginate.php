<?php
    session_start();
    require_once '../db/db-connect.php';
    require '../includes/home-inc.php';
    $halamanAktif = $_GET['halaman'];
?>

<?php for ($i = 0; $i < count($lowonganList); $i++) :?>
    <article class="card" id=<?= $lowonganList[$i]["lowongan_id"];?>>
        <div class="keterangan">
            <h2><?= $lowonganList[$i]["posisi"];?></h2>
            <h3><?= getCompanyName($lowonganList[$i]["company_id"]);?></h3>
            <p>Jenis Pekerjaan: <?= $lowonganList[$i]["jenis_pekerjaan"];?></p>
            <p>Lokasi: <?= $lowonganList[$i]["jenis_lokasi"];?></p>
            <p>Diperbarui pada: <?= date('d-m-Y H:i:s', $lowonganList[$i]["updated_at"]);?></p>
        </div>
        <a class="detail" href="vacancy-details.php?lowongan_id=<?= $lowonganList[$i]["lowongan_id"];?>">Detail</a>
        
        <?php if ($_SESSION['role'] == 'company') : ?>
            <a class="edit" href="#">
                <img src="public/assets/images/edit.svg" alt="edit">
            </a>
            <a class="trash" href="#">
                <img src="public/assets/images/trash.svg" alt="hapus">
            </a>
        <?php endif ?>
    </article>
<?php endfor ?>