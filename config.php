<?php
// config.php

session_start(); // penting buat pakai $_SESSION

$host = "localhost";
$user = "root";          // sesuaikan
$pass = "";              // sesuaikan
$db   = "threekost_db";  // sesuaikan

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
