<?php
    require_once '../db/db-connect.php';
    session_start();
    if (!isset($_SESSION["name"]) || (isset($_SESSION["role"]) && $_SESSION["role"] === 'company')) {
        //company dan user tidak terautentikasi tidak boleh visit page ini
        http_response_code(401);
        header("Location: 401-unauthorized.php");
        exit();
    }
    
    $currentUser = $_SESSION["user_id"];
    $_SESSION["prev"] = "riwayat-jobseeker.php";

    function getCompanyName(int $x) : string {
        global $conn;
        $getNameCompanyQuery = "SELECT nama FROM \"user\" WHERE user_id = $x";
        $result = pg_query($conn, $getNameCompanyQuery);

        if ($result) {
            return pg_fetch_result($result, 0);
        }
        return "Error: " . pg_last_error($conn);
    }

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $dataLamaran = [];
        $query = "SELECT 
                    la.lamaran_id, 
                    lo.lowongan_id, 
                    la.status, 
                    lo.company_id,
                    lo.posisi, 
                    lo.jenis_pekerjaan, 
                    lo.jenis_lokasi, 
                    lo.updated_at, 
                    la.created_at
                FROM 
                    lamaran la
                INNER JOIN 
                    lowongan lo 
                    ON lo.lowongan_id = la.lowongan_id
                WHERE 
                    la.user_id = $currentUser
                ORDER BY la.created_at DESC";
        $result = pg_query($conn, $query);
        while ($row = pg_fetch_assoc($result)) {
            $dataLamaran[] = array(
                "lamaran_id" => (int)$row["lamaran_id"],
                "lowongan_id" => (int)$row["lowongan_id"],
                "status" => $row["status"],
                "posisi" => $row["posisi"],
                "company_id" => (int)$row["company_id"],
                "jenis_pekerjaan" => $row["jenis_pekerjaan"],
                "jenis_lokasi" => $row["jenis_lokasi"],
                "updated_at" => strtotime($row["updated_at"])
            );
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/assets/css/riwayat-jobseeker.css">
        <title>Riwayat</title>
    </head>

    <body>
        <header>
        <a href="home-jobseeker.php" class="back-button">
            <img src="public/assets/images/back-button.svg" alt="back">
        </a>        

        <a href="home-jobseeker.php" class="linkin-logo">LinkinPurry</a>
        </header>

        <main method="get">
            <?php if (count($dataLamaran) > 0): ?>
                <content>
                    <?php for ( $i = 0; $i < count($dataLamaran); $i++ ): ?>
                        <article class="card" id="lamaran<?= $dataLamaran[$i]["lamaran_id"]; ?>,lowongan<?= $dataLamaran[$i]["lowongan_id"]; ?>">
                            <div class="keterangan">
                                <h2><?= $dataLamaran[$i]["posisi"];?></h2>
                                <h3><?= getCompanyName($dataLamaran[$i]["company_id"]);?></h3>
                                <p>Jenis Pekerjaan: <?= $dataLamaran[$i]["jenis_pekerjaan"];?></p>
                                <p>Lokasi: <?= $dataLamaran[$i]["jenis_lokasi"];?></p>
                                <p>Diperbarui pada: <?= date('d-m-Y H:i:s', $dataLamaran[$i]["updated_at"]);?></p>
                            </div>
                            <div class="status">
                                <p class="<?= $dataLamaran[$i]["status"]; ?>"><?= $dataLamaran[$i]["status"]; ?></p>
                                <a href="vacancy-details.php?lowongan_id=<?= $dataLamaran[$i]["lowongan_id"];?>">Detail</a>
                            </div>
                        </article>
                    <?php endfor ?>
                </content>
            <?php else: ?>
                <p class="no-result">Tidak Ada Lamaran</p>
            <?php endif ?>
        </main>
    </body>
</html>