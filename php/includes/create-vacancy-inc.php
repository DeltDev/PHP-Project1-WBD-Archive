<?php
    require '../db/db-connect.php';
    function createJobVacancy($position,$jobtype,$joblocation,$jobdesc,$company_id,$attachment){
        global $conn;
        //sanitize semua input bertipe string agar tidak terjadi SQL injection
        $position = pg_escape_string($conn,$position);
        $jobtype = pg_escape_string($conn,$jobtype);
        $joblocation = pg_escape_string($conn,$joblocation);
        $jobdesc = pg_escape_string($conn,$jobdesc);
 
        $insert_vacancy_query = 
        "INSERT INTO 
        lowongan(company_id,posisi,deskripsi,jenis_pekerjaan,jenis_lokasi,created_at,updated_at) 
        VALUES ('$company_id','$position','$jobdesc','$jobtype','$joblocation',NOW(),NOW())";
        // query untuk menjaga apabila terjadi kesalahan
        $delete_vacancy_query = 
        "DELETE FROM lowongan WHERE lowongan.lowongan_id IN 
          (SELECT lowongan_id FROM lowongan ORDER BY created_at DESC LIMIT 1)";

        $res = pg_query($conn,$insert_vacancy_query);
        if(!$res){
            $_SESSION['error_message'] = "Gagal mendaftar: ' . pg_last_error($conn) . '";
            return;
        }
        // masukkan path attachment ke db
        $lowongan_id_query = "SELECT lowongan_id FROM lowongan WHERE posisi='$position' AND deskripsi='$jobdesc' AND jenis_pekerjaan='$jobtype' AND jenis_lokasi='$joblocation' AND company_id='$company_id'";
        $res = pg_query($conn,$lowongan_id_query);
        $lowongan_data = pg_fetch_assoc($res);
        $lowongan_id =$lowongan_data["lowongan_id"];
        //unggah file
        $target_dir = "./public/uploads/company-attachments/" . time();
        foreach ($attachment['name'] as $key => $filename) {
            $file_tmp = $attachment['tmp_name'][$key];
            $file_name = basename($filename);
            $file_name = str_replace(' ', '_', $file_name);
            $target_file = $target_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($file_tmp);            
            if($check === false && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                http_response_code(400);
                $_SESSION["error_message"] = "Lampiran hanya boleh berupa gambar dengan format .jpg, .jpeg, dan .png";
                $del = pg_query($conn,$delete_vacancy_query);
                return;
            } 
            //cek apakah gambar asli atau palsu meskipun extensionnya benar
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $image_mime = $finfo->file($file_tmp);
            
            if(!($image_mime === 'image/jpeg' || $image_mime === 'image/png')){
                http_response_code(400);
                $_SESSION["error_message"] = "Gambar yang Anda unggah palsu! Silakan upload file .jpg/.jpeg/.png asli.";
                $del = pg_query($conn,$delete_vacancy_query);
                return;
            }//ada kesalahan penyimpanan dari file
            if (move_uploaded_file($file_tmp, $target_file)) {
                $insert_attachment_query = "INSERT INTO attachment_lowongan(lowongan_id, file_path) VALUES('$lowongan_id', '$target_file')";
                $res = pg_query($conn, $insert_attachment_query);
                if (!$res) {
                    $_SESSION['error_message'] = "Gagal menyimpan lampiran: " . pg_last_error($conn);
                    $del = pg_query($conn,$delete_vacancy_query);
                    continue;
                }
            } else {
                $_SESSION["error_message"] = "Mohon maaf, ada kesalahan saat mengunggah lampiran";
                $del = pg_query($conn,$delete_vacancy_query);
            }
        }

        $_SESSION['success_message'] = "Berhasil membuat lowongan kerja baru!";
        return;
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create"])){

        //dapatkan semua data
        if (checkPostMaxSizeExceeded()) {
            $_SESSION['error_message'] = "Ukuran data terlalu besar. Maksimum " . ini_get('post_max_size');
            exit;
        }
        $position = $_POST["job-position"];
        $jobtype = $_POST["job-types"];
        $joblocation = $_POST["job-locations"];
        $jobdesc = $_POST["job-desc"];
        $company_id = $_SESSION["user_id"];
        $attachment = $_FILES["job-attachment"];
         
        createJobVacancy($position,$jobtype,$joblocation,$jobdesc,$company_id,$attachment);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
    function checkPostMaxSizeExceeded() {
        if ($_SERVER['CONTENT_LENGTH'] > (int) ini_get('post_max_size') * 1024 * 1024) {
            return true;
        }
        return false;
    }
?>