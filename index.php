<?php
require_once 'database.php';
if(!isset($_SESSION['auth_kasir'])) { header("Location: login.php"); exit(); }

if(isset($_GET['action']) && $_GET['action'] == 'process') {
    $nota_proses = base64_decode($_GET['token']);
    $app->update_status_proses_by_nota($nota_proses);
    header("Location: index.php"); 
    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'complete') {
    $nota_hapus = base64_decode($_GET['token']);
    $app->update_status_selesai_by_nota($nota_hapus);
    header("Location: index.php");
    exit();
}

include 'header_petugas.php';
?>

<div class="dashboard-grid">
    <div class="glass-card animate-fade-in" style="grid-column: span 2;">
        <div class="card-header border-alert">
            <h3>🔔 Daftar Pesanan Aktif</h3>
            <p class="subtitle">Seluruh pesanan tergabung otomatis berdasarkan Meja & Nota</p>
        </div>
        
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Jam & Nota</th>
                        <th>Meja</th>
                        <th>Pelanggan</th>
                        <th>Status</th> 
                        <th>Rincian Menu (Porsi)</th>
                        <th>Tagihan</th>
                        <th style="text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $list = $app->get_grouped_antrean(); 
                    if(count($list) > 0):
                        foreach($list as $nota => $data):
                    ?>
                    <tr class="table-row-animate">
                        <td>
                            <span class="time-tag"><?php echo date('H:i', strtotime($data['waktu'])); ?></span><br>
                            <small class="text-muted" style="font-family: monospace;"><?php echo $nota; ?></small>
                        </td>
                        <td><span style="font-size: 1.3rem; font-weight: 800; color: #eab308;">#<?php echo $data['meja']; ?></span></td>
                        <td><strong><?php echo htmlspecialchars($data['pelanggan']); ?></strong></td>
                        
                        <td>
                            <?php if($data['status'] == 'Pending'): ?>
                                <span style="background: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">Antrean</span>
                            <?php else: ?>
                                <span style="background: #e0e7ff; color: #4f46e5; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">Dibuat</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php foreach($data['items'] as $item): ?>
                                <div class="menu-tag" style="display: inline-block; margin: 2px;"><?php echo $item; ?></div>
                            <?php endforeach; ?>
                        </td>
                        <td><strong>Rp <?php echo number_format($data['total_tagihan'],0,',','.'); ?></strong></td>
                        
                        <td style="text-align: center;">
                            <?php if($data['status'] == 'Pending'): ?>
                                <a href="index.php?action=process&token=<?php echo base64_encode($nota); ?>" class="btn-action" style="background: #4f46e5; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-block;">👨‍🍳 Mulai Buat</a>
                            <?php else: ?>
                                <a href="index.php?action=complete&token=<?php echo base64_encode($nota); ?>" class="btn-action btn-success" style="display: inline-block;">✅ Sajikan Semua</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr><td colspan="7" class="text-center text-muted" style="padding: 50px 0;">Menunggu pesanan masuk dari pelanggan...</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>