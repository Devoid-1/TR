<?php
// logout.php
session_start();

// Hanya izinkan via POST (optional keamanan)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Hapus semua data session
session_unset();
session_destroy();

// Redirect kembali ke halaman utama
header("Location: index.php");
exit;
