<?php
require 'config.php';

// kalau belum login, balikin ke index / buka login
if (!isset($_SESSION['user_id'])) {
    // bisa kamu ganti jadi set flag openLogin kalau mau
    header('Location: index.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];
$kostId = isset($_POST['kost_id']) ? (int)$_POST['kost_id'] : 0;

if ($kostId > 0) {
    // cek apakah sudah ada di wishlist
    $stmt = $conn->prepare(
        "SELECT 1 FROM wishlists WHERE user_id = ? AND kost_id = ?"
    );
    $stmt->bind_param("ii", $userId, $kostId);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($exists) {
        // kalau sudah ada → hapus (un-wishlist)
        $stmt = $conn->prepare(
            "DELETE FROM wishlists WHERE user_id = ? AND kost_id = ?"
        );
        $stmt->bind_param("ii", $userId, $kostId);
        $stmt->execute();
        $stmt->close();
    } else {
        // belum ada → tambahkan
        $stmt = $conn->prepare(
            "INSERT INTO wishlists (user_id, kost_id) VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $userId, $kostId);
        $stmt->execute();
        $stmt->close();
    }
}

// balik ke halaman sebelumnya (search_kost)
$redirect = $_SERVER['HTTP_REFERER'] ?? 'search_kost.php';
header('Location: ' . $redirect);
exit;
