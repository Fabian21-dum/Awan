<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">GudangKu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="product.php">Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php">Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logs.php">Riwayat</a>
                </li>
            </ul>
            <div class="navbar-nav ms-auto">
                <span class="nav-link text-light me-3">Halo, <strong><?= $_SESSION['username'] ?></strong></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>