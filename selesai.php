<?php
require_once 'koneksi.php';
$db = new Database();

if(isset($_GET['id'])){
    $db->selesaikan_pesanan($_GET['id']);
}

header("location: index.php");
?>