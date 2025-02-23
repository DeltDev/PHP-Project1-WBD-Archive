<?php
    require '../db/db-connect.php';
    session_start();
    if (!isset($_SESSION["name"])) { //user belum login

        header("Location: login-company.php");
        exit();
    }

    if ($_SESSION["role"] !== 'company') { //rolenya salah
        http_response_code(401);
        header("Location: 401-unauthorized.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST") {
        $dataCompany = [];
        $temp = $_SESSION["user_id"] ;
        $getDataCompany = "SELECT * FROM company_detail WHERE user_id = $temp";
        $result = pg_query($conn, $getDataCompany) ;
        $row = pg_fetch_assoc($result) ;
        if ($row) {
            $dataCompany[] = array(
                "user_id" => (int)$row["user_id"],
                "lokasi" => $row["lokasi"],
                "about" => $row["about"]
            );
        }
        else {
            // Jika tidak ada data yang ditemukan
            $dataCompany[] = array(
                "user_id" => $temp,
                "lokasi" => "Tidak ada data",
                "about" => "Tidak ada data"
            );
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['new_location'])) {
            $new_location = $_POST['new_location'];

            $temp = $_SESSION["user_id"];
            
            $updateLocationQuery = "UPDATE company_detail SET lokasi = '$new_location' WHERE user_id = $temp";
            pg_query($conn, $updateLocationQuery);

            $dataCompany[0]["lokasi"] = $new_location;
        }

        if (isset($_POST['new_about'])) {
            $new_about = $_POST['new_about'];

            $temp = $_SESSION["user_id"];
            
            $updateAboutQuery = "UPDATE company_detail SET about = '$new_about' WHERE user_id = $temp";
            pg_query($conn, $updateAboutQuery);

            $dataCompany[0]["about"] = $new_about;
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/assets/css/profil-company.css">
    <title>Home</title>
</head>
<body>
    <header>
        <a href="home-company.php" class="back-button">
            <img src="public/assets/images/back-button.svg" alt="back">
        </a>        

        <a href="home-company.php" class="linkin-logo">LinkinPurry</a>
    </header>
    <main method="get">
    <div class="container">
        <div class="box">
            <div class="nama-perusahaan">
                <h1><?php echo $_SESSION["name"]; ?></h2>
            </div>

            <div class="company">
                <h2>Lokasi </h2>
                <p> <?php echo $dataCompany[0]["lokasi"];?> </p>

                <button id="edit-button-lokasi">Edit</button>
                <form id="edit-form-lokasi" action="" method="POST" autocomplete="off" style="display:none;">
                    <input type="text" name="new_location" value="<?php echo $dataCompany[0]["lokasi"];?>">
                    <button type="submit">Simpan</button>
                </form>
            </div>

            <div class="company">
                <h2>About </h2>
                <p> <?php echo $dataCompany[0]["about"];?> </p>

                <button id="edit-button-about">Edit</button>
                <form id="edit-form-about" action="" method="POST" autocomplete="off" style="display:none;">
                    <input type="text" name="new_about" value="<?php echo $dataCompany[0]["about"];?>">
                    <button type="submit">Simpan</button>
                </form>
            </div>

            <script>
                // Script untuk menampilkan form saat tombol edit ditekan
                document.getElementById('edit-button-lokasi').addEventListener('click', function() {
                    document.getElementById('edit-form-lokasi').style.display = 'block';
                    document.getElementById('edit-button-lokasi').style.display = 'none'; // nyembunyiin tombol edit
                });
                document.getElementById('edit-button-about').addEventListener('click', function() {
                    document.getElementById('edit-form-about').style.display = 'block';
                    document.getElementById('edit-button-about').style.display = 'none'; // nyembunyiin tombol edit
                });
            </script>
        
        </div>
    </div>
    </main>
</body>
</html>

