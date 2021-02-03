<?php
if($_POST){
    include '../../config/connect.php';
    header('Content-Type: application/json');
    $return_arr= array(
        "id" => '',
        "pesan" => '',
        "success" => false);
    $paket_id=mysqli_real_escape_string($con,$_POST['paket_id']);
    $soal_id=mysqli_real_escape_string($con,$_POST['soal_id']);
    $isi=mysqli_real_escape_string($con,$_POST['isi']);
    $a=mysqli_real_escape_string($con,$_POST['opsi-a']);
    $b=mysqli_real_escape_string($con,$_POST['opsi-b']);
    $c=mysqli_real_escape_string($con,$_POST['opsi-c']);
    $d=mysqli_real_escape_string($con,$_POST['opsi-d']);
    $e=mysqli_real_escape_string($con,$_POST['opsi-e']);
    $kunci=mysqli_real_escape_string($con,$_POST['kunci']);
    $pembahasan=mysqli_real_escape_string($con,$_POST['pembahasan']);
    $qu=mysqli_query($con, "insert into soal (id_paket_soal, id_sesi_soal, isi, kunci, pembahasan, a, b, c, d, e) values('$paket_id','$soal_id','$isi','$kunci','$pembahasan','$a','$b','$c','$d','$e')");
    //print_r($_POST);
    if($qu){
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