<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pembahasan - BaseCampTO by Bagja College</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
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
if($_POST){
    $id_paket=mysqli_real_escape_string($con, $_POST['id_paket']);
}else{
    $gagal=1;
}
// cek status paket soal
$tp=mysqli_query($con, "select * from paket_soal where id='$id_paket'");
$tps=mysqli_fetch_assoc($tp);
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
                $tpp=mysqli_query($con,"select * from sesi_soal where id_paket_soal='$id_paket'");
                $notps=0;
                $tpp_hitung=mysqli_num_rows($tpp);
               // echo $tpp_hitung;
                while($tpps=mysqli_fetch_array($tpp)){
                    $hitung_so=mysqli_query($con, "select * from soal where id_sesi_soal='$tpps[id]'");
                    $hitung_soal=mysqli_num_rows($hitung_so);
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
                                            <form method="post" action="pembahasan.php" name="mulai" id="mulai">
                                                <input type="hidden" name="durasi" value="<?php echo $tpps['durasi'];?>">
                                                <input type="hidden" name="idsoal" value="<?php echo $tpps['id'];?>">
                                                <input type="hidden" name="idsiswa" value="<?php echo $id;?>">
                                                <input type="hidden" name="idpaket" value="<?php echo $tps['id'];?>">
                                                <button class="btn btn-primary btn-block">Lihat</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
           <?php
                }
           ?>
        </div>
        <hr>
    </div>
</div>
<?php include '../footer.php';?>
<script>
$(document).ready(function(){
    var tinggi = $('.buka2').height();
    $('.tutup').height(tinggi);
})
</script>