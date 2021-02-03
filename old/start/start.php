<!DOCTYPE html>
<html lang="en">
<head>
  <title>BaseCampTO by Bagja College</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body style="background:white">
<?php include '../header.php';?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<style>
#message {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
}
#inner-message {
    margin: 0 auto;
}
.hilang{
    display: none;
}
</style>
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
//POST SoAL 
date_default_timezone_set('Asia/Jakarta');
if($_POST){
    $id_sesi=mysqli_real_escape_string($con,$_POST['idsoal']);
    $id_paket=mysqli_real_escape_string($con,$_POST['idpaket']);
    $durasi=mysqli_real_escape_string($con,$_POST['durasi']);
    //cek sesi siswa
    $se=mysqli_query($con, "select * from user_ujian where id_siswa='$id' and id_soal='$id_sesi'");
    $se_hitung=mysqli_num_rows($se);
    if($se_hitung<1){
        $mulai = date('Y/m/d H:i:s');
        $akhir=date("Y/m/d H:i:s", strtotime("+".$durasi." minutes"));
        $insert_sesi=mysqli_query($con, "insert into user_ujian (id_siswa, id_paket, id_soal, mulai, akhir, status, percobaan) values ('$id', '$id_paket','$id_sesi','$mulai', '$akhir','1','1')");
    }
    // cek jawaban siswa
    $so=mysqli_query($con, "select * from user_jawaban where id_siswa='$id' and id_sesi='$id_sesi'");
    $so_hitung=mysqli_num_rows($so);
    $nomor_soal=1;
    if($so_hitung<1){
        $se=mysqli_query($con, "select * from soal where id_sesi_soal='$id_sesi' order by rand(UNIX_TIMESTAMP(NOW()))");
        while($sel=mysqli_fetch_array($se)){
            $kunci=$sel['kunci'];
            $jawabanSiswa="";
            $id_soal=$sel['id'];
            $in=mysqli_query($con, "insert into user_jawaban (id_siswa, id_paket, nomor_soal, kunci, jawabanSiswa, id_soal, id_sesi) Values('$id', '$id_paket', '$nomor_soal','$kunci','$jawabanSiswa', '$id_soal', '$id_sesi')");
            $nomor_soal++;
        }
    }
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
//cek paket soal yang aktif
$ak=mysqli_query($con, "select * from paket_soal where status='2'");
$aktif=mysqli_fetch_assoc($ak);
//cek user aktif
$us=mysqli_query($con, "select * from user_ujian where id_paket='$aktif[id]' and id_siswa='$id' and status='1'");
$user=mysqli_fetch_assoc($us);
//waktu ujian
$skrg = new DateTime(date('Y/m/d H:i:s'));
$akhir_ujian=$user['akhir'];
$sisa_waktu = $skrg->diff(new DateTime($akhir_ujian));
$menit=$sisa_waktu->i;
$detik=$sisa_waktu->s;
$total_sisa=$menit.":".$detik;
//cek sesi aktif
$nomor_sesi=mysqli_num_rows($us);
//select sesi aktif
$ses=mysqli_query($con, "select * from sesi_soal where id='$user[id_soal]'");
$sesi=mysqli_fetch_assoc($ses);

?>
<div class="sticky-top" style="margin:20px 12px;">
    <div id=""><button class="btn btn-info"><span class="spinner-grow spinner-grow-sm"></span>&nbsp;&nbsp;&nbsp;<span  id="timer"></span></button><button class="btn btn-info" id="nomor_soal" data-toggle="modal" data-target="#myModal" style="margin-left:12px;"></button></div>
</div>
<div class="col-12 row row-imbang primary" style="margin-top:60px;margin-bottom:60px;">
    <div id="soal" name="soal" class="col-12">
        
    </div>
    <div id="footer" class="col-12 row justify-content-end">
        <button style="margin-right:12px;" id="sebelumnya" class="btn btn-secondary">Sebelumnya</button><button class="btn btn-primary" id="berikutnya">Berikutnya</button>
    </div>
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Nomor Soal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-12 row" id="panel-control">
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="finishModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Yakin Selesai?</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-12 row" id="panel-control">
                    <p>Kamu yakin akan mengirimkan jawabanmu sekarang?</p>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                <button type="button" id="yakinFinish" class="btn btn-success">Yakin</button>
            </div>

            </div>
        </div>
    </div>
    <div id="message">
        <div style="padding: 5px;">
            <div class="alert hilang alert-danger" id="pessan" role="alert">
                Waktu sudah habis, kamu akan dialhikan dalam 3 detik.
            </div>
        </div>
    </div>
    <?php
    //total soal
    $t_s=mysqli_query($con, "select * from soal where id_sesi_soal='$sesi[id]'");
    $a_soal=1;
    $t_soal=mysqli_num_rows($t_s);
    ?>
    <input type="hidden" id="nosoalupdate" value="<?php echo $a_soal;?>">
    <script>
        $(document).ready(function(){
            //var soalke="<?php echo $a_soal;?>";
            var nosoal=$("#nosoalupdate");
            var soalke=nosoal.val();
            var totalSoal="<?php echo $t_soal;?>";
            /* --TIMER OLD
            var timer2 = "<?php echo $total_sisa;?>";
            var interval = setInterval(function() {
                var timer = timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer[0], 10);
                var seconds = parseInt(timer[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0){
                    clearInterval(interval);
                    alert('expired');
                };
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                //$('#timer').html(minutes + ':' + seconds);
                timer2 = minutes + ':' + seconds;
            }, 1000);
            */
            //var countDownDate = new Date("<?php echo $akhir_ujian;?>").getTime();
            var countDownDate = moment("<?php echo $akhir_ujian;?>").toDate();
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
                $("#message").toggleClass('hilang');
                setTimeout(function(){window.location.replace("launch.php"); }, 3000);
                
            }
            }, 1000);
            //getjawaban
            $.get( "action/nav.php?id_siswa=<?php echo $id;?>&&id_sesi=<?php echo $sesi['id'];?>&&nama=<?php echo $sesi['nama_sesi'];?>", function( data ) {
                $( "#panel-control" ).html( data );
            });
            //getsoal
            $.get( "action/soal.php?idSesi=<?php echo $sesi['id'];?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi['nama_sesi'];?>", function( data ) {
                $( "#soal" ).html( data );
                $("#nomor_soal").html(soalke);
            });
            $("#berikutnya").click(function(){
                if(nosoal.val()==totalSoal){
                    /*$.get( "action/finish.php?idd=<?php echo $id;?>&&nomor="+soalke+"&&ujian=<?php echo $user['id'];?>", function( data ) {
                        if(data){
                            $("#pessan").html('Kamu akan dialihkan.');
                            $("#message").toggleClass('hilang');
                            setTimeout(function(){window.location.replace("launch.php?lanjut=1"); }, 1000);
                        }
                    });*/
                    $("#finishModal").modal('toggle');
                    $("#yakinFinish").click(function(){
                        $.get( "action/finish.php?idd=<?php echo $id;?>&&nomor="+nosoal.val()+"&&ujian=<?php echo $user['id'];?>", function( data ) {
                            if(data){
                                $("#pessan").html('Kamu akan dialihkan.');
                                $("#message").toggleClass('hilang');
                                setTimeout(function(){window.location.replace("launch.php?lanjut=1"); }, 1000);
                            }
                        });
                    })
                }else if(nosoal.val()<=totalSoal){
                    if(nosoal.val()==totalSoal-1){
                        $(this).html('Selesai');
                    }
                    $("#sebelumnya").prop('disabled', false);
                    //soalke=$('#nosoalupdate').val();
                    var ss=parseInt(nosoal.val(), 10) + 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get( "action/soal.php?idSesi=<?php echo $sesi['id'];?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi['nama_sesi'];?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }else if(nosoal.val()>20){
                    alert('Error');
                }else{
                    $("#sebelumnya").prop('disabled', false);
                    var ss=parseInt(nosoal.val(), 10) + 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get( "action/soal.php?idSesi=<?php echo $sesi['id'];?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi['nama_sesi'];?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }
            });
            $("#sebelumnya").prop('disabled', true);
            $("#sebelumnya").click(function(){
                if(nosoal.val()==1){
                    $("#sebelumnya").prop('disabled', true);
                }else{
                    $("#berikutnya").html('Berikutnya');
                    $("#sebelumnya").prop('disabled', false);
                    //soalke=$('#nosoalupdate').val();
                    var ss=parseInt(nosoal.val(), 10) - 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get( "action/soal.php?idSesi=<?php echo $sesi['id'];?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi['nama_sesi'];?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }
            })
            //panel control
        })
    </script>
</div>