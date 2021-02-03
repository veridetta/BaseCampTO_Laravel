<?php
if($_POST){
    header('Content-Type: application/json');
    $return_arr= array(
        "id" => '',
        "pesan" => '',
        "success" => false);
    include '../../config/connect.php';
    $nama=mysqli_real_escape_string($con,$_POST['nama']);
    $kategori=mysqli_real_escape_string($con,$_POST['kategori']);
    $keterangan=mysqli_real_escape_string($con,$_POST['keterangan']);
    $status="0";
    $q=mysqli_query($con,"insert into paket_soal(nama, kategori, keterangan, status) values('$nama','$kategori','$keterangan','$status')");
    if($q){
        $pesan = "Berhasil, paket Try out berhasil dibuat";
        $return_arr['pesan']=$pesan;
        $return_arr['success']=true;
        $output = json_encode($return_arr);
        die($output);
    }else{
        $pesan = "Gagal, silahkan coba lagi.";
        $return_arr['pesan']=$pesan;
        $return_arr['success']=false;
        $output = json_encode($return_arr);
        die($output);
    }
}