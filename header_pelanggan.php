<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espresso & Co. - Pelanggan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    <style>
        main {
            flex: 1 0 auto;
            width: 100%;
        }
        body, table, th, td, input, button, select, h2, h3, p {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
    </style>
</head>
<body>

    <nav class="main-navbar">
        <div class="nav-brand">☕ Espresso & Co.</div>
        <div class="nav-menu">
            <?php if(basename($_SERVER['PHP_SELF']) == 'login.php'): ?>
                <a href="order.php" class="btn-secondary" style="text-decoration: none; font-size: 0.9rem; font-weight: 600;">⬅️ Kembali ke Menu</a>
            <?php else: ?>
                <a href="login.php" class="btn-secondary" style="text-decoration: none; font-size: 0.9rem; font-weight: 600;">🔑 Login Petugas</a>
            <?php endif; ?>
        </div>
    </nav>

    <main></main>