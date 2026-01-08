<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$stmtTotal = $pdo->query("SELECT COUNT(*) FROM products");
$totalBarang = $stmtTotal->fetchColumn();

$stmtStok = $pdo->query("SELECT SUM(stock) FROM products");
$totalStok = $stmtStok->fetchColumn() ?? 0;

$stmtLow = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 5");
$stokRendah = $stmtLow->fetchColumn();

$stmtCat = $pdo->query("SELECT COUNT(*) FROM categories");
$totalKategori = $stmtCat->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Inventaris Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Ringkasan Inventaris</h2>
            <p class="text-muted">Pantau kondisi stok gudang Anda secara real-time.</p>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-md-3 mb-3"> <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <i class="bi bi-box-seam fs-1"></i>
                    <h5 class="card-title mt-2">Jenis Barang</h5>
                    <h2 class="display-6 fw-bold"><?= $totalBarang ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <i class="bi bi-stack fs-1"></i>
                    <h5 class="card-title mt-2">Total Stok</h5>
                    <h2 class="display-6 fw-bold"><?= $totalStok ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <i class="bi bi-tags fs-1"></i>
                    <h5 class="card-title mt-2">Kategori</h5>
                    <h2 class="display-6 fw-bold"><?= $totalKategori ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <h5 class="card-title mt-2">Stok Menipis</h5>
                    <h2 class="display-6 fw-bold"><?= $stokRendah ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <div class="card shadow-sm border-0 p-4">
                <h4>Menu Navigasi Cepat</h4>
                <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
                    <a href="product.php" class="btn btn-dark btn-lg px-4">
                        <i class="bi bi-table"></i> Daftar Barang
                    </a>
                    <a href="categories.php" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-tag"></i> Kelola Kategori
                    </a>
                    <a href="add_product.php" class="btn btn-outline-dark btn-lg px-4">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
