<?php
session_start();

date_default_timezone_set('Asia/Jakarta');

class DatabaseConnection {
    protected $host = "127.0.0.1"; 
    protected $uname = "root";
    protected $pass = "";
    protected $db = "db_cafe_uas";
    protected $port = 3307;       
    public $db_handle;

    public function __construct() {
    
        $this->db_handle = mysqli_connect($this->host, $this->uname, $this->pass, $this->db, $this->port);
        if (mysqli_connect_errno()) { die("Koneksi gagal: " . mysqli_connect_error()); }
    }
}

class CafeSystem extends DatabaseConnection {
    
    // 1. LOGIN KASIR
    public function login_kasir($username, $password) {
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $execute = mysqli_query($this->db_handle, $query);
        if (mysqli_num_rows($execute) > 0) {
            $row = mysqli_fetch_assoc($execute);
            $_SESSION['auth_kasir'] = true;
            $_SESSION['nama_kasir'] = $row['nama_kasir'];
            return true;
        }
        return false;
    }

    public function get_all_produk($search = '') {
     
        $search = mysqli_real_escape_string($this->db_handle, $search);
        
        if (!empty($search)) {
            $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%' OR kategori LIKE '%$search%'";
        } else {
            $query = "SELECT * FROM produk";
        }
        
        $execute = mysqli_query($this->db_handle, $query);
        $result = [];
        while ($row = mysqli_fetch_assoc($execute)) { $result[] = $row; }
        return $result;
    }

    public function insert_produk($nama_produk, $harga, $stok, $kategori, $foto) {
        $query = "INSERT INTO produk (nama_produk, harga, stok, kategori, foto) 
                  VALUES ('$nama_produk', '$harga', '$stok', '$kategori', '$foto')";
        return mysqli_query($this->db_handle, $query);
    }

    public function update_produk($id_produk, $nama_produk, $harga, $stok, $kategori, $foto) {
        $query = "UPDATE produk SET 
                    nama_produk = '$nama_produk', 
                    harga = '$harga', 
                    stok = '$stok', 
                    kategori = '$kategori',
                    foto = '$foto' 
                  WHERE id_produk = '$id_produk'";
        return mysqli_query($this->db_handle, $query);
    }

    public function delete_produk($id_produk) {
        $query = "DELETE FROM produk WHERE id_produk='$id_produk'";
        return mysqli_query($this->db_handle, $query);
    }

    public function insert_multiple_order($nama_pelanggan, $no_meja, $keranjang_pesanan) {
        $default_tags = ['Original Recipe', 'Standard Serve'];
        $tag_string = implode(", ", $default_tags); 
        
        $no_nota = "INV-" . date('Ymd-His'); 
        $waktu = date('Y-m-d H:i:s');
        $kode_lacak_baru = rand(1000, 9999); 

        foreach($keranjang_pesanan as $id_produk => $qty) {
            if($qty > 0) {
                
                $q_produk = mysqli_query($this->db_handle, "SELECT nama_produk, harga, stok FROM produk WHERE id_produk='$id_produk'");
                $p = mysqli_fetch_assoc($q_produk);
                
                if($p['stok'] >= $qty) {
                    $nama_produk = $p['nama_produk'];
                    $total_harga = $p['harga'] * $qty;

                    $q_insert = "INSERT INTO transaksi (no_nota, kode_lacak, nama_pelanggan, no_meja, id_produk, nama_produk, qty, tag_pesanan, total_harga, status, tanggal_transaksi) 
                                 VALUES ('$no_nota', '$kode_lacak_baru', '$nama_pelanggan', '$no_meja', '$id_produk', '$nama_produk', '$qty', '$tag_string', '$total_harga', 'Pending', '$waktu')";
                    mysqli_query($this->db_handle, $q_insert);

                    $q_update_stok = "UPDATE produk SET stok = stok - $qty WHERE id_produk='$id_produk'";
                    mysqli_query($this->db_handle, $q_update_stok);
                }
            }
        }
        return $no_nota; 
    }

