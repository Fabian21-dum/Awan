<?php

$host     = '127.0.0.1';
$port     = 3306;
$db_name  = 'inventaris_db';
$username = 'user_inventaris';
$password = 'Test123!';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);

    // echo "Koneksi Berhasil!";
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
