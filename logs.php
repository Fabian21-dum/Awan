<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$query = "SELECT stock_logs.*, products.name as product_name, users.username 
          FROM stock_logs 
          JOIN products ON stock_logs.product_id = products.id 
          JOIN users ON stock_logs.user_id = users.id 
          ORDER BY stock_logs.created_at DESC";
$logs = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Stok - GudangKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2 class="mb-4">Riwayat Arus Barang</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Barang</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Admin</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l): ?>
                        <tr>
                            <td><?= $l->created_at ?></td>
                            <td><?= htmlspecialchars($l->product_name) ?></td>
                            <td>
                                <span class="badge <?= $l->type == 'masuk' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= strtoupper($l->type) ?>
                                </span>
                            </td>
                            <td><?= $l->quantity ?></td>
                            <td><?= $l->username ?></td>
                            <td><?= htmlspecialchars($l->notes) ?></td>
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