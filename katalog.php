<?php
require_once 'database.php';
if(!isset($_SESSION['auth_kasir'])) { header("Location: login.php"); exit(); }

$target_folder = "uploads/";


if(isset($_POST['tambah_menu'])) {
    $foto_nama = "";
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        if(!is_dir($target_folder)) { mkdir($target_folder, 0755, true); }
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nama = $target_folder . time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "_", $_POST['nama_produk']) . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto_nama);
    }
    $app->insert_produk($_POST['nama_produk'], $_POST['harga'], $_POST['stok'], $_POST['kategori'], $foto_nama);
    header("Location: katalog.php?msg=Menu Berhasil Ditambahkan");
    exit();
}

$menu_edit = null;
if(isset($_GET['edit_menu_id'])) {
    foreach($app->get_all_produk() as $p) {
        if($p['id_produk'] == $_GET['edit_menu_id']) { $menu_edit = $p; break; }
    }
}

if(isset($_POST['update_menu'])) {
    $id_produk = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $foto_nama = $_POST['foto_lama'];
    
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        if(!is_dir($target_folder)) { mkdir($target_folder, 0755, true); }
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $new_foto_nama = $target_folder . time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "_", $nama_produk) . "." . $ext;
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $new_foto_nama)) {
            $foto_nama = $new_foto_nama;
            if(!empty($_POST['foto_lama']) && file_exists($_POST['foto_lama'])) { unlink($_POST['foto_lama']); }
        }
    }
    $app->update_produk($id_produk, $nama_produk, $harga, $stok, $kategori, $foto_nama);
    header("Location: katalog.php?msg=Menu Berhasil Diperbarui");
    exit();
}

if(isset($_GET['hapus_menu_id'])) {
    foreach($app->get_all_produk() as $p) {
        if($p['id_produk'] == $_GET['hapus_menu_id']) {
            if(!empty($p['foto']) && file_exists($p['foto'])) { unlink($p['foto']); }
            break;
        }
    }
    $app->delete_produk($_GET['hapus_menu_id']);
    header("Location: katalog.php?msg=Menu Berhasil Dihapus");
    exit();
}

include 'header_petugas.php';
?>

<div class="dashboard-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card animate-fade-in">
        <div class="card-header">
            <h3>🍔 Kelola Katalog Menu Cafe</h3>
            <p class="subtitle">Kelola daftar makanan, minuman, dessert, stok operasional, beserta foto produk</p>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div id="alert-notif" class="alert alert-success" style="margin-bottom: 15px; padding: 12px; background: #d1fae5; color: #065f46; text-align: center; border-radius: 8px; font-weight: 600; transition: opacity 0.5s ease;">
                🔹 <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama Menu / Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok Operasional</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($app->get_all_produk() as $p): ?>
                    <tr>
                        <td>#<?php echo $p['id_produk']; ?></td>
                        <td>
                            <?php if(!empty($p['foto']) && file_exists($p['foto'])): ?>
                                <img src="<?php echo $p['foto']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">No Photo</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($p['nama_produk']); ?></strong></td>
                        <td><span class="time-tag" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 700; background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px;"><?php echo htmlspecialchars($p['kategori'] ?? 'Makanan'); ?></span></td>
                        <td>Rp <?php echo number_format($p['harga'],0,',','.'); ?></td>
                        <td><span class="stock-badge"><?php echo $p['stok']; ?> porsi</span></td>
                        <td style="text-align: center;">
                            <a href="katalog.php?edit_menu_id=<?php echo $p['id_produk']; ?>#form-menu-section" class="btn-action" style="background: #e0f2fe; color: #0369a1; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">✏️ Edit</a>
                            <a href="katalog.php?hapus_menu_id=<?php echo $p['id_produk']; ?>" onclick="return confirm('Hapus menu <?php echo $p['nama_produk']; ?> dari katalog?')" class="btn-action" style="background: #fee2e2; color: #b91c1c; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; margin-left: 5px;">❌ Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="form-menu-section" class="glass-card animate-fade-in" style="margin-top: 25px; max-width: 650px;">
        <div class="card-header">
            <h3><?php echo $menu_edit ? '✏️ Edit Detail Menu' : '➕ Tambah Menu Baru ke Katalog'; ?></h3>
            <p class="subtitle">Sesuaikan data item produk di bawah ini</p>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
            <?php if($menu_edit): ?>
                <input type="hidden" name="id_produk" value="<?php echo $menu_edit['id_produk']; ?>">
                <input type="hidden" name="foto_lama" value="<?php echo $menu_edit['foto'] ?? ''; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label style="font-weight: 600; color: #334155;">Nama Produk / Menu</label>
                <input type="text" name="nama_produk" class="form-control" value="<?php echo $menu_edit ? htmlspecialchars($menu_edit['nama_produk']) : ''; ?>" placeholder="Contoh: Es Kopi Susu Vanilla" required autocomplete="off" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div class="form-group">
                    <label style="font-weight: 600; color: #334155;">Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="<?php echo $menu_edit ? $menu_edit['harga'] : ''; ?>" placeholder="Contoh: 15000" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                <div class="form-group">
                    <label style="font-weight: 600; color: #334155;">Stok Awal (Porsi)</label>
                    <input type="number" name="stok" class="form-control" value="<?php echo $menu_edit ? $menu_edit['stok'] : ''; ?>" placeholder="Contoh: 50" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="font-weight: 600; color: #334155;">Kategori Menu</label>
                <select name="kategori" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #cbd5e1; border-radius: 6px; background: #f8fafc; font-weight: 500;">
                    <option value="Makanan" <?php echo ($menu_edit && $menu_edit['kategori'] == 'Makanan') ? 'selected' : ''; ?>>🍿 Makanan</option>
                    <option value="Minuman" <?php echo ($menu_edit && $menu_edit['kategori'] == 'Minuman') ? 'selected' : ''; ?>>☕ Minuman</option>
                    <option value="Dessert" <?php echo ($menu_edit && $menu_edit['kategori'] == 'Dessert') ? 'selected' : ''; ?>>🍰 Dessert</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="font-weight: 600; color: #334155;">Foto Produk</label>
                <?php if($menu_edit && !empty($menu_edit['foto']) && file_exists($menu_edit['foto'])): ?>
                    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                        <img src="<?php echo $menu_edit['foto']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                        <span style="font-size: 0.8rem; color: #64748b;">*Biarkan kosong jika tidak ingin mengganti foto saat ini</span>
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" class="form-control" accept="image/*" style="width: 100%; padding: 7px; margin-top: 5px; border: 1px solid #cbd5e1; border-radius: 6px; background: #fff;">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <?php if($menu_edit): ?>
                    <button type="submit" name="update_menu" style="background: #4f46e5; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer;">Simpan Perubahan</button>
                    <a href="katalog.php" style="background: #e2e8f0; color: #334155; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center;">Batal</a>
                <?php else: ?>
                    <button type="submit" name="tambah_menu" style="background: #4f46e5; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer;">Masukkan ke Katalog 🚀</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
    const alertNotif = document.getElementById('alert-notif');
    if (alertNotif) {
        setTimeout(() => {
            alertNotif.style.opacity = '0';
            setTimeout(() => alertNotif.remove(), 500); 
        }, 3000); 
    }
</script>

<?php include 'footer_petugas.php'; ?>