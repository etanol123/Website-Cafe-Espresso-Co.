<?php
require_once 'koneksi.php';
$db = new Database();

if(isset($_POST['pesan'])){
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $id_produk = $_POST['id_produk'];

    $catatan_array = isset($_POST['catatan']) ? $_POST['catatan'] : ['Tidak ada catatan']; 
    
    $id_transaksi = $db->simpan_transaksi($nama_pelanggan, $id_produk, $catatan_array);
    
    $id_aman = base64_encode($id_transaksi);
    header("location: nota.php?inv=$id_aman");
}
?>