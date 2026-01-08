<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) { die("Barang tidak ditemukan!"); }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">Edit Data Barang</div>
                    <div class="card-body">
                        <form action="func/update_product.php" method="POST">
                            <input type="hidden" name="id" value="<?= $product->id ?>">
                            
                            <div class="mb-3">
                                <label>Nama Barang</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product->name) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>" <?= ($cat->id == $product->category_id) ? 'selected' : '' ?>>
                                            <?= $cat->name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Stok</label>
                                <input type="number" name="stock" class="form-control" value="<?= $product->stock ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Harga</label>
                                <input type="number" name="price" class="form-control" value="<?= $product->price ?>" required>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="product.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>