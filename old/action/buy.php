<?php
    if($_POST){
        include '../config/connect.php';
        $return_arr= array(
                    "id" => '',
                    "pesan" => '',
                    "judul"=>'',
                    "success" => false);
        $id_paket = mysqli_real_escape_string($con,$_POST['id_paket_soal']);
        $id_user = mysqli_real_escape_string($con,$_POST['id_user']);
        $voucher = mysqli_real_escape_string($con,$_POST['voucher']);
        $so=mysqli_query($con, "select * from paket_soal where id='$id_paket'");
        $soal=mysqli_fetch_assoc($so);
        $harga=$soal['bintang'];
        $sa=mysqli_query($con, "select * from riwayat_bintang where id_users='$id_user' order by id desc limit 1");
        $sal=mysqli_fetch_assoc($sa);
        $hitung=mysqli_num_rows($sa);
        $saldo=$sal['saldo'];
        $hvoucher=strlen($voucher);
        if($hvoucher>7){
            $v=mysqli_query($con, "select * from paket_soal where id='$id_paket' and voucher='$voucher'");
            if(mysqli_num_rows($v)>0){
                $sisa=$saldo-0;
                $be=mysqli_query($con, "insert into riwayat_bintang (id_users, nominal, status, saldo) values('$id_user','0','2','$sisa')");
                if($be){
                    //status user :
                    //0 === tidak terdaftar
                    //1 === belum mengerjakan
                    //2 === sedang mengerjakan
                    //3 === sudah
                    $pak=mysqli_query($con, "insert into peserta_paket (id_user, id_paket, status) values ('$id_user','$id_paket','1')");
                    if($pak){
                        $pesan = "Pembayaran Berhasil, kamu akan segera di alihkan ";
                        $judul="Pembelian berhasil";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=true;
                        $output = json_encode($return_arr);
                        die($output);
                    }else{
                        $pesan = "Error tidak diketahui ";
                        $judul="Pembelian gagal";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=false;
                        $output = json_encode($return_arr);
                        die($output);
                    }
                    
                }else{
                    $pesan = "Error tidak diketahui ";
                    $judul="Pembelian gagal";
                    $return_arr['pesan']=$pesan;
                    $return_arr['judul']=$judul;
                    $return_arr['success']=false;
                    $output = json_encode($return_arr);
                    die($output);
                }
            }else{
                $pesan = "Kode Voucher yang dimasukkan tidak berlaku, silahkan periksa kembali kode voucher anda, atau silahkan lakukan pembelian tanpa menggunakan voucher";
                $judul="Pembelian gagal";
                $return_arr['pesan']=$pesan;
                $return_arr['judul']=$judul;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);
            }
        }else{
            if($saldo>=$harga){
                $sisa=$saldo-$harga;
                //status riwayat
                //1 topup
                //2 pembelian
                $be=mysqli_query($con, "insert into riwayat_bintang (id_users, nominal, status, saldo) values('$id_user','$harga','2','$sisa')");
                if($be){
                    //status user :
                    //0 === tidak terdaftar
                    //1 === belum mengerjakan
                    //2 === sedang mengerjakan
                    //3 === sudah
                    $pak=mysqli_query($con, "insert into peserta_paket (id_user, id_paket, status) values ('$id_user','$id_paket','1')");
                    if($pak){
                        $pesan = "Pembayaran Berhasil, kamu akan segera di alihkan ";
                        $judul="Pembelian berhasil";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=true;
                        $output = json_encode($return_arr);
                        die($output);
                    }else{
                        $pesan = "Error tidak diketahui ";
                        $judul="Pembelian gagal";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=false;
                        $output = json_encode($return_arr);
                        die($output);
                    }
                    
                }else{
                    $pesan = "Error tidak diketahui ";
                    $judul="Pembelian gagal";
                    $return_arr['pesan']=$pesan;
                    $return_arr['judul']=$judul;
                    $return_arr['success']=false;
                    $output = json_encode($return_arr);
                    die($output);
                }
            }else{
                $pesan = "Bintang kamu tidak mencukupi ";
                $judul="Pembelian gagal";
                $return_arr['pesan']=$pesan;
                $return_arr['judul']=$judul;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);
            }
        }
    }else{
        echo "no post";
    }
?>