<?php
if($_POST){
    include '../../config/connect.php';
    header('Content-Type: application/json');
    $return_arr= array(
        "id" => '',
        "pesan" => '',
        "success" => false);
    $jawab=mysqli_real_escape_string($con,$_POST['jawab']);
    $idnya=mysqli_real_escape_string($con,$_POST['idnya']);
    $qq=mysqli_query($con, "update user_jawaban set jawabanSiswa='$jawab' where id='$idnya'");
    if($qq){
        $pesan = "Berhasil, Soal berhasil dibuat";
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