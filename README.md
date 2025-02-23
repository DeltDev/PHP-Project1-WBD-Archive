# Tugas Besar Milestone I IF3110 Pengembangan Aplikasi Berbasis Web 2024/2025
> This project is a group assignment for Web Based Development class in Institut Teknologi Bandung. Developed in 3 weeks.
> Aplikasi LinkinPurry by Kelompok 13 K01

## Daftar Konten
* [Deskripsi Aplikasi](#deskripsi-aplikasi)
* [Daftar _Requirement_](#daftar-requirement)
* [Setup dan Penggunaan](#instalasi-dan-cara-menjalankan-web-app)
* [_Preview_ Aplikasi](#preview-aplikasi)
* [Setup Database](#setup-database)
* [Kreator](#anggota-kelompok-dan-pembagian-tugas)

## Deskripsi Aplikasi
Aplikasi LinkinPurry bertujuan membantu proses pencarian lowongan kerja. Platform ini diharapkan dapat membantu para pencari pekerjaan menemukan pekerjaan yang sesuai dengan keahlian mereka serta memberikan kemudahan dalam mengakses informasi lowongan yang ada.

## Daftar _Requirement_
1. Pengguna dapat mendaftar sebagai company atau job seeker
2. Company dapat mengubah profil perusahaan
3. Company dapat melihat daftar lowongan kerja yang dibuka olehnya
4. Company dapat membuat lowongan kerja baru
5. Company dapat mengubah lowongan kerja yang sudah ada
6. Company dapat menghapus lowongan kerja
7. Company dapat menutup lowongan kerja
8. Company dapat melihat daftar pelamar untuk suatu lowongan kerja
9. Company dapat menerima atau menolak pelamar pada suatu lowongan kerja beserta alasan atau langkah selanjutnya
10. Job seeker dapat melihat daftar lowongan yang ada
11. Job seeker dapat mencari lowongan
12. Job seeker dapat memfilter lowongan berdasarkan jenis pekerjaan dan tipe lokasi
13. Job seeker dapat menyortir lowongan berdasarkan waktu posting
14. Job seeker dapat melamar pekerjaan
15. Job seeker dapat melihat riwayat lamaran kerjanya
16. Pengguna yang tidak terautentikasi dapat melihat daftar lowongan seperti yang dilihat job seeker tanpa bisa melakukan aksi apapun

## Instalasi Dan Cara Menjalankan Web App
1. Unduh _source code_ aplikasi dari _release_ tag v1.3
2. Install Docker Dekstop dari [pranala ini](https://www.docker.com/products/docker-desktop/)
3. Buka Docker Desktop jika menggunakan windows dan pastikan Docker Desktop tetap menyala
4. Isi file ```.env``` sesuai dengan petunjuk yang ditulis di dalam file .env
5. Ketik ```docker-compose up``` ke terminal
6. Tunggu sampai tulisan ```web-1``` muncul di terminal
7. Buka ```http://localhost:8000``` di web browser
8. Aplikasi siap digunakan

## _Preview_ Aplikasi
1. register-jobseeker.php
![image](https://github.com/user-attachments/assets/2e10bd75-4cc4-4929-a214-e8af9827f087)

2. login-jobseeker.php
![image](https://github.com/user-attachments/assets/93a82a66-aea9-4f84-a626-0a373e49c6cf)

3. home-jobseeker.php
![image](https://github.com/user-attachments/assets/df48216a-5cf5-4123-bc4d-bee7590a4adf)

4. vacancy-details.php
![image](https://github.com/user-attachments/assets/ae2719b1-287f-43f0-b3ce-945cf3ee8799)

5. apply-job.php
![image](https://github.com/user-attachments/assets/94d01af6-69f9-41f8-bd30-224acdc97f15)

6. riwayat-jobseeker.php
![image](https://github.com/user-attachments/assets/89738f94-a8fa-46a5-a19b-e1dfd95100ef)

7. register-company
![image](https://github.com/user-attachments/assets/0e019e39-78b2-4a8e-9070-a4a9b007ceef)

8. login-company.php
![image](https://github.com/user-attachments/assets/30e4cc43-9d69-49f0-86fe-dcf854a91412)

9. home-company.php
![image](https://github.com/user-attachments/assets/0b88990c-f348-4017-9135-21f01c6f3a69)

10. profil-company.php
![image](https://github.com/user-attachments/assets/7cab2525-9671-4162-8103-598dbd5280ae)

11. edit-vacancy.php
![image](https://github.com/user-attachments/assets/01b5ac3a-20cc-4af6-89bc-64a39f87ff0b)

12. vacancy-details-company.php
![image](https://github.com/user-attachments/assets/380f7e3c-8ecc-4629-9517-4c23d07c9424)

13. create-vacancy.php
![image](https://github.com/user-attachments/assets/ced97bf8-7a0b-4f82-a1bd-9d11768a209f)

14. application-details.php
![image](https://github.com/user-attachments/assets/2859bc00-b36f-4f8a-a5a2-723814c4693b)

## Bonus yang Dikerjakan
1. Data Export
2. Lighthouse

## Anggota Kelompok dan Pembagian Tugas
| NIM | Nama | Tugas |
|-----|------| ------ |
| 13522033 | Bryan Cornelius Lauwrence | Home Jobseeker, Riwayat Lamaran, Pagination, Sort, Filter, Design UI, Integrasi |
| 13522036 | Akbar Al Fattah | Login, Register, Buat Lowongan, Detail Lamaran, Detail Lowongan, Buat Lamaran, Setup database, Docker, Bonus: Export Data Pelamar, Upload File |
| 13522049 | Vanson Kurnialim | Home Company, Ubah Lowongan, Profil Perusahaan, Detail Lowongan Company, Search - Debounce, Bonus : Lighthouse, Design UI | 


# Hasil Tangkap Layar Lighthouse
1. register-jobseeker.php
![image](https://github.com/user-attachments/assets/28867b1e-69cf-4ceb-9c55-d02b58e3e1bd)

2. login-jobseeker.php
![image](https://github.com/user-attachments/assets/bddf2811-00e2-4c5e-9517-314daf5f626b)

3. home-jobseeker.php
![image](https://github.com/user-attachments/assets/5683d1f4-d55b-4560-93c9-0f4fb5d720db)

Setelah melakukan perubahan berupa penambahan alt="" pada tag img, hasil yang didapatkan menjadi sebagai berikut : 
![image](https://github.com/user-attachments/assets/4469ed94-0f8e-44eb-8958-e4a758aaa3fd)

4. vacancy-details.php
![image](https://github.com/user-attachments/assets/72276ecf-ddc6-48dc-b548-03f2bfa0d368)

5. apply-job.php
![image](https://github.com/user-attachments/assets/a3f00100-5dfd-4d50-9cd4-051624085239)

6. riwayat-jobseeker.php
![image](https://github.com/user-attachments/assets/a8d92821-ad11-4367-afea-aea15662d68d)

7. register-company
![image](https://github.com/user-attachments/assets/997c9e5f-adcd-4296-82b6-827f3bb09e2a)

8. login-company.php
![image](https://github.com/user-attachments/assets/82087af2-41b7-48a9-be02-7fac9b13d34e)

9. home-company.php
![image](https://github.com/user-attachments/assets/bdd04216-de27-4dc0-9bbc-767f84b3a16a)

10. profil-company.php
![image](https://github.com/user-attachments/assets/ff0635d7-a0ed-44a2-ac53-0e6acae492b3)

11. edit-vacancy.php
![image](https://github.com/user-attachments/assets/7b9563c6-556b-48f3-b484-b5f1a15a8773)

12. vacancy-details-company.php
![image](https://github.com/user-attachments/assets/e2442966-5ee2-42ba-a954-6caf98888a08)

13. create-vacancy.php
![image](https://github.com/user-attachments/assets/d15b775e-ab52-41e9-ab1d-6921b22f0d21)

14. application-details.php
![image](https://github.com/user-attachments/assets/40b1a202-f000-4af0-a9ec-c79d97641ce3)
