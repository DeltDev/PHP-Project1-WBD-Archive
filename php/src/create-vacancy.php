<?php
    require '../includes/role-authorization-guard.php';
    checkAuthorization("company");
    require '../includes/create-vacancy-inc.php';
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    unset($_SESSION["error_message"]);
    $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
    unset($_SESSION["success_message"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Lowongan</title>
    <link rel="stylesheet" href="./public/assets/css/login-register.css">

    <!--Quill JS-->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>
<body>
    <a href="home-company.php" class="back-btn"><img src="./public/assets/images/back-button.svg" alt="back" width="100px"></a>
    <a href="home-company.php" class="linkin-logo">LinkinPurry</a>
    <div class="login-container">
        <h1>Buat Lowongan Pekerjaan</h1>
        <div id="error-message" class="message-box error-message <?php echo $errorMessage ? '' : 'hidden'; ?>"><?php echo $errorMessage; ?></div>
        <div id="success-message" class="message-box success-message <?php echo $successMessage ? '' : 'hidden'; ?>"><?php echo $successMessage; ?></div>
 
        <form class="login-form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <label>Jenis Pekerjaan</label>
            <div class="radio-section">
                <div class="radio-group">
                    <input type="radio" id="internship" name="job-types" value="internship" required checked="checked">
                    <label for="internship" class="custom-radio">Internship/Magang</label>
                    <input type="radio" id="part-time" name="job-types" value="part-time">
                    <label for="part-time" class="custom-radio">Part-time</label>
                    <input type="radio" id="full-time" name="job-types" value="full-time">
                    <label for="full-time" class="custom-radio">Full-time</label>
                </div>
            </div>
            <label>Jenis Lokasi</label>
            <div class="radio-section">
                <div class="radio-group">
                    <input type="radio" id="on-site" name="job-locations" value="on-site" required checked="checked">
                    <label for="on-site" class="custom-radio">On-Site</label>
                    <input type="radio" id="hybrid" name="job-locations" value="hybrid">
                    <label for="hybrid" class="custom-radio">Hybrid</label>
                    <input type="radio" id="remote" name="job-locations" value="remote">
                    <label for="remote" class="custom-radio">Remote</label>
                </div>
            </div>

            <label for="job-position">Nama Posisi</label>
            <input type="text" id="job-position" name="job-position" placeholder="Nama Posisi" required>

            <label for="job-desc">Deskripsi Pekerjaan</label>
            <div id="editor-container" style="height: 200px; width: 500px;" ></div>
            <input type="hidden" id="job-desc" name="job-desc">
            
            <label>Lampiran</label>
            <div class="upload-container">
                <div class="file-upload">
                    <label for="job-attachment" class="file-label" id="file-label">
                        <div class="button-text">Pilih gambar</div>
                    </label>
                    <input type="file" name="job-attachment[]" id="job-attachment" style="display: none;" required multiple>
                </div>
                <span id="file-name" class="file-name">Belum ada file yang dipilih</span>
            </div>
            <button type="submit" name="create" class="login-btn">Buat</button>
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
        
        document.querySelector('form').onsubmit = function() {
            var jobDescInput = document.querySelector('input[name=job-desc]');
            jobDescInput.value = rte.root.innerHTML;
        };
    </script>
</body>
</html>
