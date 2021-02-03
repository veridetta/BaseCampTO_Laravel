<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bagja College Try Out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="background-color:#f8f8f8">
<?php include '../header.php';?>
<?php 
$gagal=0;
if($_SESSION){
    $nama=$_SESSION['nama'];
    $id=$_SESSION['id'];
    $ref=$_SESSION['ref'];
    //status riwayat
    //1 topup
    //2 pembelian
    $sa=mysqli_query($con, "select * from riwayat_bintang where id_users='$id' order by id desc limit 1");
    $sal=mysqli_fetch_assoc($sa);
    $hitung=mysqli_num_rows($sa);
    $saldo=$sal['saldo'];
    if($hitung<1){
        $saldo=0;
    }
}else{
     $gagal=1;
}
// cek status paket soal
$tp=mysqli_query($con, "select * from paket_soal where status='2'");
$tps=mysqli_fetch_assoc($tp);
$hitung=mysqli_num_rows($tp);
if($hitung<1){
    $gagal=1;
    echo $hitung;
}
if($gagal>0){
    header('location:/home.php');
}
?>
<div class="col-12 row row-imbang primary" style="background:white;margin-top:60px;">
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-danger"><i class="fa fa-user"></i> Profile</p>
            <hr>   
        </div>            
        <div class="col-6">
            <p class="h4"><?php echo $nama;?></p>
            <p class="h6">Code Referal : <?php echo $ref;?></p>
        </div>
        <div class="col-6 text-right">
            <span class="h4 text-right"><i class="text-warning fa fa-star"></i> <?php echo $saldo;?></span>
            <button class="btn btn-outline-warning h4">Tambah</button>
        </div>
        <hr>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-danger"><i class="fa fa-book"></i> <?php echo $tps['nama'];?></p>
            <hr>   
        </div>            
        <div class="col-6">
            <p class="h4">Tes Pengetahuan Skolastik & Akademik</p>
            <hr>
        </div>
        <div class="col-12 row justify-content-center">
            <?php
                $tpp=mysqli_query($con,"select * from sesi_soal where id_paket_soal='$tps[id]'");
                $notps=0;
                $insert=0;
                $selesaie=0;
                $tpp_hitung=mysqli_num_rows($tpp);
               // echo $tpp_hitung;
                while($tpps=mysqli_fetch_array($tpp)){
                    $status=mysqli_query($con,"select * from user_ujian where id_soal='$tpps[id]' and id_siswa='$id'");
                    $status_siswa=mysqli_fetch_assoc($status);
                    $hitung_so=mysqli_query($con, "select * from soal where id_sesi_soal='$tpps[id]'");
                    $hitung_soal=mysqli_num_rows($hitung_so);
                    $hitung_sesi=mysqli_num_rows($status);
                    if($hitung_sesi<1){
                        if($notps<1){
                            ?>
                            <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                        </div>
                                        <div class="card-body">
                                        <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                            <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                        </div>
                                        <div class="card-footer">
                                            <form method="post" action="start.php" name="mulai" id="mulai">
                                                <input type="hidden" name="durasi" value="<?php echo $tpps['durasi'];?>">
                                                <input type="hidden" name="idsoal" value="<?php echo $tpps['id'];?>">
                                                <input type="hidden" name="idsiswa" value="<?php echo $id;?>">
                                                <input type="hidden" name="idpaket" value="<?php echo $tps['id'];?>">
                                                <button class="btn btn-primary btn-block">Mulai</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }else{
                            if($insert>0){
                                date_default_timezone_set('Asia/Jakarta');
                                $mulaie = date('Y-m-d H:i:s');
                                $akhire=date("Y-m-d H:i:s", strtotime("+".$tpps['durasi']." minutes"));
                               $sql=mysqli_query($con, "insert into user_ujian (id_siswa, id_paket, id_soal, mulai, akhir, status, percobaan) values ('$id', '$tpps[id_paket_soal]','$tpps[id]','$mulaie', '$akhire','1','1')");
                                if($sql){
                                    $insert=0;
                                    ?>
                                    <script>
                                        location.reload();
                                    </script>
                                    <?php
                                }
                            }
                            ?>
                            <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="tutup card row" style="width:85%; min-height: 100px;position: absolute;background: black;opacity:0.4;">
                                        <p class="text-center my-auto text-white">Belum</p>
                                    </div>
                                    <div class="card buka2" style="position:initial;">
                                        <div class="card-header">
                                            <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                        </div>
                                        <div class="card-body">
                                            <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                            <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-disabled btn-block">Mulai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }else{
                        if($status_siswa['status']==1){
                            //waktu ujian
                            date_default_timezone_set('Asia/Jakarta');
                            $skrg = new DateTime(date('Y-m-d H:i:s'));
                            $akhir_ujian=$status_siswa['akhir'];
                            if($skrg>new DateTime($akhir_ujian)){
                                $ubah=mysqli_query($con, "update user_ujian set status='2' where id='$status_siswa[id]'");
                                if($ubah){
                                    $insert=1;
                                }else{
                                    $insert=0;
                                   
                                }
                               ?>
                               <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                    <div class="col-12">
                                        <div class="tutup card row" style="width:85%; min-height: 100%;position: absolute;background: black;opacity:0.4;">
                                            <p class="text-center my-auto text-white">Selesai</p>
                                        </div>
                                        <div class="card buka" style="position:initial;">
                                            <div class="card-header">
                                                <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                            </div>
                                            <div class="card-body">
                                                <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                                <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-disabled btn-block">Mulai</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <?php
                            }else{
                                //waktu ujian
                                $skrg = new DateTime(date('Y/m/d H:i:s'));
                                $akhir_ujian=$status_siswa['akhir'];
                                $sisa_waktu = $skrg->diff(new DateTime($akhir_ujian));
                                $menit=$sisa_waktu->i;
                                $detik=$sisa_waktu->s;
                                $total_sisa=$menit.":".$detik;
                                $insert=0;
                                ?>
                                <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                        </div>
                                        <div class="card-body">
                                        <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                            <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                            <hr>
                                            <p class="text-center"><span id="timer"></span> </p>
                                        </div>
                                        <div class="card-footer">
                                            <form method="post" action="start.php" name="mulai" id="mulai">
                                                <input type="hidden" name="durasi" value="<?php echo $tpps['durasi'];?>">
                                                <input type="hidden" name="idsoal" value="<?php echo $tpps['id'];?>">
                                                <input type="hidden" name="idsiswa" value="<?php echo $id;?>">
                                                <input type="hidden" name="idpaket" value="<?php echo $tps['id'];?>">
                                                <button class="btn btn-primary btn-block">Mulai</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php
                            }
                        }else if($status_siswa['status']==2){
                            $selesaie++;
                            ?>
                            <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="tutup card row" style="width:85%; min-height: 100%;position: absolute;background: black;opacity:0.4;">
                                        <p class="text-center my-auto text-white">Selesai</p>
                                    </div>
                                    <div class="card buka" style="position:initial;">
                                        <div class="card-header">
                                            <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                        </div>
                                        <div class="card-body">
                                            <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                            <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-disabled btn-block">Mulai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }else if($status_siswa['status']==3){
                            ?>
                            <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="tutup card row" style="width:85%; min-height: 100px;position: absolute;background: black;opacity:0.4;">
                                        <p class="text-center my-auto text-white">Selesai</p>
                                    </div>
                                    <div class="card buka" style="position:initial;">
                                        <div class="card-header">
                                            <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                        </div>
                                        <div class="card-body">
                                            <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                            <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-disabled btn-block">Mulai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }else{
                            ?>
                            <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="tutup card row" style="width:85%; min-height: 100px;position: absolute;background: black;opacity:0.4;">
                                        <p class="text-center my-auto text-white">Belum</p>
                                    </div>
                                    <div class="card buka2" style="position:initial;">
                                        <div class="card-header">
                                            <p class="card-title"><?php echo $tpps['nama_sesi'];?></p>
                                        </div>
                                        <div class="card-body">
                                            <p>Total Soal : <?php echo $hitung_soal;?> Soal
                                            <p>Durasi : <?php echo $tpps['durasi'];?> Menit</p>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-disabled btn-block">Mulai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    $notps++;
            ?>
           <?php
                }
               // echo $selesaie;
           ?>
        </div>
        <hr>
    </div>
</div>
<script>
$(document).ready(function(){
    var tinggi = $('.buka2').height();
    $('.tutup').height(tinggi);
    /*var timer2 = "<?php echo $total_sisa;?>";
            var interval = setInterval(function() {
            var timer = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#sisaw').html("Sisa Waktu : "+minutes + ':' + seconds);
            timer2 = minutes + ':' + seconds;
            }, 1000);*/
            //var countDownDate = new Date("<?php echo $akhir_ujian;?>").getTime();
            var countDownDate = Date.parse("<?php echo $akhir_ujian;?>");
            // Update the count down every 1 second
            var x = setInterval(function() {
            // Get today's date and time
            var now = new Date().getTime();
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            // Display the result in the element with id="demo"
            document.getElementById("timer").innerHTML = minutes + " : " + seconds;
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $("#message").toggleClass('sembunyi');
                setTimeout(function(){window.location.replace("launch.php"); }, 3000);
                
            }
            }, 1000);
})
</script>