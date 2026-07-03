<?php
session_start();

class Database {
   
    private $host = "localhost";
    private $uname = "root";
    private $pass = "";
    private $db = "db_cafe_uas";
    public $koneksi;

  
    function __construct() {
        $this->koneksi = mysqli_connect($this->host, $this->uname, $this->pass, $this->db);
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
        }
    }

    
    function login($username, $password) {
        $data = mysqli_query($this->koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
        if(mysqli_num_rows($data) > 0) {
            $user = mysqli_fetch_array($data);
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama_kasir'] = $user['nama_kasir'];
            $_SESSION['token'] = base64_encode($user['username'] . date('Ymd')); 
            return true;
        }
        return false;
    }

    
    function tampil_produk() {
        $data = mysqli_query($this->koneksi, "SELECT * FROM produk");
        $hasil = [];
        while($d = mysqli_fetch_array($data)){
            $hasil[] = $d;
        }
        return $hasil;
    }

    
    function tampil_nota($id_transaksi) {
        $data = mysqli_query($this->koneksi, "SELECT * FROM transaksi WHERE id_transaksi='$id_transaksi'");
        return mysqli_fetch_array($data);
    }

    
    function simpan_transaksi($nama_pelanggan, $id_produk, $catatan_array) {
        $catatan_string = implode(", ", $catatan_array); 
        
       
        $produk = mysqli_query($this->koneksi, "SELECT nama_produk, harga FROM produk WHERE id_produk='$id_produk'");
        $p = mysqli_fetch_assoc($produk);
        $nama_produk = $p['nama_produk'];
        $total_harga = $p['harga'];

        $no_nota = "INV-" . date('YmdHis');
        $tanggal = date('Y-m-d H:i:s');

       
        mysqli_query($this->koneksi, "INSERT INTO transaksi (no_nota, nama_pelanggan, nama_produk, catatan_pesanan, total_harga, status, tanggal_transaksi) VALUES ('$no_nota', '$nama_pelanggan', '$nama_produk', '$catatan_string', '$total_harga', 'Pending', '$tanggal')");
        
        return mysqli_insert_id($this->koneksi); 
    }

    
    function tampil_antrean() {
        $data = mysqli_query($this->koneksi, "SELECT * FROM transaksi WHERE status='Pending' ORDER BY tanggal_transaksi ASC");
        $hasil = [];
        while($d = mysqli_fetch_array($data)){
            $hasil[] = $d;
        }
        return $hasil;
    }

   
    function selesaikan_pesanan($id_transaksi) {
        mysqli_query($this->koneksi, "UPDATE transaksi SET status='Selesai' WHERE id_transaksi='$id_transaksi'");
    }
}
?>