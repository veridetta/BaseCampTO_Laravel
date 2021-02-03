<!DOCTYPE html>
<html lang="en">
<head>
  <title>Member Area</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body style="background-color:#f8f8f8">
<?php include 'header.php';?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<?php 
if($_SESSION){
    $nama=$_SESSION['nama'];
    $id=$_SESSION['id'];
    $ref=$_SESSION['ref'];
    $nama_d=substr($nama, 0, 1);
    $nama_depan=$nama_d[0];
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
    header('location:/index.php');
}
function format_hari_tanggal($waktu){
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date( 'H:i:s', strtotime($waktu));
    
    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari, $tanggal $bulan $tahun";
}
function statistik($tipe,$id){
    include 'config/connect.php';
    $go=1;
    $ses=mysqli_query($con,"SELECT DISTINCT nama_sesi FROM sesi_soal where induk_sesi='$tipe'");
    $dat= array(
        "deta"=>array()
    );
    if(mysqli_num_rows($ses)>0){
        while($sesi=mysqli_fetch_array($ses)){
            $nila=mysqli_query($con, "select * from nilai_siswa where id_siswa='$id' and nama_sesi='$sesi[nama_sesi]'");
            $ar['name']=$sesi['nama_sesi'];
            $ar['data']=array();
            $dak=array(
                "x"=>"",
                "y"=>""
            );
            $n=1;
            if(mysqli_num_rows($nila)<1){
                $go=0;
            }else{
                while($nilai=mysqli_fetch_array($nila)){
                    //print_r($nilai);
                    $arx['x']="TO".$n;
                    $arx['y']=$nilai['nilai'];
                    array_push($ar["data"], $arx);
                    $n++;
                }
                $go+=1;
            }
            array_push($dat["deta"], $ar);
        }
    }else{
        $go=0;
    }
    if($go>0){
        $dat['data']=$ar;
        echo json_encode($dat['deta']);   
    }else{
        echo "[]";
    }
}
?>
    <div class="col-12 row row-imbang primary" style="margin-top:60px;">
        <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
            <div class="col-12">
                <p class="h4 text-danger"><i class="fa fa-user"></i> Profile</p>
                <hr>   
                <p class="text-center"><img class=" text-center rounded-circle" alt="100x100" src="https://place-hold.it/100x100/e74c3c/ecf0f1?text=<?php echo $nama_depan;?>&fontsize=55" data-holder-rendered="true"></p>
                <p class="text-center h4 text-danger text-capitalize"><?php echo $nama;?></p>
                <hr>
                <div class="text-center">
                    <span class="h4 text-right"><i class="text-warning fa fa-star"></i> <?php echo $saldo;?></span>
                    <a class="btn btn-outline-warning h4" href="trans/purchase.php">Tambah</a>
                </div>
            </div>            
            <hr>
        </div>
        <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
            <div class="col-12">
                <p class="h4 text-success"><i class="fa fa-bar-chart"></i> Statistik </p>
                <hr>
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <p class="text-center display-3 font-weight-bold text-success">TPS</p>
                        <div id="chart"></div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <p class="text-center display-3 font-weight-bold text-warning">TKA</p>
                        <div id="chart-tka"></div>
                    </div>
                </div>
            </div>            
            <hr>
        </div>
        <div class="col-12" style="background-color:white;padding:20px;">
            <p class="h4 text-warning"><i class="fa fa-calendar"></i> Event Terbaru</p>
            <hr>
            <div class="col-12 row">
            <form method="post"  id="form_buy" name="form_buy">
                <input type="hidden" name="id_paket_soal" id="id_paket_soal" value="">
                <input type="hidden" name="id_user" id="id_user" value="<?php echo $id;?>">
                <input type="hidden" name="voucher" id="voucher" value="">
                <input type="submit" name="sb_buy" id="sb_buy" style="display:none;" value="ada">
            </form>
            <?php 
                //status user :
                //0 === tidak terdaftar
                //1 === belum mengerjakan
                //2 === sedang mengerjakan
                //3 === sudah
                //status soal
                //0 === idle
                //1 === siap
                //2 === mulai
                //3 === stop/masa lalu
                //4 === sesi pembahasan
                $qu=mysqli_query($con, "select * from paket_soal where status <3 order by status desc");
                $hit=mysqli_num_rows($qu);
                if($hit<1){
                    ?>
                    <div class="col-12 row" style="min-height:30px;">
                        <p class="h4 text-muted">Belum ada event terbaru</p>
                    </div>
                    <?php
                }
                while($query=mysqli_fetch_array($qu)){
                    $nama=$query['nama'];
                    $kategori=$query['kategori'];
                    $keterangan=$query['keterangan'];
                    $status=$query['status'];
                    $id_paket=$query['id'];
                    $tgl_mulai=format_hari_tanggal(date("Y-m-d",strtotime($query['tgl_mulai'])));
                    $tgl_selesai=format_hari_tanggal(date("Y-m-d",strtotime($query['tgl_selesai'])));
                    $us=mysqli_query($con, "select * from peserta_paket where id_paket='$id_paket' and id_user='$id' LIMIT 1");
                    $userx=mysqli_fetch_assoc($us);
                    $us_status=$userx['status'];
                    if($us_status===""){
                        $us_status=0;
                    }

            ?>
                <div class="col-md-3 col-11">
                    <div class="card" style="margin-bottom:12px;">
                        <div class="card-header">
                            <p class="card-title h4 text-center"><?php echo $nama;?></p>
                        </div>
                        <div class="card-body">
                            <p class="display-1 text-center"><i class="fa fa-file-o"></i></p>
                            <p class="text-center font-weight-bold" style="margin-bottom:0px;"><?php echo $kategori;?></p>
                            <p class="text-center" style="margin-bottom:0px;"><?php echo $keterangan;?></p>
                            <p class="text-center text-muted" style="margin-bottom:0px;"><?php echo $tgl_mulai." - ".$tgl_selesai;?></p>
                        </div>
                        <div class="card-footer">
                            <?php 
                            if($status==2 and $us_status==1){
                            ?>
                            <form method="post" action="start/launch.php">
                                <button class="btn button btn-success btn-block">Mulai Mengerjakan</button>
                            </form>
                            <?php    
                            }else if($us_status==0){ 
                                ?>
                                <button class="kluk btn button btn-primary btn-block" id="btn-buy<?php echo $id_paket;?>" paket="<?php echo $id_paket;?>">Daftar (<?php echo $query  ['bintang'];?> <i class="text-warning fa fa-star"></i>)</button>
                                <?php
                            }else {
                                ?>
                                <button class="btn button btn-disabled btn-block">Sudah Terdaftar</button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            };
            ?>
            </div>
        </div>
        <div class="col-12" style="background-color:white;padding:20px;margin-top:12px;">
            <p class="h4 text-secondary"><i class="fa fa-history"></i> Event Terakhir</p>
            <hr>
            <div class="col-12 row">
            <?php 
                //status user :
                //0 === tidak terdaftar
                //1 === belum mengerjakan
                //2 === sedang mengerjakan
                //3 === sudah
                //status soal
                //0 === idle
                //1 === siap
                //2 === mulai
                //3 === stop/masa lalu
                //4 === sesi pembahasan
                $qu2=mysqli_query($con, "select * from paket_soal where status >2 order by id desc");
                while($query2=mysqli_fetch_array($qu2)){
                    $nama2=$query2['nama'];
                    $kategori2=$query2['kategori'];
                    $keterangan2=$query2['keterangan'];
                    $status2=$query2['status'];
                    $id_paket2=$query2['id'];
                    $tgl_mulai2=format_hari_tanggal(date("Y-m-d",strtotime($query2['tgl_mulai'])));
                    $tgl_selesai2=format_hari_tanggal(date("Y-m-d",strtotime($query2['tgl_selesai'])));
                    $us2=mysqli_query($con, "select * from peserta_paket where id_paket='$id_paket2' and id_user='$id' LIMIT 1");
                    $userx2=mysqli_fetch_assoc($us2);
                    $us_status2=$userx2['status'];
                    if($us_status2===""){
                        $us_status2=0;
                    }

            ?>
                <div class="col-md-3 col-11 ">
                    <div class="card" style="margin-bottom:12px;">
                        <div class="card-header">
                            <p class="card-title h4 text-center"><?php echo $nama2;?></p>
                        </div>
                        <div class="card-body">
                            <p class="display-1 text-center"><i class="fa fa-file-o"></i></p>
                            <p class="text-center font-weight-bold" style="margin-bottom:0px;"><?php echo $kategori2;?></p>
                            <p class="text-center" style="margin-bottom:0px;"><?php echo $keterangan2;?></p>
                            <p class="text-center  text-muted" style="margin-bottom:0px;"><?php echo $tgl_mulai2." sampai ".$tgl_selesai2;?></p>
                        </div>
                        <div class="card-footer">
                            <?php 
                            if($status2==4 and $us_status2>0){
                            ?>
                            <form method="post" action="pembahasan.php">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <input type="hidden" name="id_paket" value="<?php echo $id_paket2;?>">
                                <button class="btn button btn-primary btn-block">Buat Analisis Saya</button>
                            </form>
                            <form method="post" action="start/bahas-launch.php" style="margin-top:12px;">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <input type="hidden" name="id_paket" value="<?php echo $id_paket2;?>">
                                <button class="btn button btn-success btn-block">Lihat Pembahasan</button>
                            </form>
                            <?php    
                            }else if($status2==3 and $us_status2>0){ 
                                ?>
                                <button class="btn button btn-disabled btn-block">Menunggu Pembahasan</button>
                                <?php
                            }else {
                                ?>
                                <button class="btn button btn-disabled btn-block">Tidak Terdaftar</button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            };
            ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>
    <!-- The Modal -->
    <div class="modal" id="buy-modal">
    <div class="modal-dialog">
        <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title" id="">Konfirmasi Pembelian</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body justify-content-center">
            <div class='form-check'>
                <div class="input-group">
                    <input id="cek" name="cek" type="checkbox" class="form-check-input" placeholder="xx">
                    <label for="cek" class="form-check-label">Menggunakan Voucher</label>
                </div>
            </div>
            <div class='form-group'>
                <div class="input-group">
                    <input id="kode" maxlength="8" name="kode" type="text" class="form-control-lg" placeholder="Voucher">
                </div>
            </div>            
            <span class="text-muted">* Ceklis voucher jika mempunyai kode voucher, abaikan jika langsung membeli dengan bintang.</span>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="beli-jadi" class="btn btn-success" data-dismiss="modal">Konfirmasi</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
    </div>
   <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="judul">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <span id="pesan"></span>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<div id="demo"></div>
    <script>
    $(".kluk").click(function(){
        var vale = $(this).attr("paket");
        $("#id_paket_soal").val(vale);
        $("#buy-modal").modal('show');
    });
    $("#beli-jadi").click(function(e){
        var vou = $("#kode").val();
        $("#voucher").val(vou);
        $("#sb_buy").trigger("click");
    })
    $('#form_buy').submit(function(e){
        e.preventDefault();
        $.ajax({
        url: 'action/buy.php',
        type: 'POST',
            data: $(this).serialize(),
            dataType: "json"
            })
        .done(function(data){
            if(data.success) {
                $('#myModal').modal('show'); 
                setTimeout(function() {
                    window.location.replace("home.php");
                }, 1500);
            } else {
                //info.html(data.pesan).css('color', 'red').slideDown();
                $('#myModal').modal('show'); 
            }
            $("#judul").html(data.judul);
            $("#pesan").html(data.pesan);
        })
        .fail(function(){
            alert('Maaf, submit gagal..');
        });
    });
    var options = {
        chart: {
            type: 'area',
            stacked: true,
            foreColor: "#2ecc71"
        },
        events: {
            selection: function (chart, e) {
              console.log(new Date(e.xaxis.min))
            }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        fill: {
          type: 'gradient',
          gradient: {
            enabled: true,
            opacityFrom: 0.55,
            opacityTo: 0
          }
        },
        markers: {
            size: 5,
            colors: ["#000524"],
            strokeColor: "#00BAEC",
            strokeWidth: 3
        },
        tooltip: {
            theme: "dark"
        },
        series: <?php statistik('TPS',$id);?>
        ,
        xaxis: {
            categories: []
        }
    }
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
        
    var options2 = {
        chart: {
            type: 'area',
            stacked: true,
            foreColor: "#f39c12"
        },
        events: {
            selection: function (chart, e) {
              console.log(new Date(e.xaxis.min))
            }
        },
        dataLabels: {
          enabled: true
        },
        noData: {
            text: 'Belum ada data'
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        fill: {
          type: 'gradient',
          gradient: {
            enabled: true,
            opacityFrom: 0.55,
            opacityTo: 0
          }
        },
        markers: {
            size: 5,
            colors: ["#000524"],
            strokeColor: "#00BAEC",
            strokeWidth: 3
        },
        tooltip: {
            theme: "dark"
        },
        series: <?php statistik('TKA',$id);?>,
        xaxis: {
            categories: []
        }
    }
        var chart2 = new ApexCharts(document.querySelector("#chart-tka"), options2);
        chart2.render();
    </script>
</body>
</html>