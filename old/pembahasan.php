<!DOCTYPE html>
<html lang="en">
<head>
  <title>Analisa Jawaban - BaseCampTO by Bagja College</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body style="background-color:#f8f8f8">
<?php include 'header.php';?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<?php 
$gagal=0;
if($_SESSION){
    $nama=$_SESSION['nama'];
    $id=$_SESSION['id'];
    $ref=$_SESSION['ref'];
    $nama_d=substr($nama, 0, 1);
    $nama_depan=$nama_d[0];
}else{
     $gagal=1;
}


if($_POST){
    $id_paket=mysqli_real_escape_string($con,$_POST['id_paket']);
    // cek status paket soal
    $tp=mysqli_query($con, "select * from paket_soal where status='4' and id='$id_paket'");
    $tps=mysqli_fetch_assoc($tp);
    $hitung=mysqli_num_rows($tp);
    if($hitung<1){
        $gagal=1;
        echo $hitung;
    }
    $ce=mysqli_query($con, "select * from nilai_siswa where id_siswa='$id' and id_paket='$id_paket'");
    $hi=mysqli_num_rows($ce);
    if($hi<1){
        $so=mysqli_query($con, "select * from sesi_soal where id_paket_soal='$id_paket'");
        $rank=0;
        while($soal=mysqli_fetch_array($so)){
            $jawa=mysqli_query($con, "select j.id_paket, j.kunci, j.id_siswa, j.jawabanSiswa, j.id_soal, s.id, s.score from user_jawaban j inner join soal s on s.id=j.id_soal where j.id_sesi='$soal[id]' and j.id_siswa='$id'");
            $jawaq=mysqli_num_rows($jawa);
            $nilai=0;
            $benar=0;
            $salah=0;
            
            while($jawaban=mysqli_fetch_array($jawa)){
                if($jawaban['jawabanSiswa']==$jawaban['kunci']){
                    $nilai+=$jawaban['score'];
                    $benar++;
                }else{
                    $salah++;
                }
            }
           $in=mysqli_query($con, "insert into nilai_siswa(id_siswa, id_paket, id_soal, benar, salah, nilai, nama_sesi) values('$id','$id_paket','$soal[id]','$benar','$salah','$nilai','$soal[nama_sesi]')");
           $rank+=$nilai;
            if($in){
                $nilai=0;
                $benar=0;
                $salah=0;
                
            }else{
                echo mysqli_error($con);
            }
        }
        $reng=mysqli_query($con, "insert into peringkat(id_siswa, id_paket, nilai) values('$id','$id_paket','$rank')");
        if($reng){
           
        }else{
            
        }
    }
    $nila=mysqli_query($con, "select n.id_siswa, n.id_paket, n.id_soal, n.benar, n.salah, n.nilai, s.id, s.induk_sesi, s.nama_sesi from nilai_siswa n inner join sesi_soal s on s.id=n.id_soal where n.id_siswa='$id' and n.id_paket='$id_paket' and s.induk_sesi='TPS'");
    $niq=mysqli_num_rows($nila);
    $mapel=array();
    $nilaie=array();
    while($nilai=mysqli_fetch_array($nila)){
        $mapel[]=$nilai['nama_sesi'];
        $nilaie[]=$nilai['nilai'];
    }
    $nila2=mysqli_query($con, "select n.id_siswa, n.id_paket, n.id_soal, n.benar, n.salah, n.nilai, s.id, s.induk_sesi, s.nama_sesi from nilai_siswa n inner join sesi_soal s on s.id=n.id_soal where n.id_siswa='$id' and n.id_paket='$id_paket' and s.induk_sesi='TKA'");
    $niq2=mysqli_num_rows($nila2);
    $mapel2=array();
    $nilaie2=array();
    while($nilai2=mysqli_fetch_array($nila2)){
        $mapel2[]=$nilai2['nama_sesi'];
        $nilaie2[]=$nilai2['nilai'];
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
        <div class="col-12 align-items-lg-center">
            <p class="text-center"><img class=" text-center rounded-circle" alt="100x100" src="https://place-hold.it/100x100/e74c3c/ecf0f1?text=<?php echo $nama_depan;?>&fontsize=55" data-holder-rendered="true"></p>
            <p class="text-center h4 text-danger text-capitalize"><?php echo $nama;?></p>
            <hr>
            <p class="text-center text-uppercase h4 text-primary">TRY OUT <?php echo $tps['nama']; ?></p>

        </div>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-danger"><i class="fa fa-bar-chart"></i> Grafik Nilai</p>
            <hr>
            <div class="col-12 row row-imbang">
                <div class="col-md-6 col-12">
                    <canvas id="bar-chart" width="800" height="600"></canvas>
                    <hr>
                    <p class="h6 text-center">Detail Score TPS<p>
                    <table class="table table-striped text-center table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sesi</th>
                                <th>B</th>
                                <th>S</th>
                                <th>SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $nod=1;
                        $detail_nilai=0;
                        $nilax=mysqli_query($con, "select n.id_siswa, n.id_paket, n.id_soal, n.benar, n.salah, n.nilai, s.id, s.induk_sesi, s.nama_sesi from nilai_siswa n inner join sesi_soal s on s.id=n.id_soal where n.id_siswa='$id' and n.id_paket='$id_paket' and s.induk_sesi='TPS'");
                        while($detail=mysqli_fetch_array($nilax)){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $nod;?></td>
                                <td class="text-left"><?php echo $detail['nama_sesi'];?></td>
                                <td class="bg-success text-center text-white"><?php echo $detail['benar'];?></td>
                                <td class="bg-warning text-center"><?php echo $detail['salah'];?></td>
                                <td class="text-center"><?php echo $detail['nilai'];?></td>
                            </tr>
                            <?php
                            $detail_nilai+=$detail['nilai'];
                            $nod++;
                        }
                            ?>
                            <tr>
                                <td colspan="4" class="font-weight-bold">Total SCORE TPS</td>
                                <td class="font-weight-bold"><?php echo $detail_nilai;?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 col-12 w-100">
                    <canvas id="bar-chart-tka" width="800" height="600"></canvas>
                    <hr>
                    <p class="h6 text-center">Detail Score TKA<p>
                    <table class="table table-striped text-center table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sesi</th>
                                <th>B</th>
                                <th>S</th>
                                <th>SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $nod2=1;
                        $detail_nilai2=0;
                        $nilax2=mysqli_query($con, "select n.id_siswa, n.id_paket, n.id_soal, n.benar, n.salah, n.nilai, s.id, s.induk_sesi, s.nama_sesi from nilai_siswa n inner join sesi_soal s on s.id=n.id_soal where n.id_siswa='$id' and n.id_paket='$id_paket' and s.induk_sesi='TKA'");
                        while($detail2=mysqli_fetch_array($nilax2)){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $nod2;?></td>
                                <td class="text-left"><?php echo $detail2['nama_sesi'];?></td>
                                <td class="bg-success text-center text-white"><?php echo $detail2['benar'];?></td>
                                <td class="bg-warning text-center"><?php echo $detail2['salah'];?></td>
                                <td class="text-center"><?php echo $detail2['nilai'];?></td>
                            </tr>
                            <?php
                            $detail_nilai2+=$detail2['nilai'];
                            $nod2++;
                        }
                            ?>
                            <tr>
                                <td colspan="4" class="font-weight-bold">Total SCORE TKA</td>
                                <td class="font-weight-bold"><?php echo $detail_nilai2;?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
        <p class="h4 text-danger"><i class="fa fa-bar-chart"></i> My Rank</p>
        <hr>
        <?php
                //$my=mysqli_query($con, "SELECT COUNT(*) as tot_ni FROM peringkat WHERE id <= '$id' order by nilai desc");
                $my=mysqli_query($con, "SELECT ps FROM ( select @rownum:=@rownum+1 ps, p.* from peringkat p, (SELECT @rownum:=0) r where id_paket='$id_paket' order by nilai desc) s WHERE id_siswa = '$id' and id_paket='$id_paket'");
                $myr=mysqli_fetch_assoc($my);
                $sco=mysqli_query($con, "select * from peringkat where id_siswa='$id' and id_paket='$id_paket'");
                $myscore=mysqli_fetch_assoc($sco);
            ?>
            <p class="h3 text-warning text-center">YOUR RANK</p>
            <p class="display-2 text-danger font-weight-bold text-center"><?php echo "#".$myr['ps'];?></p>
            <p class="h4 text-warning text-center">Total Score : <?php echo $myscore['nilai'];?></p>
            <p class="h4 text-primary"><i class="fa fa-bar-chart"></i> Top 30 Global Rank</p>
            <hr>
            <div class="col-12 justify-content-center">
            </div>
            <div class="col-12 row row-imbang">
                <?php
                    $offset=array("0","10","20");
                    $nrengking=1;
                    for($p=0;$p<3;$p++){
                        $of=$offset[$p];
                        ?>
                        <div class="col-md-4 col-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Sekolah</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
        
                            <?php
                            $re=mysqli_query($con, "select p.id_siswa, p.id_paket, p.nilai, u.nama, u.id, u.sekolah from peringkat p inner join user u on u.id=p.id_siswa where p.id_paket='$id_paket' order by p.nilai desc limit 10 offset $of");
                            while($rengking=mysqli_fetch_array($re)){ 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $nrengking;?></td>
                                    <td class="text-center"><?php echo $rengking['nama'];?></td>
                                    <td class="text-center"><?php echo $rengking['sekolah'];?></td>
                                    <td class="text-center"><?php echo $rengking['nilai'];?></td>
                                </tr>
                                <?php
                                $nrengking++;
                            }
                            ?>
                                </tbody>
                            </table>  
                        </div>
                        <?php
                    }
                ?>
            </div> 
        </div>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-success"><i class="fa fa-bar-chart"></i> Analisis Jawaban</p>
            <hr>   
            <div class="col-12 row row-imbang">
            <?php 
                $an=mysqli_query($con, "select * from sesi_soal where id_paket_soal='$id_paket'");
                while($ana=mysqli_fetch_array($an)){

                
            ?>
                <div class="col-md-4 col-12">
                    <p class="h5 text-center"><?php echo $ana['nama_sesi'];?></p>
                    <table class="table table-striped text-center table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jawaban</th>
                                <th>Kunci</th>
                                <th>% Benar</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $noa=1;
                         $jana=mysqli_query($con, "select j.jawabanSiswa, j.id_sesi, j.id_siswa, j.kunci, j.id_soal, s.id, s.menjawab_benar from user_jawaban j inner join soal s on s.id=j.id_soal where j.id_siswa='$id' and j.id_sesi='$ana[id]'");
                         while($janal=mysqli_fetch_array($jana)){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $noa;?></td>
                                <td class="text-center <?php if($janal['jawabanSiswa']==$janal['kunci']){echo 'bg-success';}else{echo 'bg-warning';};?>"><?php echo $janal['jawabanSiswa'];?></td>
                                <td class="text-center"><?php echo $janal['kunci'];?></td>
                                <td class=" text-center"><?php echo $janal['menjawab_benar'];?></td>
                            </tr>
                            <?php
                            $noa++;
                        }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <script>  
        // Bar chart
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
            labels: ["PU","PBM","PPU","PK"],
            datasets: [
                {
                label: "Score",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9"],
                data: <?php echo json_encode($nilaie); ?>
                }
            ]
            },
            options: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Tes Potensi Skolastik'
            }
            }
        });
        new Chart(document.getElementById("bar-chart-tka"), {
            type: 'bar',
            data: {
            labels: ["MAT","FIS","KIM","BIO"],
            datasets: [
                {
                label: "Score",
                backgroundColor: ["#f1c40f", "#3498db","#34495e","#2ecc71"],
                data: <?php echo json_encode($nilaie2); ?>
                }
            ]
            },
            options: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Tes Potensi Akademik'
            }
            }
        });
    </script>  
</div>