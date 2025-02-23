<?php
    require '../includes/role-authorization-guard.php';
    checkAuthorization("company");
    require '../includes/edit-vacancy-inc.php';
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    unset($_SESSION["error_message"]);
    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
    unset($_SESSION["success_message"]);

    $lowongan_id = $_SESSION['lowongan_id'] ;
    global $conn ;
    $query = "SELECT * FROM lowongan WHERE lowongan_id = $lowongan_id " ;
    $lowongan_isi = [];
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);
    
    if ($row) {
        $lowongan_isi = array(
            "lowongan_id" => (int)$row["lowongan_id"],
            "company_id" => (int)$row["company_id"],
            "posisi" => $row["posisi"],
            "deskripsi" => $row["deskripsi"],
            "jenis_pekerjaan" => $row["jenis_pekerjaan"],
            "jenis_lokasi" => $row["jenis_lokasi"],
            "is_open" => $row["is_open"] == 't',
            "created_at" => strtotime($row["created_at"]),
            "updated_at" => strtotime($row["updated_at"])
        );
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Lowongan</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">
    <!--Quill JS-->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>
<body>
    <?php if (isset($_SESSION["prev"])) : ?>
        <a href="<?= $_SESSION["prev"]; ?>"><img src="./public/assets/images/back-button.svg" alt="back" width="100px" class="back-btn"></a>
    <?php else : ?>
        <a href="home-company.php"><img src="./public/assets/images/back-button.svg" alt="back" width="100px" class="back-btn"></a>
    <?php endif ?>
    <a href="home-company.php" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Ubah Lowongan Pekerjaan</h1>
        <div id="error-message" class="message-box error-message <?php echo $errorMessage ? '' : 'hidden'; ?>"><?php echo $errorMessage; ?></div>
        <div id="success-message" class="message-box success-message <?php echo $successMessage ? '' : 'hidden'; ?>"><?php echo $successMessage; ?></div>

        <form class="login-form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <label>Jenis Pekerjaan</label>
            <div class="radio-section">
                <div class="radio-group">
                    <input type="radio" id="internship" name="job-types" value="internship" <?php if ($lowongan_isi["jenis_pekerjaan"] === 'internship') echo 'checked'; ?>>
                    <label for="internship" class="custom-radio">Internship/Magang</label>
                    <input type="radio" id="part-time" name="job-types" value="part-time" <?php if ($lowongan_isi["jenis_pekerjaan"] === 'part-time') echo 'checked'; ?>>
                    <label for="part-time" class="custom-radio">Part-time</label>
                    <input type="radio" id="full-time" name="job-types" value="full-time" <?php if ($lowongan_isi["jenis_pekerjaan"] === 'full-time') echo 'checked'; ?>>
                    <label for="full-time" class="custom-radio">Full-time</label>
                </div>
            </div>
            <label>Jenis Lokasi</label>
            <div class="radio-section">
                <div class="radio-group">
                    <input type="radio" id="on-site" name="job-locations" value="on-site" <?php if ($lowongan_isi["jenis_lokasi"] === 'on-site') echo 'checked'; ?>>
                    <label for="on-site" class="custom-radio">On-Site</label>
                    <input type="radio" id="hybrid" name="job-locations" value="hybrid" <?php if ($lowongan_isi["jenis_lokasi"] === 'hybrid') echo 'checked'; ?>>
                    <label for="hybrid" class="custom-radio">Hybrid</label>
                    <input type="radio" id="remote" name="job-locations" value="remote" <?php if ($lowongan_isi["jenis_lokasi"] === 'remote') echo 'checked'; ?>>
                    <label for="remote" class="custom-radio">Remote</label>
                </div>
            </div>

            <label for="job-position">Nama Posisi</label>
            <input type="text" id="job-position" name="job-position" value="<?php echo isset($lowongan_isi["posisi"]) ? htmlspecialchars($lowongan_isi["posisi"]) : '';?>" required>

            <label for="job-desc">Deskripsi Pekerjaan</label>
            <div id="editor-container" style="height: 200px; width: 500px;" ><?php echo isset($lowongan_isi["deskripsi"]) ? htmlspecialchars($lowongan_isi["deskripsi"]) : '';?></div>
            <input type="hidden" id="job-desc" name="job-desc">

            <label>Lampiran</label>
            <div class="upload-container">
                <div class="file-upload">
                    <label for="job-attachment" class="file-label" id="file-label">
                        <div class="button-text">Pilih gambar</div>
                    </label>
                    <input type="file" name="job-attachment[]" id="job-attachment" style="display: none;" multiple>
                </div>
                <span id="file-name" class="file-name">Belum ada file yang dipilih</span>
            </div>
            <button type="submit" name="create" class="login-btn">Ubah</button>
        </form>
    </div>
    <script>
        const errorMessage = "<?php echo addslashes($errorMessage); ?>";
        const successMessage = "<?php echo addslashes($successMessage); ?>";
    </script>
    <script src="./public/error-message.js"></script>
    <script src="./public/success-message.js"></script>
    <script src="./public/file-upload.js"></script>
    <script>
        var rte = new Quill('#editor-container',
            {
                theme: 'snow',
                placeholder: 'Deskripsi Pekerjaan...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['clean']
                    ]
                }
            }
        );
        
        var initialContent = "<?php echo addslashes($lowongan_isi["deskripsi"] ?? ''); ?>";
        rte.clipboard.dangerouslyPasteHTML(initialContent);

        document.querySelector('form').onsubmit = function() {
            document.querySelector('input[name=job-desc]').value = rte.root.innerHTML;
        };
    </script>
</body>
</html>
