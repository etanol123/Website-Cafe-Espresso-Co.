<?php
require_once 'database.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'Semua';
$sort = $_GET['sort'] ?? 'terlama';

$list_produk = $app->get_all_produk($search);

if ($category !== 'Semua') {
    $list_produk = array_filter($list_produk, function($p) use ($category) {
        return strcasecmp($p['kategori'], $category) === 0;
    });
}

usort($list_produk, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'terbaru':
            return (int)$b['id_produk'] <=> (int)$a['id_produk']; 
        case 'harga_asc':
            return (float)$a['harga'] <=> (float)$b['harga']; 
        case 'harga_desc':
            return (float)$b['harga'] <=> (float)$a['harga']; 
        case 'nama_asc':
            return strcasecmp($a['nama_produk'], $b['nama_produk']); 
        case 'nama_desc':
            return strcasecmp($b['nama_produk'], $a['nama_produk']); 
        case 'terlama':
        default:
            return (int)$a['id_produk'] <=> (int)$b['id_produk']; 
    }
});

$limit_per_halaman = 9;
$total_produk = count($list_produk);
$total_halaman = ceil($total_produk / $limit_per_halaman);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_halaman && $total_halaman > 0) $page = $total_halaman;

$offset = ($page - 1) * $limit_per_halaman;

$produk_halaman = array_slice($list_produk, $offset, $limit_per_halaman);


if(isset($_POST['submit_order'])) {
    $nama = $_POST['nama_pelanggan'];
    $meja = intval($_POST['no_meja']); 
    $keranjang = $_POST['qty']; 
    $total_items = array_sum($keranjang);

    if($total_items > 0) {
        if($meja < 1 || $meja > 40) {
            $error = "Gagal memproses! Nomor meja tidak valid. Maksimal nomor meja adalah 40.";
        } else {
            $no_nota = $app->insert_multiple_order($nama, $meja, $keranjang);
            $secure_nota = base64_encode($no_nota); 
            header("Location: nota.php?inv=" . $secure_nota);
            exit();
        }
    } else {
        $error = "Silakan tentukan porsi pada minimal satu menu!";
    }
}
include 'header.php';
?>

