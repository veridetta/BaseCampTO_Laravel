<?php
    //waktu ujian
    if($_GET){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "success" => false);
        include '../../config/connect.php';
        header('Content-Type: application/json');
        $id=mysqli_real_escape_string($con,$_GET['idd']);
        $ujian=mysqli_real_escape_string($con,$_GET['ujian']);
        //waktu ujian
        date_default_timezone_set('Asia/Jakarta');
        $skrg = date("Y-m-d H:i:s");
            $ubah=mysqli_query($con, "update user_ujian set akhir='$skrg' where id='$ujian'");
            if($ubah){
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
?>