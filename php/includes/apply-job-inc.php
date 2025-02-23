<?php
    require '../db/db-connect.php';
    function createJobApplication($applicant_cv, $applicant_vid){
        global $conn;
        if($applicant_cv['error'] == UPLOAD_ERR_NO_FILE || $applicant_cv['size'] == 0){
            $_SESSION["error_message"] = "Anda belum mengunggah CV!";
            return false; 
        }

        //unggah cv
        $target_dir = "./public/uploads/applicant-cv/" . time();
        $file_name = basename($applicant_cv["name"]);
        $file_name = str_replace(' ', '_', $file_name);
        $target_file_cv = $target_dir . $file_name;
        $cvFileType = strtolower(pathinfo($target_file_cv,PATHINFO_EXTENSION));
 
        if($cvFileType != "pdf") {
            http_response_code(400);
            $_SESSION["error_message"] = "CV hanya boleh berformat .pdf!";
            return;
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $cv_mime = $finfo->file($applicant_cv["tmp_name"]);
        if ($cv_mime !== 'application/pdf') {
            http_response_code(400);
            $_SESSION["error_message"] = "File PDF yang Anda unggah palsu! Silakan unggah file PDF sungguhan.";
            return false;
        }
        $target_file_vid = null;
        if($applicant_vid['error'] != UPLOAD_ERR_NO_FILE){ //jika upload video juga
            $target_dir = "./public/uploads/applicant-video/" . time();
            $file_name = basename($applicant_vid["name"]);
            $file_name = str_replace(' ', '_', $file_name);
            $target_file_vid = $target_dir . $file_name;
            $vidFileType = strtolower(pathinfo($target_file_vid,PATHINFO_EXTENSION));

            if($vidFileType != "mp4") {
                http_response_code(400);
                $_SESSION["error_message"] = "Video perkenalan hanya boleh berformat .mp4!";
                return;
            } 
            $vid_mime = $finfo->file($applicant_vid["tmp_name"]);
            if ($vid_mime !== 'video/mp4') {
                http_response_code(400);
                $_SESSION["error_message"] = "File mp4 yang Anda unggah palsu! Silakan unggah file mp4 sungguhan.";
                return false;
            }
            if (!move_uploaded_file($applicant_vid["tmp_name"], $target_file_vid)) {
                http_response_code(500);
                $_SESSION["error_message"] = "Mohon maaf, ada kesalahan saat mengunggah lampiran";
                return;
            }
        }
        if (!move_uploaded_file($applicant_cv["tmp_name"], $target_file_cv)) {
            http_response_code(500);
            $_SESSION["error_message"] = "Mohon maaf, ada kesalahan saat mengunggah lampiran";
            return;
        }

        $cv_path = pg_escape_string($conn,$target_file_cv);
        $video_path = $target_file_vid ? pg_escape_string($conn,$target_file_vid) : null;
        $user_id = $_SESSION["user_id"];
        $lowongan_id = $_GET["lowongan_id"];
        $create_lamaran_query = "INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, created_at) 
            VALUES ('$user_id', '$lowongan_id', '$cv_path', " . ($video_path ? "'$video_path'" : "NULL") . ", 'waiting', NOW())";

        $res = pg_query($conn,$create_lamaran_query);

        if(!$res){
            $_SESSION['error_message'] = "Gagal membuat lamaran: ' . pg_last_error($conn) . '";
            return;
        }
        $_SESSION["success_message"] = "Lamaran berhasil diunggah!";
        return;
    }
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create"])){

        //dapatkan semua data
        if (checkPostMaxSizeExceeded()) {
            $_SESSION['error_message'] = "Ukuran data terlalu besar. Maksimum " . ini_get('post_max_size');
            exit;
        }
        $applicant_cv = $_FILES["cv-upload"];
        $applicant_vid = $_FILES["video-upload"];
        
        createJobApplication($applicant_cv,$applicant_vid);
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