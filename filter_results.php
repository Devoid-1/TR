<?php
require 'config.php'; // sudah ada $conn

// Ambil value dari GET
$bathroom_type = $_GET['bathroom_type'] ?? '';
$parking       = isset($_GET['parking']);
$wifi          = isset($_GET['wifi']);
$gender_type   = $_GET['gender_type'] ?? '';
$ac            = isset($_GET['ac']);
$kitchen       = isset($_GET['kitchen']);
$price_range   = $_GET['price_range'] ?? '';
$city          = $_GET['city'] ?? '';
$q             = $_GET['q'] ?? '';

// Mulai query dasar
$sql = "SELECT * FROM kosts WHERE 1=1";

// Tambah kondisi dinamis
if ($bathroom_type !== '') {
    $bathroom_type = $conn->real_escape_string($bathroom_type);
    $sql .= " AND bathroom_type = '$bathroom_type'";
}

if ($parking) {
    $sql .= " AND parking = 1";
}

if ($wifi) {
    $sql .= " AND wifi = 1";
}

if ($gender_type !== '') {
    $gender_type = $conn->real_escape_string($gender_type);
    $sql .= " AND gender_type = '$gender_type'";
}

if ($ac) {
    $sql .= " AND ac = 1";
}

if ($kitchen) {
    $sql .= " AND kitchen = 1";
}

if ($city !== '') {
    $city = $conn->real_escape_string($city);
    $sql .= " AND city = '$city'";
}

// Range harga
if ($price_range !== '') {
    switch ($price_range) {
        case '100-500':
            $sql .= " AND price_month BETWEEN 100000 AND 500000";
            break;
        case '500-1000':
            $sql .= " AND price_month BETWEEN 500000 AND 1000000";
            break;
        case '1000-1500':
            $sql .= " AND price_month BETWEEN 1000000 AND 1500000";
            break;
        case '1500-2000':
            $sql .= " AND price_month BETWEEN 1500000 AND 2000000";
            break;
        case '2000-2500':
            $sql .= " AND price_month BETWEEN 2000000 AND 2500000";
            break;
        case '2500-3000':
            $sql .= " AND price_month BETWEEN 2500000 AND 3000000";
            break;
    }
}

// Keyword search (nama / alamat)
if ($q !== '') {
    $q_esc = $conn->real_escape_string($q);
    $sql .= " AND (name LIKE '%$q_esc%' OR address LIKE '%$q_esc%')";
}

// Eksekusi query
$result = $conn->query($sql);

// Nanti di bawah sini kamu tinggal looping $result untuk bikin card kost
?>