<div class="order-container">
    <div class="order-header-section animate-fade-in">
        <h2>Pilih Menu Favoritmu</h2>
        <p class="subtitle">Atur jumlah porsi, pilih meja, dan pesananmu akan segera kami siapkan</p> 
    </div>

    <div class="search-container animate-fade-in" style="margin: 10px auto 20px auto; max-width: 500px; padding: 0 15px;">
        <form method="GET" action="order.php" style="display: flex; gap: 8px;">
            
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
            
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Cari kopi, es cokelat, atau cemilan..." 
                   style="flex: 1; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.2s;"
                   onfocus="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.15)'"
                   onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
            
            <?php if(!empty($search)): ?>
                <a href="order.php?category=<?php echo urlencode($category); ?>&sort=<?php echo urlencode($sort); ?>" style="background: #f1f5f9; color: #64748b; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" 
                   onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                   ❌ Reset
                </a>
            <?php endif; ?>

            <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 12px 22px; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; font-family: inherit; transition: background 0.2s;"
                    onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                Cari
            </button>
        </form>
    </div>

    <div class="category-tabs animate-fade-in" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 35px; flex-wrap: wrap;">
        <?php 
        $categories = ['Semua', 'Minuman', 'Makanan', 'Dessert'];
        foreach ($categories as $cat): 
            
            $cat_url = "order.php?category=" . urlencode($cat) . "&sort=" . urlencode($sort);
            if (!empty($search)) {
                $cat_url .= "&search=" . urlencode($search);
            }
            
            $is_active = ($category === $cat);
            $btn_style = $is_active 
                ? "background: #4f46e5; color: white; border: 1px solid #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);" 
                : "background: white; color: #64748b; border: 1px solid #e2e8f0;";
        ?>
            <a href="<?php echo $cat_url; ?>" style="text-decoration: none; padding: 8px 22px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s; <?php echo $btn_style; ?> font-family: inherit;"
               onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                <?php echo $cat; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger animate-fade-in"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="glass-card animate-fade-in" style="max-width: 700px; margin: 0 auto 30px auto; padding: 20px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Nama Pemesan</label>
                    <input type="text" name="nama_pelanggan" class="form-control" placeholder="Siapa namamu?" required autocomplete="off">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Nomor Meja (1-40)</label>
                    <input type="number" name="no_meja" class="form-control text-center" placeholder="1-40" required min="1" max="40">
                </div>
            </div>
        </div>

        <div class="sort-filter-bar animate-fade-in" style="display: flex; justify-content: space-between; align-items: center; max-width: 1040px; margin: 0 auto 20px auto; padding: 0 15px; flex-wrap: wrap; gap: 10px;">
            <p style="color: #64748b; font-size: 0.9rem; margin: 0;">
                Menampilkan <strong><?php echo count($produk_halaman); ?></strong> dari <strong><?php echo $total_produk; ?></strong> menu terpilih
            </p>
            
            <div style="display: flex; align-items: center; gap: 8px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Urutkan:</label>
                <select onchange="location = this.value;" style="padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; color: #1e293b; outline: none; background: white; cursor: pointer; font-family: inherit; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <?php
                    $sort_options = [
                        'terlama'    => '🕒 Menu Terlama (Default)',
                        'terbaru'    => '✨ Menu Terbaru',
                        'harga_asc'  => '💸 Harga: Rendah ke Tinggi',
                        'harga_desc' => '💰 Harga: Tinggi ke Rendah',
                        'nama_asc'   => '🔤 Nama: A - Z',
                        'nama_desc'  => '🔤 Nama: Z - A'
                    ];
                    foreach ($sort_options as $key => $label):
        
                        $url = "order.php?sort=" . $key . "&category=" . urlencode($category);
                        if (!empty($search)) $url .= "&search=" . urlencode($search);
                    ?>
                        <option value="<?php echo $url; ?>" <?php echo $sort === $key ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="menu-grid animate-fade-in">
            <?php 
            if (count($produk_halaman) > 0):
                foreach($produk_halaman as $p): 
                    if (!empty($p['foto'])) {
                        $img_url = $p['foto'];
                    } else {
                        $img_url = "https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500&auto=format&fit=crop&q=60";
                    }
            ?>
            <div class="menu-card" id="card_<?php echo $p['id_produk']; ?>">
                <div class="menu-img-wrapper">
                    <img src="<?php echo $img_url; ?>" alt="<?php echo $p['nama_produk']; ?>">
                    <div class="menu-price-badge">Rp <?php echo number_format($p['harga'],0,',','.'); ?></div>
                </div>
                <div class="menu-card-body">
                    <h3><?php echo $p['nama_produk']; ?></h3>
                    
                    <p class="category-badge-text" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">
                        <?php echo htmlspecialchars($p['kategori']); ?>
                    </p>

                    <p class="stock-text" style="margin-bottom: 15px;">Tersedia: <strong><?php echo $p['stok']; ?> porsi</strong></p>
                    
                    <div class="qty-wrapper">
                        <button type="button" class="btn-qty" onclick="updateQty(<?php echo $p['id_produk']; ?>, -1, <?php echo $p['stok']; ?>)">−</button>
                        <input type="number" name="qty[<?php echo $p['id_produk']; ?>]" id="input_qty_<?php echo $p['id_produk']; ?>" class="qty-input" value="0" readonly>
                        <button type="button" class="btn-qty" onclick="updateQty(<?php echo $p['id_produk']; ?>, 1, <?php echo $p['stok']; ?>)">+</button>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            else: 
            ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #64748b;">
                    <p style="font-size: 2.5rem; margin: 0 0 10px 0;">☕🔍</p>
                    <h4 style="margin: 0 0 5px 0; color: #1e293b;">Menu Tidak Ditemukan</h4>
                    <p style="font-size: 0.85rem; margin: 0;">Maaf, produk tidak tersedia dalam kategori, urutan, atau kata kunci tersebut.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total_halaman > 1): ?>
            <div class="pagination-container animate-fade-in" style="display: flex; justify-content: center; align-items: center; gap: 8px; margin: 40px auto 20px auto;">
                
                <?php if ($page > 1): 
                    $prev_url = "order.php?page=" . ($page - 1) . "&category=" . urlencode($category) . "&sort=" . urlencode($sort);
                    if (!empty($search)) $prev_url .= "&search=" . urlencode($search);
                ?>
                    <a href="<?php echo $prev_url; ?>" style="text-decoration: none; padding: 10px 16px; background: white; border: 1px solid #e2e8f0; color: #4f46e5; border-radius: 8px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s;">« Prev</a>
                <?php else: ?>
                    <span style="padding: 10px 16px; background: #f1f5f9; border: 1px solid #e2e8f0; color: #94a3b8; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: not-allowed;">« Prev</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_halaman; $i++): 
                    $page_url = "order.php?page=" . $i . "&category=" . urlencode($category) . "&sort=" . urlencode($sort);
                    if (!empty($search)) $page_url .= "&search=" . urlencode($search);
                    
                    $is_current = ($page == $i);
                    $page_style = $is_current 
                        ? "background: #4f46e5; color: white; border: 1px solid #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);" 
                        : "background: white; color: #64748b; border: 1px solid #e2e8f0;";
                ?>
                    <a href="<?php echo $page_url; ?>" style="text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s; <?php echo $page_style; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_halaman): 
                    $next_url = "order.php?page=" . ($page + 1) . "&category=" . urlencode($category) . "&sort=" . urlencode($sort);
                    if (!empty($search)) $next_url .= "&search=" . urlencode($search);
                ?>
                    <a href="<?php echo $next_url; ?>" style="text-decoration: none; padding: 10px 16px; background: white; border: 1px solid #e2e8f0; color: #4f46e5; border-radius: 8px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s;">Next »</a>
                <?php else: ?>
                    <span style="padding: 10px 16px; background: #f1f5f9; border: 1px solid #e2e8f0; color: #94a3b8; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: not-allowed;">Next »</span>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <div class="floating-action-bar">
            <button type="submit" name="submit_order" class="btn-primary btn-order-floating">
                Kirim Pesanan ke Dapur 🚀
            </button>
        </div>
    </form>
</div>

<script>
function updateQty(id, change, maxStock) {
    let input = document.getElementById('input_qty_' + id);
    let card = document.getElementById('card_' + id);
    let currentVal = parseInt(input.value) || 0;
    let newVal = currentVal + change;

    if(newVal >= 0 && newVal <= maxStock) {
        input.value = newVal;
        
        if(newVal > 0) {
            card.classList.add('active');
            document.querySelector('.floating-action-bar').classList.add('show');
        } else {
            card.classList.remove('active');
        }
    }
}
</script>

<?php include 'footer.php'; ?>