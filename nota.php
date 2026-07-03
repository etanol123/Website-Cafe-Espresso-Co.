<?php
require_once 'database.php';

$no_nota = base64_decode($_GET['inv']);
$rincian_nota = $app->get_nota_lengkap($no_nota);

if(count($rincian_nota) == 0) { die("Nota tidak ditemukan."); }

$info_dasar = $rincian_nota[0]; 
$total_bayar_semua = 0;

$_SESSION['aktif_kode_lacak'] = $info_dasar['kode_lacak'] ?? '';

include 'header.php';
?>

<div class="receipt-wrapper animate-fade-in">
    <div class="receipt-card">
        <div class="receipt-header">
            <h3>ESPRESSO & CO.</h3>
            <p>Bukti Pemesanan Meja #<?php echo $info_dasar['no_meja']; ?></p>
            <p class="invoice-num"><?php echo $info_dasar['no_nota']; ?></p>
            
            <div style="background: #f1f5f9; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 12px; margin: 15px 0 5px 0;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 1px; text-transform: uppercase;">Kode Lacak Antrean Kamu:</span>
                <h2 style="margin: 5px 0 0 0; font-size: 2.2rem; font-weight: 800; color: #4f46e5; letter-spacing: 4px;"><?php echo $info_dasar['kode_lacak'] ?? '0000'; ?></h2>
                <small style="color: #94a3b8; font-size: 0.72rem;">Gunakan 4 digit angka ini untuk melacak proses pembuatan kopi.</small>
            </div>
        </div>
        
        <div class="receipt-body">
            <div class="receipt-row">
                <span>Pemesan</span>
                <strong><?php echo htmlspecialchars($info_dasar['nama_pelanggan']); ?></strong>
            </div>
            <div class="receipt-row">
                <span>Waktu</span>
                <span><?php echo date('d-m-Y H:i', strtotime($info_dasar['tanggal_transaksi'])); ?></span>
            </div>
            
            <hr class="dashed-line">
            
            <div style="margin-bottom: 15px;"><strong style="color: var(--text-muted); font-size: 0.85rem;">Rincian Pesanan:</strong></div>
            
            <?php foreach($rincian_nota as $item): ?>
            <div class="receipt-row" style="margin-bottom: 8px;">
                <span style="flex: 2;"><strong><?php echo $item['qty']; ?>x</strong> <?php echo $item['nama_produk']; ?></span>
                <span style="flex: 1; text-align: right;">Rp <?php echo number_format($item['total_harga'],0,',','.'); ?></span>
            </div>
            <?php 
                $total_bayar_semua += $item['total_harga'];
            endforeach; 
            ?>
            
            <hr class="dashed-line">
            
            <div class="receipt-row total-row">
                <span>TOTAL TAGIHAN</span>
                <span>Rp <?php echo number_format($total_bayar_semua,0,',','.'); ?></span>
            </div>
        </div>
        
        <div class="receipt-footer">
            <p>Harap bersabar, hidangan Anda sedang disiapkan di dapur!</p>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="status.php" class="btn-primary" style="flex: 1; text-decoration:none; display:block; background: #10b981; text-align: center; padding: 10px 0; border-radius: 8px; color: white; font-weight: 700; font-size: 0.9rem;">🕒 Lacak Status</a>
                <a href="order.php" class="btn-primary" style="flex: 1; text-decoration:none; display:block; text-align: center; padding: 10px 0; border-radius: 8px; color: white; font-weight: 700; font-size: 0.9rem;">🛍️ Menu Lain</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>