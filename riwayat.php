<?php
require_once 'database.php';
if(!isset($_SESSION['auth_kasir'])) { header("Location: login.php"); exit(); }

include 'header_petugas.php';
?>

<div class="dashboard-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card animate-fade-in">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
            <div>
                <h3>📜 Riwayat Transaksi Selesai (Lunas)</h3>
                <p class="subtitle">Daftar seluruh pesanan yang sudah selesai diproses oleh dapur dan dibayar</p>
            </div>
            <a href="dashboard.php" class="btn-action" style="background: #f1f5f9; color: #334155; font-weight: 600; padding: 10px 18px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px; border: 1px solid #cbd5e1;">
                ⬅ Keluar & Ke Dashboard
            </a>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No. Nota</th>
                        <th>Waktu Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Meja</th>
                        <th>Detail Item Pesanan</th>
                        <th>Total Pembayaran</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $riwayat = $app->get_grouped_riwayat(); 
                    if(empty($riwayat)):
                    ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 40px; font-style: italic;">
                            Belum ada pesanan yang diselesaikan (Lunas) saat ini.
                        </td>
                    </tr>
                    <?php 
                    else:
                        foreach($riwayat as $nota => $data): 
                    ?>
                    <tr>
                        <td><strong style="color: #4f46e5; font-family: monospace; font-size: 1rem;"><?php echo $nota; ?></strong></td>
                        <td style="font-size: 0.85rem; color: #64748b;"><?php echo date('d M Y - H:i', strtotime($data['waktu'])); ?> WIB</td>
                        <td><strong><?php echo htmlspecialchars($data['pelanggan']); ?></strong></td>
                        <td><span class="stock-badge" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">Meja <?php echo $data['meja']; ?></span></td>
                        <td>
                            <ul style="margin: 0; padding-left: 15px; font-size: 0.9rem; color: #334155;">
                                <?php foreach($data['items'] as $item): ?>
                                    <li><?php echo htmlspecialchars($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><strong style="color: #10b981; font-size: 1.05rem;">Rp <?php echo number_format($data['total_tagihan'], 0, ',', '.'); ?></strong></td>
                        <td style="text-align: center;">
                            <span class="time-tag" style="background: #d1fae5; color: #065f46; font-weight: 700; padding: 6px 14px; border-radius: 50px; font-size: 0.75rem;">✔ PAID / SELESAI</span>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_petugas.php'; ?>