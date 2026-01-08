<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
session_start();
require '../config.php';
require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

if (!isset($_SESSION['user_id'])) {
    die("Error: Anda harus login untuk menambah barang.");
}

if (isset($_POST['submit'])) {

    $name        = $_POST['name'];
    $category_id = $_POST['category_id'];
    $stock       = $_POST['stock'];
    $price       = $_POST['price'];
    $user_id     = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        /* =========================
           1. UPLOAD GAMBAR KE GCS
           ========================= */

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
            throw new Exception("Gambar wajib diupload.");
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=/home/g2243058/web-inventaris/credentials.json');

        $bucketName = 'bucket_inventaris';
        $storage    = new StorageClient();
        $bucket     = $storage->bucket($bucketName);

        $fileTmp  = $_FILES['image']['tmp_name'];
        $fileName = 'product_' . time() . '_' . basename($_FILES['image']['name']);

        $bucket->upload(
            fopen($fileTmp, 'r'),
            ['name' => $fileName]
        );

        $imageUrl = "https://storage.googleapis.com/$bucketName/$fileName";

        /* =========================
           2. SIMPAN PRODUCT
           ========================= */

        $stmt = $pdo->prepare(
            "INSERT INTO products (name, category_id, stock, price, image)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $name,
            $category_id,
            $stock,
            $price,
            $imageUrl
        ]);

        $productId = $pdo->lastInsertId();

        /* =========================
           3. LOG STOK
           ========================= */

        $logSql = "INSERT INTO stock_logs 
                   (product_id, user_id, type, quantity, notes) 
                   VALUES (?, ?, 'masuk', ?, 'Stok awal barang baru')";

        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([$productId, $user_id, $stock]);

        $pdo->commit();

        /* =========================
           4. KIRIM NOTIFIKASI EMAIL VIA CLOUD RUN
           ========================= */
        $cloudRunUrl = "https://notify-94603138344.asia-southeast1.run.app/notify"; // Ganti dengan URL Cloud Run

        $payload = json_encode([
            "product" => $name,
            "qty" => $stock
        ]);

        $ch = curl_init($cloudRunUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            // Bisa log error atau notif admin
            error_log("Gagal kirim notifikasi: $curlError");
        } else {
            // Opsional: bisa log response Cloud Run
            error_log("Response Cloud Run: $response");
        }

        header("Location: ../product.php?msg=success_add");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Terjadi Kesalahan: " . $e->getMessage());
    }
}
