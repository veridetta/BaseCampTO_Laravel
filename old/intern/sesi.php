<?php 
    include '../config/connect.php';
    $sesi = $_GET['sesi'];
    $paket= $_GET['paket'];
    $qu=mysqli_query($con,"select * from soal where id_paket_soal='$paket' and id_sesi_soal='$sesi' order by id");
    $que=mysqli_query($con,"select * from soal where id_paket_soal='$paket' and id_sesi_soal='$sesi' order by id");
    $da=mysqli_query($con,"select * from sesi_soal where id='$sesi'");
    $dat=mysqli_fetch_assoc($da);
?>
<div class="card">
    <div class="card-header">
        <p class="h4"><small><a href="home.php?menu=soal" class="text-primary"><i class="fa fa-arrow-left"></i></a></small><?php echo $dat['nama_sesi'];?></p>
    </div>
    <div class="card-body">
        <div class="col-12 row row-imbang" data-toggle="collapse" href="#collapse1">
            <div class="col-6">
            <?php
                $no=0;
                while($data=mysqli_fetch_array($qu)){
                    $no++;
                    if($no<11){
            ?>
                <p class="h7">Nomor <?php echo $no;?> &nbsp;&nbsp;&nbsp;<button class="button btn btn-success"><span class="text-uppercase"><?php echo $data['kunci'];?></span></button>
                <button class="button btn btn-secondary edit-soal" sesi="<?php echo $_GET['sesi'];?>" paket="<?php echo $_GET['paket'];?>" id-nomor="<?php echo $data['id'];?>" style="margin-right:3px;" href="soal"><i class="fa fa-edit"></i></button></p>
                <?php 
                    }
                }
                ?>
            </div>
            <div class="col-6">
            <?php
                $noz=0;
                $nom=10;
                while($data2=mysqli_fetch_array($que)){
                    $noz++;
                    if($noz>10){
                        $nom++;
            ?>
                <p class="h7">Nomor <?php echo $nom;?> &nbsp;&nbsp;&nbsp;<button class="button btn btn-success"><span class="text-uppercase"><?php echo $data2['kunci'];?></span></button>
                <button class="button btn btn-secondary edit-soal" sesi="<?php echo $_GET['sesi'];?>" paket="<?php echo $_GET['paket'];?>" id-nomor="<?php echo $data2['id'];?>" style="margin-right:3px;" href="soal"><i class="fa fa-edit"></i></button></p>
                <?php 
                    }
                }
                ?>
            </div>
        </div>
        <hr>
    </div>
    <div class="card-footer">
        <div class="col-12 row justify-content-center h-60">
            <div class="my-auto">
                <button class="btn button btn-success" id="tambah-soal" href="soal" sesi="<?php echo $_GET['sesi'];?>" paket="<?php echo $_GET['paket'];?>"><i class="fa fa-plus"></i> Tambah Soal</button>
            </div>
        </div>
    </div>
<div>