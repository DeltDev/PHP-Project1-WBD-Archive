<?php
    require '../db/db-connect.php';
    function editJobVacancy($position,$jobtype,$joblocation,$jobdesc,$company_id,$attachment){
        global $conn;
        //sanitize semua input bertipe string agar tidak terjadi SQL injection
        $position = pg_escape_string($conn,$position);
        $jobtype = pg_escape_string($conn,$jobtype);
        $joblocation = pg_escape_string($conn,$joblocation);
        $jobdesc = pg_escape_string($conn,$jobdesc);

        $lowongan_id = $_SESSION['lowongan_id'] ;

        $insert_vacancy_query = 
        "UPDATE lowongan 
        SET posisi = '$position',
            deskripsi = '$jobdesc',
            jenis_pekerjaan = '$jobtype',
            jenis_lokasi = '$joblocation',
            updated_at = NOW()
        WHERE lowongan_id = $lowongan_id";
        ;
        // query untuk menjaga apabila terjadi kesalahan
        $delete_vacancy_query = 
        "DELETE FROM lowongan WHERE lowongan.lowongan_id IN 
          (SELECT lowongan_id FROM lowongan ORDER BY created_at DESC LIMIT 1)";
        $res = pg_query($conn,$insert_vacancy_query);
        if(!$res){
            $_SESSION['error_message'] = "Gagal mendaftar: ' . pg_last_error($conn) . '";
            return;
        }
        
        
        if (isset($attachment["tmp_name"][0]) && is_uploaded_file($attachment["tmp_name"][0])) { //kalo attachmentnya juga diedit
            //hapus semua file attachment fisik lowongan yang berkaitan
            $delete_prev_attachment_query = "SELECT file_path FROM attachment_lowongan WHERE lowongan_id = $lowongan_id";
            $res = pg_query($conn,$delete_prev_attachment_query);
            while($row = pg_fetch_assoc($res)){
                unlink($row['file_path']);
            }
            pg_query($conn, "DELETE FROM attachment_lowongan WHERE lowongan_id = $lowongan_id"); //Hapus semua directory gambar dari database
            $target_dir = "./public/uploads/company-attachments/" . time();
            //unggah file
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
        }
        $_SESSION['success_message'] = "Berhasil mengubah lowongan kerja!";
        
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
         
        editJobVacancy($position,$jobtype,$joblocation,$jobdesc,$company_id,$attachment);
    }
    function checkPostMaxSizeExceeded() {
        if ($_SERVER['CONTENT_LENGTH'] > (int) ini_get('post_max_size') * 1024 * 1024) {
            return true;
        }
        return false;
    }
?>