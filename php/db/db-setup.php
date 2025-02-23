<?php

    require '../env/loadenv.php';
    loadEnv(dirname(__DIR__,2). '/.env');
    //isi sendiri credential postgre di PC lu sendiri (isinya di file .env sekarang)
    $host = $_ENV["DB_HOST"];
    $port = $_ENV["DB_PORT"];
    $dbname = $_ENV["DB_INIT_NAME"];
    $user = $_ENV["DB_USER"];
    $password = $_ENV["DB_PASSWORD"];
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password"); //buat connect ke postgre
    $newDBName = $_ENV["DB_NAME"];
    if(!$conn){ //handling error
        die("Koneksi eror: " . pg_last_error());
    }
    
    //cek apakah database udah pernah dibuat atau blm pake query
    $searchNewDBQuery = "SELECT datname FROM pg_database WHERE datname = '$newDBName'";
    $searchNewDBResult = pg_query($conn,$searchNewDBQuery);
    if(!$searchNewDBResult || pg_num_rows($searchNewDBResult) == 0){ //databasenya belum ada
        //buat database baru
        $createNewDBQuery = "CREATE DATABASE $newDBName";
        if(!pg_query($conn,$createNewDBQuery)){
            die("Gagal buat database " . pg_last_error());
        }
        pg_close($conn);
        $conn = pg_connect("host=$host port=$port dbname=$newDBName user=$user password=$password"); //connect ke DB baru
        if(!$conn){
            die("gagal connect ke db baru: " . pg_last_error());
        }
        //buat tabel user
        $createUserTableQuery = 
        "CREATE TABLE IF NOT EXISTS \"user\"(
            user_id SERIAL PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(10) CHECK (role IN ('jobseeker', 'company')) NOT NULL,
            nama VARCHAR(255) NOT NULL
        )";
        pg_query($conn,$createUserTableQuery);
        //buat tabel Company detail
        $createCompDetailsQuery =
        "CREATE TABLE IF NOT EXISTS company_detail(
            user_id INT NOT NULL,
            lokasi VARCHAR(255) NOT NULL,
            about TEXT NOT NULL,
            PRIMARY KEY(user_id),
            FOREIGN KEY(user_id) REFERENCES \"user\"(user_id)
        )
        ";
        pg_query($conn,$createCompDetailsQuery);
        //buat tabel lowongan
        $createJobVacancyQuery = 
        "CREATE TABLE IF NOT EXISTS lowongan(
            lowongan_id SERIAL PRIMARY KEY,
            company_id INT NOT NULL,
            posisi VARCHAR(255) NOT NULL,
            deskripsi TEXT NOT NULL,
            jenis_pekerjaan VARCHAR(11) CHECK (jenis_pekerjaan IN ('internship', 'part-time', 'full-time')) NOT NULL,
            jenis_lokasi VARCHAR(11) CHECK (jenis_lokasi IN ('remote', 'hybrid', 'on-site')) NOT NULL,
            is_open BOOLEAN DEFAULT TRUE NOT NULL,
            created_at TIMESTAMP NOT NULL,
            updated_at TIMESTAMP NOT NULL,
            FOREIGN KEY(company_id) REFERENCES \"user\"(user_id)
        )
        ";
        pg_query($conn,$createJobVacancyQuery);
        //buat tabel attachment lowongan
        $createJVAttachmentQuery =
        "CREATE TABLE IF NOT EXISTS attachment_lowongan(
            attachment_id SERIAL PRIMARY KEY,
            lowongan_id INT NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            FOREIGN KEY(lowongan_id) REFERENCES lowongan(lowongan_id)
        )
        ";
        pg_query($conn,$createJVAttachmentQuery);
        //buat tabel lamaran
        $createApplicationQuery =
        "CREATE TABLE IF NOT EXISTS lamaran(
            lamaran_id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            lowongan_id INT NOT NULL,
            cv_path VARCHAR(255) NOT NULL,
            video_path VARCHAR(255),
            status VARCHAR(10) CHECK (status IN ('accepted', 'rejected', 'waiting')) DEFAULT 'waiting' NOT NULL,
            status_reason VARCHAR(255),
            created_at TIMESTAMP NOT NULL,
            FOREIGN KEY(lowongan_id) REFERENCES lowongan(lowongan_id),
            FOREIGN KEY(user_id) REFERENCES \"user\"(user_id)
        )
        ";
        pg_query($conn,$createApplicationQuery);
    }
    pg_close($conn);
?>