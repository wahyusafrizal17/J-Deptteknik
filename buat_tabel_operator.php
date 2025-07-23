<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'durasi_listrik';
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) die('Koneksi gagal: ' . $conn->connect_error);
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);
$sql = "CREATE TABLE IF NOT EXISTS operator (
    id_operator VARCHAR(20) PRIMARY KEY,
    nama_operator VARCHAR(100) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo 'Tabel operator siap!';
} else {
    echo 'Gagal membuat tabel: ' . $conn->error;
}
$conn->close(); 