<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso & Co. - POS System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="main-navbar">
        <div class="nav-brand">☕ Espresso & Co.</div>
        <div class="nav-menu" style="display: flex; align-items: center; gap: 50px;">
            <a href="dashboard.php" class="btn-link" style="text-decoration: none; font-weight: 700; font-size: 0.9rem;">Dashboard Penjualan</a>
            <a href="katalog.php" class="btn-link" style="text-decoration: none; font-weight: 700; font-size: 0.9rem;">Kelola Katalog</a>
            <a href="index.php" class="btn-link" style="text-decoration: none; font-weight: 700; font-size: 0.9rem;">Antrean Pesanan</a>
            <span class="kasir-badge">Staff: <?php echo htmlspecialchars($_SESSION['nama_kasir'] ?? 'Kasir'); ?></span>
            <a href="logout.php" class="btn-secondary">Logout</a>
        </div>
    </nav>
    <main class="main-container">