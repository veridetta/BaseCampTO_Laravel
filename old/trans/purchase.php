<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tambah Bintang - BaseCampTO by Bagja College</title>
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
    $tag=mysqli_query($con, "select * from tagihan where id_siswa='$id' and expires>=NOW() and status='1'");
    $tex=0;
    if(mysqli_num_rows($tag)>0){
        $tex=1;
        $tagihan=mysqli_fetch_assoc($tag);
    }else{
        $tex=0;
    }
}else{
     $gagal=1;
}
if($gagal>0){
    header('location:/home.php');
}
?>
<div class="col-12 row row-imbang primary" style="margin-top:60px;">
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
    <div class="col-12">
        <p class="h4 text-success"><i class="fa fa-money"></i> Pembelian Bintang</p>
        <hr>   
        <p class="h5" style="">Pilih Jumlah Bintang</p>
        <p class=" text-muted" style="margin-bottom:22px;">1 Paket Soal menghabiskan 90-97 <i class="fa fa-star"></i></p>
        <div class="alert alert-warning alert-dismissible fade <?php if($tex>0){ echo 'show';};?>" role="alert" id="info">
            <strong>Info!</strong> Kamu punya tagihan yang belum dibayar sebesar <strong><?php if($tex>0){ echo "Rp".number_format($tagihan['tagihan'],2,",",".");};?></strong>, lihat cara pembayaran <a href="cara.php" class="text-success">disini</a>.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php if($tex>0){
            ?>
                <div class="alert alert-success alert-dismissible fade <?php if($tex>0){ echo 'show';};?>" role="alert" id="sudah">
                    <strong>Sudah Transfer?</strong>  Cek Status Pembayaran pembayaran <a href="#" class="text-danger" id="btn-cek">disini</a>.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-info alert-dismissible fade" role="alert" id="cek-info">
                    <strong>Sudah Transfer?</strong>  Cek Status Pembayaran pembayaran <a href="cara.php" class="text-danger">disini</a>.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
        };
        ?>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="card border-secondary">
                    <div class="card-body">
                        <p class="display-3 text-center text-warning"><i class="fa fa-star"></i></p>
                        <p class="h1 text-center">100</p>
                        <p class="text-center text-secondary">Setara lebih dari 1 paket soal</p>
                        <hr>
                        <button class="btn btn-info btn-block btn-beli">Rp20.000</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-secondary">
                    <div class="card-body">
                        <p class="display-3 text-center text-warning"><i class="fa fa-star"></i></p>
                        <p class="h1 text-center">200</p>
                        <p class="text-center text-secondary">Setara lebih dari 2 paket soal</p>
                        <hr>
                        <button class="btn btn-info btn-block btn-beli">Rp35.000</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-danger">
                    <div class="card-body">
                        <span class="badge badge-danger" style="position: absolute;top: -4px;left: -16px;padding:5px;transform: rotate(-17deg);">Paling Laris</span>
                        <p class="display-3 text-center text-warning"><i class="fa fa-star"></i></p>
                        <p class="h1 text-center">500</p>
                        <p class="text-center text-secondary">Setara lebih dari 5 paket soal</p>
                        <hr>
                        <button class="btn btn-danger btn-block btn-beli">Rp80.000</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-secondary">
                    <div class="card-body">
                        <p class="display-3 text-center text-warning"><i class="fa fa-star"></i></p>
                        <p class="h1 text-center">1000</p>
                        <p class="text-center text-secondary">Setara lebih dari 10 paket soal</p>
                        <hr>
                        <button class="btn btn-info btn-block btn-beli">Rp150.000</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-12">
        
    </div>
    <form method="post" id="buy" name="buy" action="action/generate.php">
        <input type="hidden" name="nominal" id="nominal" value="">
        <input type="submit" name="btn" id="btn" style="display:none;">
    </form>
    <form method="post" id="cek" name="cek">
        <input type="hidden" name="nominal" id="nominal" value="">
        <input type="submit" name="btn" id="btn" style="display:none;">
    </form>
    <script>
        $(document).ready(function(){
            $(".btn-beli").click(function(e){
                var isii=$(this).html();
                var info = $("#info");
                $("#nominal").val(isii);
                
                e.preventDefault();
                    $.ajax({
                    type: "POST",
                    url: "action/generate.php",
                    data: $("#buy").serialize(),
                    dataType: "json"
                    }).done(function(data) {
                        if(data.success) {
                            info.html(data.pesan).css('color', 'green');
                            $("#sudah").removeClass('show');
                            info.removeClass('show');
                            info.addClass('show');
                            //setTimeout(function() {
                            //    window.location.replace("home.php");
                        // }, 1500);
                        } else {
                            info.html(data.pesan).css('color', 'red');
                            info.removeClass('show');
                            info.addClass('show');
                        }
                    });
            })
            $("#btn-cek").click(function(e){
                var info = $("#cek-info");
                e.preventDefault();
                    $.ajax({
                    type: "POST",
                    url: "action/update.php",
                    data: $("#cek").serialize(),
                    dataType: "json"
                    }).done(function(data) {
                        if(data.success) {
                            info.html(data.pesan).css('color', 'green');
                            info.addClass('show');
                            setTimeout(function() {
                                window.location.replace("/home.php");
                         }, 1500);
                        } else {
                            info.html(data.pesan).css('color', 'red');
                            info.addClass('show');
                        }
                    });
            })
        })
    </script>
</div>