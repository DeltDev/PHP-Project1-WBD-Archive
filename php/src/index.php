<?php
//setup db jika belum pernah dibuat
if (!file_exists('../db/db_initialized.lock')) {
    include_once '../db/db-setup.php';
    file_put_contents('../db/db_initialized.lock', 'Setup DB selesai');
}

//redirect ke register user
header("Location: login-jobseeker.php");
die();
?>