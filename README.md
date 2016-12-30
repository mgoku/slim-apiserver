# Skeleton Server API menggunakan Slim 3

Ini adalah Skeleton server Restful API menggunakan Slim 3

## Install the Application

* Clone repo ini
* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.
* Jalankan.

	composer install

  untuk install dependency.

* Buat database kemudian import `sql/database.sql` untuk membuat tabel users
* Edit konfigurasi di file `src/settings.php`

## Framework yang dipakai

* catfan/Medoo untuk komunikasi dengan database
* tuupola/slim-jwt-auth untuk auth JWT
* guzzlehttp/guzzle untuk http request

## Contoh - Contoh

* Untuk create user baru, panggil `http://localhost/slim/apiserver/public/adduser?username=admin&password=password&type=ADMIN`
* Untuk cek setting timezone, panggil `http://localhost/slim/apiserver/public/tz`