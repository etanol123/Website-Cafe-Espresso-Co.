<?php
require_once 'database.php';

if(isset($_POST['login'])) {
    $auth = $app->login_kasir($_POST['username'], $_POST['password']);
    if($auth) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Akses ditolak! Username atau password Anda salah.";
    }
}
include 'header.php';
?>

<div class="auth-wrapper animate-fade-in">
    <div class="glass-card auth-card">
        <h2>Akses Petugas</h2>
        <p class="subtitle">Masuk untuk mengelola sistem antrean pesanan</p>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Isi: kasir1" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Isi: kasir123" required>
            </div>
            <button type="submit" name="login" class="btn-primary">Masuk Dashboard</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>