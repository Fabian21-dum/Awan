<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ../categories.php?msg=cat_deleted");
        exit;
    } catch (PDOException $e) {
        die("Gagal menghapus kategori: " . $e->getMessage());
    }
} else {
    header("Location: ../categories.php");
    exit;
}
