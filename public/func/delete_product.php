<?php
session_start();
require '../config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        $stmtInfo = $pdo->prepare("SELECT name, stock FROM products WHERE id = ?");
        $stmtInfo->execute([$id]);
        $product = $stmtInfo->fetch();

        if ($product) {
            $logSql = "INSERT INTO stock_logs (product_id, user_id, type, quantity, notes) 
                       VALUES (?, ?, 'keluar', ?, ?)";
            $logStmt = $pdo->prepare($logSql);
            $note = "Penghapusan Produk: " . $product->name;
            $logStmt->execute([$id, $user_id, $product->stock, $note]);

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            $pdo->commit();
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal menghapus: " . $e->getMessage());
    }
}

header("Location: ../product.php?msg=success_delete");
exit;
