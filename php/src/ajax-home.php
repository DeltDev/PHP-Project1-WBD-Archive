<?php
    session_start();
    require_once '../db/db-connect.php';
    require '../includes/home-inc.php';
    $halamanAktif = $_GET['halaman'];
?>

<?php if ($jumlahLowongan > 0) : ?>
    <content id="konten">
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
    </content>
    
    <?php if ($jumlahHalaman > 1) : ?>
        <div class="pagination">
            <?php if ($jumlahHalaman > 5): ?>
                <button id="laquo" class="halaman" onclick="left(<?= $halamanAktif; ?>)" disabled="true">&laquo;</button>

                    <?php for ($i = 1; $i <= 5; $i++):?>
                        <?php if ($i == $halamanAktif) : ?>
                            <button class="halaman aktif" onclick="changePage(<?= $i; ?>)" disabled="true"><?= $i; ?></button>
                        <?php else : ?>
                            <button class="halaman" onclick="changePage(<?= $i; ?>)"><?= $i; ?></button>
                        <?php endif ?>
                    <?php endfor ?>

                <button id="raquo" class="halaman" jumlahHal="<?= $jumlahHalaman; ?>" onclick="right(<?= $halamanAktif; ?>,<?= $jumlahHalaman; ?>)" <?= ($halamanAktif == $jumlahHalaman) ? "disabled" : ""; ?>>&raquo;</button>
            <?php else: ?>
                <?php for ($i = 1; $i <= $jumlahHalaman; $i++):?>
                    <?php if ($i == $halamanAktif) : ?>
                        <button class="halaman aktif" disabled="true"><?= $i; ?></button>
                    <?php else : ?>
                        <button class="halaman" onclick="changePage(<?= $i; ?>)"><?= $i; ?></button>
                    <?php endif ?>
                <?php endfor ?>
            <?php endif ?>
        </div>
    <?php endif ?>
<?php else : ?>
    <p class="no-result">Tidak ditemukan lowongan</p>
<?php endif ?>