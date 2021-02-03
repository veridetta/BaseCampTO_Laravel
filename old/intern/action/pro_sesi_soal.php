<?php
if($_POST){
    header('Content-Type: application/json');
    $return_arr= array(
        "id" => '',
        "pesan" => '',
        "success" => false);
    include '../../config/connect.php';
    $id_paket_soal=mysqli_real_escape_string($con,$_POST['id_paket_soal']);
    $nama_sesi=mysqli_real_escape_string($con,$_POST['nama_sesi']);
    $durasi=mysqli_real_escape_string($con,$_POST['durasi']);
    $urutan=mysqli_real_escape_string($con,$_POST['urutan']);
    $induk_sesi=mysqli_real_escape_string($con,$_POST['induk_sesi']);
    $q=mysqli_query($con,"insert into sesi_soal(id_paket_soal, nama_sesi, durasi, urutan,induk_sesi) values('$id_paket_soal','$nama_sesi','$durasi','$urutan','$induk_sesi')");
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