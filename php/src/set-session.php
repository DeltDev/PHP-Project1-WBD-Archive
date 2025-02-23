<?php
require '../db/db-connect.php';
session_start();

// Simpan lowongan_id ke dalam SESSION
if (isset($_POST['lowongan_id'])) {
    $_SESSION['lowongan_id'] = $_POST['lowongan_id'];

    // Redirect ke halaman edit setelah menyimpan session
    header("Location: edit-vacancy.php");
    exit();
}

if (isset($_POST['hapus'])) {
    $temp = $_POST['hapus'] ;
    
    $hapus_lowongan = "SELECT file_path FROM attachment_lowongan WHERE lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);
    if ($res) {
        while ($row = pg_fetch_assoc($res)) {
            // Cek apakah file tersebut ada menggunakan file_exists()
            if (file_exists($row["file_path"])) {
                // Jika file ada, hapus file tersebut dengan unlink()
                unlink($row["file_path"]);
            }   
        }
    }
    
    $hapus_lamaran = "SELECT video_path, cv_path FROM lamaran WHERE lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lamaran);
    if ($res) {
        while ($row = pg_fetch_assoc($res)) {
            // Cek apakah file tersebut ada menggunakan file_exists()
            if (file_exists($row["cv_path"])) {
                // Jika file ada, hapus file tersebut dengan unlink()
                unlink($row["cv_path"]);
            } 
            if (file_exists($row["video_path"])) {
                // Jika file ada, hapus file tersebut dengan unlink()
                unlink($row["video_path"]);
            } 
        }
    }
    
    $hapus_lamaran = "DELETE FROM lamaran where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lamaran);
    
    $hapus_lowongan = "DELETE FROM attachment_lowongan where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);
    
    $hapus_lowongan = "DELETE FROM lowongan where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);
    
    // Redirect ke halaman company setelah menghapus lowongan
    header("Location: home-company.php");
    exit();
}

if (isset($_POST['hapus-details-company'])) {
    $temp = $_POST['hapus-details-company'] ;

    $hapus_lowongan = "SELECT file_path FROM attachment_lowongan WHERE lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);
    if ($res) {
        while ($row = pg_fetch_assoc($res)) {
             // Cek apakah file tersebut ada menggunakan file_exists()
            if (file_exists($row["file_path"])) {
                // Jika file ada, hapus file tersebut dengan unlink()
                unlink($row["file_path"]);
            } else {
                // Jika file tidak ada, tampilkan pesan bahwa file tidak ditemukan
            }
        }
    }

    $hapus_lamaran = "SELECT video_path, cv_path FROM lamaran WHERE lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lamaran);
    if ($res) {
        while ($row = pg_fetch_assoc($res)) {
             // Cek apakah file tersebut ada menggunakan file_exists()
            if (file_exists($row["cv_path"])) {
                // Jika file ada, hapus file tersebut dengan unlink()
                unlink($row["cv_path"]);
            } 
            if (file_exists($row["video_path"])) {
                // Jika file ada, hapus file tersebut dengan unlink()
                unlink($row["video_path"]);
            } 
        }
    }

    $hapus_lamaran = "DELETE FROM lamaran where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lamaran);

    $hapus_lowongan = "DELETE FROM attachment_lowongan where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);
    
    $hapus_lowongan = "DELETE FROM lowongan where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);

    // Redirect ke halaman company setelah menghapus lowongan
    header("Location: home-company.php");
    exit();
}

if (isset($_POST['tutupForm'])) {
    $temp = (int)$_POST['tutupForm'] ;

    $hapus_lowongan = "UPDATE lowongan SET is_open = false where lowongan_id = $temp";
    $res = pg_query($conn,$hapus_lowongan);

    $hapus_lamaran = "UPDATE lamaran SET status = 'rejected', status_reason = 'Lowongan sudah ditutup' WHERE lowongan_id = $temp AND status = 'waiting'";
    $res = pg_query($conn,$hapus_lamaran);

    // Redirect ke halaman company setelah menghapus lowongan
    header("Location: vacancy-details-company.php?lowongan_id=" . $temp);
    exit();
}

if (isset($_POST["bukaForm"])) {
    $temp = (int)$_POST["bukaForm"] ;

    $buka_lowngan = "UPDATE lowongan SET is_open = true WHERE lowongan_id = $temp";
    $res = pg_query($conn,$buka_lowngan);

    $buka_lamaran = "UPDATE lamaran SET status = 'waiting', status_reason = '' WHERE lowongan_id = $temp AND status = 'rejected'";
    $res = pg_query($conn,$buka_lamaran);

    header("Location: vacancy-details-company.php?lowongan_id=" . $temp);
    exit();
}
?>
