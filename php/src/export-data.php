<?php
    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        ob_start();
        require '../db/db-connect.php';
        // $temp = $_SESSION["user_id"];
        $temp = $_POST["unduh-data"] ;
        $data_query = 
        "SELECT 
        u.nama AS nama_pelamar, 
        lo.posisi AS pekerjaan_yang_dilamar, 
        la.created_at AS tanggal_melamar, 
        la.cv_path AS url_cv, 
        la.video_path AS url_video 
        FROM \"user\" u, lamaran la, lowongan lo 
        WHERE la.user_id = u.user_id 
        AND la.lowongan_id = lo.lowongan_id 
        AND lo.company_id = $temp";
        $res = pg_query($conn, $data_query);
        if (!$res) {
            echo 'error';
            exit();
        }
        $file_name = "Data_Pelamar_" . date("Y-m-d") . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nama Pelamar', 'Pekerjaan yang Dilamar', 'Tanggal Melamar', 'CV URL', 'Video URL']);
        while ($row = pg_fetch_assoc($res)) {
            //ganti relative path menjadi absolute path
            $row['url_cv'] = str_replace('./', 'http://localhost:8000/', $row['url_cv']);
            if($row['url_video'] === null){
                $row['url_video'] = "Tidak ada video";
            } else {
                $row['url_video'] = str_replace('./', 'http://localhost:8000/', $row['url_video']);
            }
            
            fputcsv($output, $row);
        }
        fclose($output);
        ob_end_flush();
        exit();
    }
?>