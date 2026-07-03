<?php
require_once 'database.php';

$kode_otomatis = $_SESSION['aktif_kode_lacak'] ?? '';
$search_kode = $_GET['kode'] ?? $kode_otomatis;

$hasil_track = [];

if (!empty($search_kode)) {
    $hasil_track = $app->track_pesanan_pelanggan($search_kode);
}

include 'header.php';
?>

<div class="main-container animate-fade-in" style="padding: 20px; max-width: 600px; margin: 0 auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <div class="search-box" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center;">
        <h3 style="margin-top: 0; margin-bottom: 5px; color: #1e293b;">Masukkan Kode Lacak Kamu</h3>
        <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 20px;">Lihat 4 digit angka unik yang tertera pada nota belanjaanmu.</p>
        
        <form method="GET" action="status.php" style="display: flex; gap: 10px; justify-content: center;">
            <input type="number" name="kode" value="<?php echo htmlspecialchars($search_kode); ?>" placeholder="Contoh: 8524" max="9999" min="1000" style="font-size: 1.2rem; text-align: center; font-weight: 700; letter-spacing: 2px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; width: 60%; font-family: inherit;" required>
            <button type="submit" style="background: #1e293b; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; font-family: inherit;">Lacak</button>
        </form>
    </div>

    <?php if (!empty($search_kode)): ?>
        <?php if (!empty($hasil_track)): ?>
            <?php foreach ($hasil_track as $nota => $data): 
                $step1 = 'done'; 
                $step2 = ''; 
                $step3 = '';
                
                if ($data['status'] == 'Diproses') { 
                    $step2 = 'active'; 
                } elseif ($data['status'] == 'Selesai') { 
                    $step2 = 'done'; 
                    $step3 = 'done active'; 
                } else { 
                    $step1 = 'active'; 
                }
            ?>
                <div class="order-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed #e2e8f0; padding-bottom: 12px; margin-bottom: 15px;">
                        <div>
                            <span style="font-size: 0.75rem; color: #4f46e5; font-weight: 800; background: #eef2ff; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-bottom: 5px;">Token: <?php echo $data['kode_lacak']; ?></span>
                            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;">Meja <?php echo htmlspecialchars($data['meja']); ?> - <?php echo htmlspecialchars($data['pelanggan']); ?></h4>
                        </div>
                        <span class="badge" style="padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: #f1f5f9; color: #475569;">
                            <?php echo $data['status']; ?>
                        </span>
                    </div>

                    <div class="tracker-timeline" style="display: flex; justify-content: space-between; margin: 25px 0; position: relative;">
                        <div class="step <?php echo $step1; ?>" style="text-align: center; flex: 1;">
                            <div class="step-icon" style="width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px auto; font-weight: bold;">1</div>
                            <div class="step-label" style="font-size: 0.8rem; color: #64748b;">Antrean</div>
                        </div>
                        <div class="step <?php echo $step2; ?>" style="text-align: center; flex: 1;">
                            <div class="step-icon" style="width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px auto; font-weight: bold;">2</div>
                            <div class="step-label" style="font-size: 0.8rem; color: #64748b;">Dibuat</div>
                        </div>
                        <div class="step <?php echo $step3; ?>" style="text-align: center; flex: 1;">
                            <div class="step-icon" style="width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px auto; font-weight: bold;">3</div>
                            <div class="step-label" style="font-size: 0.8rem; color: #64748b;">Selesai</div>
                        </div>
                    </div>

                    <div class="items-list" style="background: #f8fafc; padding: 15px; border-radius: 8px;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Daftar Menu:</div>
                        <?php foreach ($data['items'] as $item): ?>
                            <div class="item-row" style="font-size: 0.9rem; color: #334155; margin-bottom: 6px;">🔹 <?php echo htmlspecialchars($item); ?></div>
                        <?php endforeach; ?>
                        
                        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-weight: 700; font-size: 0.95rem;">
                            <span style="color: #475569;">Total Bayar:</span>
                            <span style="color: #4f46e5;">Rp <?php echo number_format($data['total_tagihan'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); text-align: center; color: #64748b;">
                <p style="font-size: 2.5rem; margin: 0 0 10px 0;">🕵️‍♂️</p>
                <h4 style="margin: 0 0 5px 0; color: #1e293b;">Pesanan Tidak Ditemukan</h4>
                <p style="font-size: 0.85rem; margin: 0;">Kode Token <strong>"<?php echo htmlspecialchars($search_kode); ?>"</strong> tidak ditemukan untuk hari ini.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="order.php" style="color: #4f46e5; text-decoration: none; font-size: 0.9rem; font-weight: 600;">← Kembali ke Halaman Menu</a>
    </div>
</div>

<?php include 'footer.php'; ?>