    public function get_grouped_antrean() {
        $query = "SELECT * FROM transaksi WHERE status='Pending' OR status='Diproses' ORDER BY tanggal_transaksi ASC";
        $execute = mysqli_query($this->db_handle, $query);
        $grouped = [];
        
        while ($row = mysqli_fetch_assoc($execute)) {
            $nota = $row['no_nota'];
            if(!isset($grouped[$nota])) {
                $grouped[$nota] = [
                    'waktu' => $row['tanggal_transaksi'],
                    'pelanggan' => $row['nama_pelanggan'],
                    'meja' => $row['no_meja'],
                    'status' => $row['status'],
                    'items' => [],
                    'total_tagihan' => 0
                ];
            }
            $grouped[$nota]['items'][] = $row['nama_produk'] . " (" . $row['qty'] . "x)";
            $grouped[$nota]['total_tagihan'] += $row['total_harga'];
        }
        return $grouped;
    }
    
    public function update_status_selesai_by_nota($no_nota) {
        $query = "UPDATE transaksi SET status='Selesai' WHERE no_nota='$no_nota'";
        return mysqli_query($this->db_handle, $query);
    }

    public function get_nota_lengkap($no_nota) {
        $query = "SELECT * FROM transaksi WHERE no_nota='$no_nota'";
        $execute = mysqli_query($this->db_handle, $query);
        $items = [];
        while($row = mysqli_fetch_assoc($execute)) {
            $items[] = $row;
        }
        return $items;
    }

    public function get_dashboard_stats() {
        $stats = [
            'pendapatan_hari_ini' => 0,
            'pesanan_hari_ini' => 0,
            'menu_terlaris' => 'Belum ada data'
        ];

        $q1 = mysqli_query($this->db_handle, "SELECT SUM(total_harga) as total FROM transaksi WHERE status='Selesai' AND DATE(tanggal_transaksi) = CURDATE()");
        if ($row1 = mysqli_fetch_assoc($q1)) {
            $stats['pendapatan_hari_ini'] = $row1['total'] ?? 0;
        }

        $q2 = mysqli_query($this->db_handle, "SELECT COUNT(DISTINCT no_nota) as total FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE()");
        if ($row2 = mysqli_fetch_assoc($q2)) {
            $stats['pesanan_hari_ini'] = $row2['total'] ?? 0;
        }

        $q3 = mysqli_query($this->db_handle, "SELECT nama_produk, SUM(qty) as total_qty FROM transaksi WHERE status='Selesai' GROUP BY id_produk ORDER BY total_qty DESC LIMIT 1");
        if ($row3 = mysqli_fetch_assoc($q3)) {
            $stats['menu_terlaris'] = $row3['nama_produk'] . " (" . $row3['total_qty'] . "x)";
        }

        return $stats;
    }

    public function get_grouped_riwayat() {
        $query = "SELECT * FROM transaksi WHERE status='Selesai' ORDER BY tanggal_transaksi DESC";
        $execute = mysqli_query($this->db_handle, $query);
        $grouped = [];
        
        while ($row = mysqli_fetch_assoc($execute)) {
            $nota = $row['no_nota'];
            if(!isset($grouped[$nota])) {
                $grouped[$nota] = [
                    'waktu' => $row['tanggal_transaksi'],
                    'pelanggan' => $row['nama_pelanggan'],
                    'meja' => $row['no_meja'],
                    'items' => [],
                    'total_tagihan' => 0
                ];
            }
            $grouped[$nota]['items'][] = $row['nama_produk'] . " (" . $row['qty'] . "x)";
            $grouped[$nota]['total_tagihan'] += $row['total_harga'];
        }
        return $grouped;
    }

