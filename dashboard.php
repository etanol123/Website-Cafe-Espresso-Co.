<?php
require_once 'database.php';
if(!isset($_SESSION['auth_kasir'])) { header("Location: login.php"); exit(); }

include 'header_petugas.php';

$range = $_GET['range'] ?? '7_days';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$analytics = $app->get_complete_dashboard_analytics($range, $start_date, $end_date);

$label_rentang = "Hari Ini";
if ($range == 'yesterday') $label_rentang = "Kemarin";
if ($range == '2_days_ago') $label_rentang = "2 Hari Lalu";
if ($range == '3_days_ago') $label_rentang = "3 Hari Lalu";
if ($range == '7_days') $label_rentang = "7 Hari Terakhir";
if ($range == '30_days') $label_rentang = "30 Hari Terakhir";
if ($range == 'custom') $label_rentang = (!empty($start_date) && !empty($end_date)) ? date('d M Y', strtotime($start_date)) . " - " . date('d M Y', strtotime($end_date)) : "Rentang Kustom";
?>

<div class="animate-fade-in" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px; margin-bottom: 30px;">
    <div>
        <h2 style="color: #0f172a; font-weight: 800; margin: 0 0 5px 0;">👋 Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_kasir']); ?>!</h2>
        <p style="color: #64748b; margin: 0; margin-bottom: 10px;">Analitik data performa kafe terintegrasi secara dinamis.</p>
        <a href="riwayat.php" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f1f5f9; color: #334155; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; border: 1px solid #e2e8f0; transition: all 0.2s;">
            📜 Buka Riwayat Transaksi
        </a>
    </div>
    
    <form method="GET" action="dashboard.php" id="filterForm" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
        <div style="background: #ffffff; padding: 6px 12px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">📅 Filter Periode:</span>
            <select name="range" id="rangeSelect" onchange="handleRangeChange(this.value)" style="border: none; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; font-weight: 700; color: #0f172a; cursor: pointer; outline: none; background: transparent;">
                <option value="today" <?php echo $range == 'today' ? 'selected' : ''; ?>>Hari Ini</option>
                <option value="yesterday" <?php echo $range == 'yesterday' ? 'selected' : ''; ?>>Kemarin</option>
                <option value="2_days_ago" <?php echo $range == '2_days_ago' ? 'selected' : ''; ?>>2 Hari Lalu</option>
                <option value="3_days_ago" <?php echo $range == '3_days_ago' ? 'selected' : ''; ?>>3 Hari Lalu</option>
                <option value="7_days" <?php echo $range == '7_days' ? 'selected' : ''; ?>>7 Hari Terakhir</option>
                <option value="30_days" <?php echo $range == '30_days' ? 'selected' : ''; ?>>30 Hari Terakhir</option>
                <option value="custom" <?php echo $range == 'custom' ? 'selected' : ''; ?>>📍 Berdasarkan Kalender</option>
            </select>
        </div>

        <div id="customDateWrapper" style="display: <?php echo $range == 'custom' ? 'flex' : 'none'; ?>; gap: 8px; align-items: center; background: #ffffff; padding: 8px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" style="border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: #334155;">
            <span style="font-size: 0.85rem; color: #64748b;">s/d</span>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" style="border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: #334155;">
            <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Terapkan</button>
        </div>
    </form>
</div>

<div class="stats-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 25px;">
    <div class="glass-card animate-fade-in" style="padding: 20px; border-left: 5px solid #10b981; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 12px;">
        <span style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">💰 Pendapatan (<?php echo $label_rentang; ?>)</span>
        <h2 style="margin: 10px 0 0 0; color: #0f172a; font-size: 1.6rem; font-weight: 800;">Rp <?php echo number_format($analytics['pendapatan'], 0, ',', '.'); ?></h2>
    </div>
    
    <div class="glass-card animate-fade-in" onclick="location.href='riwayat.php'" style="padding: 20px; border-left: 5px solid #3b82f6; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <span style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">📋 Total Nota (<?php echo $label_rentang; ?>)</span>
        <h2 style="margin: 10px 0 0 0; color: #0f172a; font-size: 1.6rem; font-weight: 800;"><?php echo $analytics['pesanan']; ?> Transaksi</h2>
        <span style="font-size: 0.75rem; color: #3b82f6; font-weight: 600; display: block; margin-top: 5px;">👉 Klik untuk lihat riwayat lengkap</span>
    </div>
    
    <div class="glass-card animate-fade-in" style="padding: 20px; border-left: 5px solid #f59e0b; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 12px;">
        <span style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">🔥 Terlaris (<?php echo $label_rentang; ?>)</span>
        <h2 style="margin: 10px 0 0 0; color: #1e293b; font-size: 1.15rem; font-weight: 700; word-break: break-word;"><?php echo htmlspecialchars($analytics['menu_terlaris']); ?></h2>
    </div>
</div>

<div class="glass-card animate-fade-in" style="padding: 25px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 25px;">
    <div style="margin-bottom: 20px;">
        <h3 style="margin: 0; color: #0f172a; font-weight: 700;">📈 Grafik Tren Pendapatan</h3>
        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">Fluktuasi grafik penjualan nominal omzet yang berhasil dibukukan.</p>
    </div>
    <div style="position: relative; width: 100%; height: 260px;">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<div class="glass-card animate-fade-in" style="padding: 25px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 25px;">
    <div style="margin-bottom: 20px;">
        <h3 style="margin: 0; color: #0f172a; font-weight: 700;">🍕 Komposisi Menu Terjual</h3>
        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">Komparasi porsi produk yang terjual sesuai periode riwayat transaksi.</p>
    </div>
    <div style="position: relative; width: 100%; height: 260px; display: flex; justify-content: center;">
        <div style="width: 100%; max-width: 400px; height: 100%;">
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function handleRangeChange(value) {
    const wrapper = document.getElementById('customDateWrapper');
    if (value === 'custom') {
        wrapper.style.display = 'flex';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('filterForm').submit();
    }
}

document.addEventListener("DOMContentLoaded", function() {

    const ctxLine = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line', 
        data: {
            labels: <?php echo json_encode($analytics['line_labels']); ?>,
            datasets: [{
                label: 'Omzet Pendapatan',
                data: <?php echo json_encode($analytics['line_data']); ?>,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                borderWidth: 3,
                tension: 0.35,
                pointBackgroundColor: '#4f46e5',
                pointHoverRadius: 7,
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        color: '#64748b',
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + ' Jt';
                            if (value >= 1000) return (value / 1000) + ' Rb';
                            return value;
                        }
                    }
                },
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } } }
            }
        }
    });

    const ctxPie = document.getElementById('pieChart').getContext('2d');
    
    const pieLabelsCount = <?php echo count($analytics['pie_labels']); ?>;
    const baseColors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#ec4899', '#8b5cf6', '#06b6d4', '#f43f5e', '#14b8a6'];
    let pieColors = [];
    for (let i = 0; i < pieLabelsCount; i++) {
        pieColors.push(baseColors[i % baseColors.length]);
    }

    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($analytics['pie_labels']); ?>,
            datasets: [{
                data: <?php echo json_encode($analytics['pie_data']); ?>,
                backgroundColor: pieColors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#475569', font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 600 }, boxWidth: 12, padding: 15 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) { return ' ' + context.label + ': ' + context.raw + ' Porsi'; }
                    }
                }
            }
        }
    });
});
</script>

<?php include 'footer_petugas.php'; ?>