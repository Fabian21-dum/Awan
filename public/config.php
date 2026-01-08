<?php

$dsn = sprintf(
    "mysql:unix_socket=/cloudsql/%s;dbname=%s;charset=utf8mb4",
    getenv('INSTANCE_CONNECTION_NAME'),
    getenv('DB_NAME')
);

$pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

    // echo "Koneksi Berhasil!";
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}

