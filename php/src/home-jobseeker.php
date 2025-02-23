<?php
    session_start();
    require '../includes/home-inc.php';
    if (isset($_SESSION["role"]) && $_SESSION["role"] === 'company') { //company tidak boleh visit page ini
        http_response_code(401);
        header("Location: 401-unauthorized.php");
        exit();
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/assets/css/home.css">
        <title>Home</title>
    </head>
    <body method="get">
        <header>
            <div class="nav">
                <div class="hyperlink">
                    <!-- Jika sudah terdaftar -->
                    <?php if (isset($_SESSION["name"])): ?>
                        <a href="riwayat-jobseeker.php"><img src="public/assets/images/lamaranAnda-icon.svg" alt="Lamaran Anda"></a>
                        <a href="logout.php" class="log">Keluar</a>
                    <?php else : ?> 
                        <a href="redirect-register.php" class="log">Daftar</a>
                        <a href="logout.php" class="log">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="search-container">
                <span><img src="public/assets/images/search-icon.svg" alt="search-icon"></span>
                <input type="text" id="search" name="cari" placeholder="Cari..." autocomplete="off">
            </div>

            <form id="sort-filter" onchange="filterSort()">
                <div class="group">
                    <label class="judul">Jenis Pekerjaan: </label>
                    <input type="checkbox" id="jenis-pekerjaan-full-time" name="jenis-pekerjaan" value="full-time" checked onchange="cekOption('jenis-pekerjaan')">
                    <label for="jenis-pekerjaan-full-time" class="custom-checkbox">Full Time</label>
                    <input type="checkbox" id="jenis-pekerjaan-part-time" name="jenis-pekerjaan" value="part-time" checked onchange="cekOption('jenis-pekerjaan')">
                    <label for="jenis-pekerjaan-part-time" class="custom-checkbox">Part Time</label>
                    <input type="checkbox" id="jenis-pekerjaan-internship" name="jenis-pekerjaan" value="internship" checked onchange="cekOption('jenis-pekerjaan')">
                    <label for="jenis-pekerjaan-internship" class="custom-checkbox">Internship</label>
                </div>

                <div class="group">
                    <label class="judul">Lokasi: </label>
                    <input type="checkbox" id="lokasi-on-site" name="lokasi" value="on-site" checked onchange="cekOption('lokasi')">
                    <label for="lokasi-on-site" class="custom-checkbox">On Site</label>
                    <input type="checkbox" id="lokasi-hybrid" name="lokasi" value="hybrid" checked onchange="cekOption('lokasi')">
                    <label for="lokasi-hybrid" class="custom-checkbox">Hybrid</label>
                    <input type="checkbox" id="lokasi-remote" name="lokasi" value="remote" checked onchange="cekOption('lokasi')">
                    <label for="lokasi-remote" class="custom-checkbox">Remote</label>
                </div>

                <div class="group">
                    <label for="waktu" class="judul">Waktu: </label>
                    <select id="waktu" name="waktu">
                        <option value="ascending" selected>Terbaru</option>
                        <option value="descending">Terlama</option>
                    </select>
                </div>
            </form>
        </header>
        <main id="main">
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
        </main>
        <script src="public/home.js"></script>
    </body>
</html>