    public function get_complete_dashboard_analytics($range = '7_days', $start_date = '', $end_date = '') {
        $where_clause = "WHERE status='Selesai'";
        $line_query = "";

        $start_date = mysqli_real_escape_string($this->db_handle, $start_date);
        $end_date = mysqli_real_escape_string($this->db_handle, $end_date);

        switch ($range) {
            case 'today':
                $where_clause .= " AND DATE(tanggal_transaksi) = CURDATE()";
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%H:00') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY HOUR(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
            case 'yesterday':
                $where_clause .= " AND DATE(tanggal_transaksi) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%H:00') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY HOUR(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
            case '2_days_ago':
                $where_clause .= " AND DATE(tanggal_transaksi) = DATE_SUB(CURDATE(), INTERVAL 2 DAY)";
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%H:00') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY HOUR(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
            case '3_days_ago':
                $where_clause .= " AND DATE(tanggal_transaksi) = DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%H:00') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY HOUR(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
            case '30_days':
                $where_clause .= " AND tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%d %b') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY DATE(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
            case 'custom':
                if (!empty($start_date) && !empty($end_date)) {
                    $where_clause .= " AND DATE(tanggal_transaksi) BETWEEN '$start_date' AND '$end_date'";
                }
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%d %b') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY DATE(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
            case '7_days':
            default:
                $where_clause .= " AND tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                $line_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%W') as label, SUM(total_harga) as total 
                               FROM transaksi $where_clause GROUP BY DATE(tanggal_transaksi) ORDER BY tanggal_transaksi ASC";
                break;
        }

        $stats_query = "SELECT SUM(total_harga) as total_pendapatan, COUNT(DISTINCT no_nota) as total_pesanan 
                        FROM transaksi $where_clause";
        $execute_stats = mysqli_query($this->db_handle, $stats_query);
        $stats_row = mysqli_fetch_assoc($execute_stats);
        
        $pendapatan = $stats_row['total_pendapatan'] ?? 0;
        $pesanan = $stats_row['total_pesanan'] ?? 0;

        $best_query = "SELECT nama_produk FROM transaksi $where_clause 
                       GROUP BY nama_produk ORDER BY SUM(qty) DESC LIMIT 1";
        $execute_best = mysqli_query($this->db_handle, $best_query);
        $best_row = mysqli_fetch_assoc($execute_best);
        $menu_terlaris = $best_row['nama_produk'] ?? 'Tidak ada penjualan';

        $execute_line = mysqli_query($this->db_handle, $line_query);
        $hari_indonesia = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        
        $line_labels = [];
        $line_data = [];
        while ($row = mysqli_fetch_assoc($execute_line)) {
            $label = $row['label'];
            if (isset($hari_indonesia[$label])) { $label = $hari_indonesia[$label]; }
            $line_labels[] = $label;
            $line_data[] = (int)$row['total'];
        }

        $pie_query = "SELECT nama_produk, SUM(qty) as total_qty 
                      FROM transaksi $where_clause 
                      GROUP BY nama_produk ORDER BY total_qty DESC";
        $execute_pie = mysqli_query($this->db_handle, $pie_query);
        
        $pie_labels = [];
        $pie_data = [];
        while ($row = mysqli_fetch_assoc($execute_pie)) {
            $pie_labels[] = $row['nama_produk'];
            $pie_data[] = (int)$row['total_qty'];
        }

        if (empty($line_labels)) { $line_labels = ['Belum Ada Data']; $line_data = [0]; }
        if (empty($pie_labels)) { $pie_labels = ['Belum Ada Menu']; $pie_data = [0]; }

        return [
            'pendapatan' => $pendapatan,
            'pesanan' => $pesanan,
            'menu_terlaris' => $menu_terlaris,
            'line_labels' => $line_labels,
            'line_data' => $line_data,
            'pie_labels' => $pie_labels,
            'pie_data' => $pie_data
        ];
    }

    public function update_status_proses_by_nota($no_nota) {
        $query = "UPDATE transaksi SET status='Diproses' WHERE no_nota='$no_nota'";
        return mysqli_query($this->db_handle, $query);
    }

    public function track_pesanan_pelanggan($kode_lacak) {
        $kode_lacak = mysqli_real_escape_string($this->db_handle, $kode_lacak);
        
        $query = "SELECT * FROM transaksi 
                  WHERE DATE(tanggal_transaksi) = CURDATE() 
                  AND kode_lacak = '$kode_lacak'
                  ORDER BY tanggal_transaksi DESC";
                  
        $execute = mysqli_query($this->db_handle, $query);
        
        $grouped = [];
        while ($row = mysqli_fetch_assoc($execute)) {
            $nota = $row['no_nota'];
            if(!isset($grouped[$nota])) {
                $grouped[$nota] = [
                    'waktu' => $row['tanggal_transaksi'],
                    'pelanggan' => $row['nama_pelanggan'],
                    'meja' => $row['no_meja'],
                    'status' => $row['status'],
                    'kode_lacak' => $row['kode_lacak'],
                    'items' => [],
                    'total_tagihan' => 0
                ];
            }
            $grouped[$nota]['items'][] = $row['nama_produk'] . " (" . $row['qty'] . "x)";
            $grouped[$nota]['total_tagihan'] += $row['total_harga'];
        }
        return $grouped;
    }
}

$app = new CafeSystem();
?>