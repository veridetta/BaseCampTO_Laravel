<?php
    if($_POST){
        include '../config/connect.php';
        $return_arr= array(
                    "id" => '',
                    "pesan" => '',
                    "success" => false);
        $email = mysqli_real_escape_string($con,$_POST['email']);
        $password = mysqli_real_escape_string($con,$_POST['password']);
        $nama = mysqli_real_escape_string($con,$_POST['nama']);
        $sekolah = mysqli_real_escape_string($con,$_POST['sekolah']);
        $hp = mysqli_real_escape_string($con,$_POST['hp']);
        $va = "";
        //$va = mysqli_real_escape_string($con,$_POST['va']);
        $friend_ref=mysqli_real_escape_string($con, $_POST['ref_friend']);
        $ref = $bytes = bin2hex(random_bytes(3));;
        $query = mysqli_query($con, "insert into user(email, password, nama, sekolah, hp, va, my_ref, friend_ref, role) VALUES('$email','$password','$nama','$sekolah','$hp','$va','$ref','$friend_ref','user')");
         if($query){
            $pesan = "Pendaftaran berhasil, kamu akan segera di alihkan ";
            $return_arr['pesan']=$pesan;
            $return_arr['success']=true;
            $output = json_encode($return_arr);
            die($output);
        }else{
            $return_arr['pesan']="Pendaftaran gagal, silahkan coba beberapa saat lagi";
            $return_arr['success']=false;
            $output = json_encode($return_arr);
            die($output);
        }
    }else{
        echo "no post";
    }
?>