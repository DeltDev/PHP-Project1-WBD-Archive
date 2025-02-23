<?php
    require '../db/db-connect.php';
    if (isset($_SESSION["prev"])) { unset($_SESSION["prev"]); }
    
    // Fungsi memperoleh nama company dari id user
    function getCompanyName(int $x) : string {
        global $conn;
        $getNameCompanyQuery = "SELECT nama FROM \"user\" WHERE user_id = $x";
        $result = pg_query($conn, $getNameCompanyQuery);

        if ($result) {
            return pg_fetch_result($result, 0);
        }
        return "Error: " . pg_last_error($conn);
    }

    // filter
    function makeQuery(int $id) : string {
        $jenisPekerjaan = isset($_GET["jenis-pekerjaan"]) ? $_GET["jenis-pekerjaan"] : "all";
        $lokasi = isset($_GET["lokasi"]) ? $_GET["lokasi"] : "all";
        $posisi = isset($_GET["keyword"]) ? $_GET["keyword"] : '';

        $query = "SELECT * FROM lowongan";
        $whereClause = [];

        // Pengecekan lowongan milik company
        if ($id != -999) {
            $whereClause[] = "company_id = $id";
        }

        // Filter jenis pekerjaan
        if ($jenisPekerjaan != "all") {
            $jenisPekerjaanClause = explode(",", $jenisPekerjaan);
            foreach ($jenisPekerjaanClause as $key => $value) {
                $jenisPekerjaanClause[$key] = "jenis_pekerjaan = '" . $value . "'";
            }

            $whereClause[] = '(' . implode(" OR ", $jenisPekerjaanClause) . ')';
        }

        // Filter jenis lokasi
        if ($lokasi != "all") {
            $jenisLokasiClause = explode(",", $lokasi);
            foreach ($jenisLokasiClause as $key => $value) {
                $jenisLokasiClause[$key] = "jenis_lokasi = '" . $value . "'";
            }

            $whereClause[] = '(' . implode(" OR ", $jenisLokasiClause) . ')';
        }

        // Pencarian
        if ($posisi != '') {
            $whereClause[] = "LOWER(posisi) LIKE LOWER('%$posisi%')";
        }

        if (count($whereClause) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClause);
        }
        
        return $query;
    }

    // paginasi
    function countLowongan() : int {
        global $conn;
        
        if ($_SESSION["role"] == "jobseeker") {
            $result = pg_query($conn, makeQuery(-999)); 
        } else if (isset($_SESSION["user_id"])) {
            $result = pg_query($conn, makeQuery((int)$_SESSION["user_id"]));
        }
        $jumlahLowongan = (int)pg_num_rows($result);

        return $jumlahLowongan;
    }

    function countPage(int $jl) : int {
        $jumlahLowonganPerHalaman = 10;
        $jumlahHalaman = ceil($jl / $jumlahLowonganPerHalaman);

        return $jumlahHalaman;
    }

    function paginateAndAddQuery(string $query) : string {
        $jumlahLowonganPerHalaman = 10;
        $halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
        $start = $jumlahLowonganPerHalaman * ($halamanAktif - 1);
        
        $waktu = isset($_GET["waktu"]) ? $_GET["waktu"] : "ascending";
        $sort = $waktu == 'ascending' ? "ORDER BY updated_at DESC" : "ORDER BY updated_at ASC";

        $query .= " $sort LIMIT $jumlahLowonganPerHalaman OFFSET $start";

        return $query;
    }

    // variabel yang diperlukan
    $jumlahLowongan = countLowongan();
    $jumlahHalaman = countPage($jumlahLowongan);
    $halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $lowonganList = [];
        if ($_SESSION["role"] == "jobseeker") {
            $result = pg_query($conn, paginateAndAddQuery(makeQuery(-999)));
        } else if (isset($_SESSION["user_id"])) {
            $result = pg_query($conn, paginateAndAddQuery(makeQuery((int)$_SESSION["user_id"])));
        }

        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $lowonganList[] = array(
                    "lowongan_id" => (int)$row["lowongan_id"],
                    "company_id" => (int)$row["company_id"],
                    "posisi" => $row["posisi"],
                    "deskripsi" => $row["deskripsi"],
                    "jenis_pekerjaan" => $row["jenis_pekerjaan"],
                    "jenis_lokasi" => $row["jenis_lokasi"],
                    "is_open" => ($row["is_open"] == 't'),
                    "created_at" => strtotime($row["created_at"]),
                    "updated_at" => strtotime($row["updated_at"])
                );
            }
        }
    }
?>