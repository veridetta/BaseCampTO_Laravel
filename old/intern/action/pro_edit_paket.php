<?php
if($_POST){
    include '../../config/connect.php';
    header('Content-Type: application/json');
    $paket_id=mysqli_real_escape_string($con,$_POST['ide']);
    $status=mysqli_real_escape_string($con,$_POST['kategori']);
    $bintang=mysqli_real_escape_string($con,$_POST['harga']);
    $mulai=mysqli_real_escape_string($con,$_POST['mulai']);
    $akhir=mysqli_real_escape_string($con,$_POST['akhir']);
    $voucher=mysqli_real_escape_string($con,$_POST['voucher']);
    $keterangan=mysqli_real_escape_string($con,$_POST['keterangan']);
    function score($b,$s){
        $score=($b!=0)?($b/($b+$s))*100:0;
        $niali=0;
        if($score!=0){
            if($score<76){
                if($score<51){
                    if($score<21){
                        $nilai=10;
                    }else{
                        $nilai=8;        
                    }
                }else{
                    $nilai=6; 
                }
            }else{
                $nilai=3;
            }
        }else{
            $nilai=0;
        }
        return $nilai;
    }
    $return_arr= array(
        "id" => '',
        "pesan" => '',
        "success" => false);
        if($status==4){
            $se=mysqli_query($con, "Select * from sesi_soal where id_paket_soal='$paket_id'");
            while($sesi=mysqli_fetch_array($se)){
                $no=mysqli_query($con, "select * from soal where id_sesi_soal='$sesi[id]'");
                while($nomor=mysqli_fetch_array($no)){
                    $jawa=mysqli_query($con, "select * from user_jawaban where id_soal='$nomor[id]'");
                    $benar=0;
                    $salah=0;
                    while($jawaban=mysqli_fetch_array($jawa)){
                        if($jawaban['jawabanSiswa']==$jawaban['kunci']){
                            $benar++;
                        }else{
                            $salah++;
                        }
                    } 
                    $skoring=score($benar,$salah);
                    $presentase=($benar!=0)?($benar/($benar+$salah))*100:0;
                    $in=mysqli_query($con, "UPDATE soal set score='$skoring', menjawab_benar='$presentase' where id='$nomor[id]'");
                }
            }
        }
        $qu=mysqli_query($con, "update paket_soal set status='$status', bintang='$bintang', tgl_mulai='$mulai', tgl_selesai='$akhir', keterangan='$keterangan', voucher='$voucher' where id='$paket_id'");
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

?>