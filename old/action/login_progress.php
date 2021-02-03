<?php
    if($_POST){
        include '../config/connect.php';
        $return_arr= array(
                    "id" => '',
                    "pesan" => '',
                    "success" => false);
        $email = mysqli_real_escape_string($con,$_POST['email']);
        $password = mysqli_real_escape_string($con,$_POST['password']);
        $qu = mysqli_query($con, "select * from user where email='$email' and password='$password'");
        $hitung=mysqli_num_rows($qu);
         if($hitung>0){
            session_start();
            $query=mysqli_fetch_assoc($qu);
            $name=$query['nama'];
            $sekolah=$query['sekolah'];
            $ref=$query['my_ref'];
            $id=$query['id'];
            $_SESSION['nama']=$name;
            $_SESSION['sekolah']=$sekolah;
            $_SESSION['id']=$id;
            $_SESSION['ref']=$ref;
            $pesan = "Login berhasil, kamu akan segera di alihkan ";
            $return_arr['pesan']=$pesan;
            $return_arr['success']=true;
            $output = json_encode($return_arr);
            die($output);
        }else{
            $pesan = "Login gagal, Silahkan coba lagi";
            $return_arr['pesan']=$pesan;
            $return_arr['success']=false;
            $output = json_encode($return_arr);
            die($output);
        }
    }else{
        echo "no post";
    }
?>