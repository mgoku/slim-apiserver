# Skeleton Server API menggunakan Slim 3

Ini adalah Skeleton server Restful API menggunakan Slim 3

## Install the Application

* Clone repo ini
* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.
* Untuk install dependency, jalankan:

	`composer install`

* Buat database kemudian import `sql/slimapiserver.sql` untuk membuat tabel users
* Copy file `src/settings.php.example` menjadi `src/settings.php` kemudian edit sesuai konfigurasi yang diinginkan

## Framework yang dipakai

* catfan/Medoo untuk komunikasi dengan database
* tuupola/slim-jwt-auth untuk auth JWT
* guzzlehttp/guzzle untuk http request

## Contoh - Contoh

* Untuk create user baru, panggil `http://localhost/slim/apiserver/public/adduser?username=admin&email=admin@localhost.com&password=password&type=ADMIN`
* Untuk cek setting timezone, panggil `http://localhost/slim/apiserver/public/tz`