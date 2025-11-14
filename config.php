<?php
// config.php

// Mulai session sekali saja
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi database
$host = "localhost";
$user = "root";          // default XAMPP/WAMP
$pass = "";              // kosong kalau belum di-set
$db   = "threekost_db";  // sesuaikan dengan nama DB kamu

// Konek ke MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Cek error koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
