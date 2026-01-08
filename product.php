<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$query = "SELECT products.*, categories.name AS category_name 
          FROM products 
          LEFT JOIN categories ON products.category_id = categories.id 
          ORDER BY products.created_at DESC";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Barang - Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?> <div class="container mt-4">
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success_delete'): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Barang berhasil dihapus!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Stok Barang</h2>
            <a href="add_product.php" class="btn btn-success">+ Tambah Barang</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p->name) ?></td>
                            <td><span class="badge bg-info text-dark"><?= $p->category_name ?? 'Tanpa Kategori' ?></span></td>
                            <td><?= $p->stock ?></td>
                            <td>Rp <?= number_format($p->price, 0, ',', '.') ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $p->id ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="func/delete_product.php?id=<?= $p->id ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus barang ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>