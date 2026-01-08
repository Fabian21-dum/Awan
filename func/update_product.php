<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['update'])) {
    $id          = $_POST['id']; 
    $name        = $_POST['name'];
    $category_id = $_POST['category_id'];
    $stock_new   = (int)$_POST['stock']; 
    $price       = $_POST['price'];
    $user_id     = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        $stmtOld = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmtOld->execute([$id]);
        $stock_old = $stmtOld->fetchColumn();

        $sql = "UPDATE products SET name = ?, category_id = ?, stock = ?, price = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $category_id, $stock_new, $price, $id]);

        if ($stock_new != $stock_old) {
            $diff = abs($stock_new - $stock_old);
            $type = ($stock_new > $stock_old) ? 'masuk' : 'keluar';
            $note = "Update data produk (Perubahan stok manual)";

            $logSql = "INSERT INTO stock_logs (product_id, user_id, type, quantity, notes) 
                       VALUES (?, ?, ?, ?, ?)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([$id, $user_id, $type, $diff, $note]);
        }

        $pdo->commit();
        header("Location: ../product.php?msg=success_update");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}