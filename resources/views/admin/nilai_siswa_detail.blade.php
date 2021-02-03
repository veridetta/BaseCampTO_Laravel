@extends('adminlte::page')

@section('title', 'Nilai Detail')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Nilai {{$siswa->name}}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Nilai {{$siswa->name}}</li>
            </ol>
        </div>
    </div>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    .hidden{
        display:none;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@stop
@section('content')
<?php
function statistik($tipe,$id){
    $go=0;
    $sesi=DB::table('sesi_soal')->where('induk_sesi',$tipe)->groupBy('nama_sesi');
    $dat= array(
        "deta"=>array()
    );
    if($sesi->count()>0){
      foreach($sesi->get() as $sessi){
        $nila=DB::table('nilai_siswa')->where('id_siswa',$id)->where('nama_sesi',$sessi->nama_sesi);
        $ar['name']=$sessi->nama_sesi;
            $ar['data']=array();
            $dak=array(
                "x"=>"",
                "y"=>""
            );
            $n=1;
            if($nila->count()<0){
              //$go=0;
            }else{
              foreach($nila->get() as $nilai){
                $arx['x']="TO".$n;
                $arx['y']=$nilai->nilai;
                array_push($ar["data"], $arx);
                $n++;
              }
              $go=1;
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
<div class="container-fluid col-12">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <p class="card-title h4 text-info"><i class="fa fa-bar-chart"></i> Statistik</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <p class="text-center display-3 font-weight-bold text-success">TPS</p>
                        <div id="chart" ></div>
                    </div>
                    <div class="col-lg-6 col-12">

                        <p class="text-center display-3 font-weight-bold text-warning">TKA</p>
                        <div id="chart-tka"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <p class="card-title h4 text-info"><i class="fa fa-bar-chart"></i> Detail Nilai</p>
        </div>
        <div class="card-body">
            <table class="table table-hovered table-bordered col-12 table-responsive">
                <thead>
                    <tr>
                        <td class="font-weight-bold text-center" style="vertical-align:middle" rowspan="3">No</td>
                        <td class="font-weight-bold text-center" style="vertical-align:middle"  rowspan="3">Nama Paket</td>
                        <td class="font-weight-bold text-center" colspan="13">TPS</td>
                        <td class="font-weight-bold text-center" colspan="13">TKA</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-center" colspan="3">PU</td>
                        <td class="font-weight-bold text-center" colspan="3">PBM</td>
                        <td class="font-weight-bold text-center" colspan="3">PPU</td>
                        <td class="font-weight-bold text-center" colspan="3">PK</td>
                        <td class="font-weight-bold text-center" style="vertical-align:middle" rowspan="2">SC</td>
                        <td class="font-weight-bold text-center" colspan="3">MAT</td>
                        <td class="font-weight-bold text-center" colspan="3">FIS</td>
                        <td class="font-weight-bold text-center" colspan="3">KIM</td>
                        <td class="font-weight-bold text-center" colspan="3">BIO</td>
                        <td class="font-weight-bold text-center" style="vertical-align:middle" rowspan="2">SC</td>
                    </tr>
                    <tr>
                        <?php for($i=0;$i<4;$i++){
                            echo '<td class="font-weight-bold text-center">B</td>';
                            echo '<td class="font-weight-bold text-center">S</td>';
                            echo '<td class="font-weight-bold text-center">N</td>';
                        }
                        ?>
                        <?php for($i=0;$i<4;$i++){
                            echo '<td class="font-weight-bold text-center">B</td>';
                            echo '<td class="font-weight-bold text-center">S</td>';
                            echo '<td class="font-weight-bold text-center">N</td>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php $ni=1;?>
                @foreach($paket->get() as $pak)
                <?php $se=DB::table('nilai_siswa')->where('id_siswa',$id_siswa)->where('id_paket',$pak->id)->orderBy('id');
                $se2=DB::table('nilai_siswa')->where('id_siswa',$id_siswa)->where('id_paket',$pak->id)->orderBy('id');
                    $nilai_TPS=0;
                    $nilai_TKA=0;
                    //dd($pak);
                ?>
                    <tr>
                        <td>{{$ni}}</td>
                        <td>{{$pak->nama}}</td>
                        @if($se->take(4)->count()>0)
                            @foreach($se->take(4)->get() as $sesi)
                            <td class="bg-success">{{$sesi->benar}}</td>
                            <td class="bg-danger">{{$sesi->salah}}</td>
                            <td>{{$sesi->nilai}}</td>
                            <?php $nilai_TPS+=$sesi->nilai;?>
                            @endforeach
                            <td class="font-weight-bold">{{$nilai_TPS+=$sesi->nilai}}</td>
                        @else
                            <?php for($u=0;$u<4;$u++){
                                ?>
                                <td class="bg-success">0</td>
                                <td class="bg-danger">0</td>
                                <td class="font-weight-bold">0</td>
                                <?php
                            }
                            ?>
                            <td class="font-weight-bold">0</td>
                        @endif      
                        @if($se2->skip(4)->take(4)->get()->count() > 0)
                            @foreach($se2->skip(4)->take(4)->get() as $sesii)
                            <?php 
                            $nilaa=$sesii->nilai;
                            $b=$sesii->benar;
                            $c=$sesii->salah;?>
                            <td class="bg-success">{{$sesii->benar}}</td>
                            <td class="bg-danger">{{$sesii->salah}}</td>
                            <td>{{$sesii->nilai}}</td>
                            <?php $nilai_TKA+=$sesii->nilai;?>
                            @endforeach
                            <td class="font-weight-bold">{{$nilai_TKA+= $nilaa}}</td>
                        @else
                            <?php 
                            for($u=0;$u<4;$u++){
                                
                                ?>
                                <td class="bg-success">0</td>
                                <td class="bg-danger">0</td>
                                <td class="font-weight-bold">0</td>
                                <?php
                            }
                            ?>
                            <td class="font-weight-bold">0</td>
                        @endif
                    </tr>
                    <?php $ni++;?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
var options = {
        chart: {
            type: 'area',
            stacked: true,
            height: '320',
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
        series: <?php statistik('TPS',$id_siswa);?>
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
            height: '320',
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
        series: <?php statistik('TKA',$id_siswa);?>,
        xaxis: {
            categories: []
        }
    }
        var chart2 = new ApexCharts(document.querySelector("#chart-tka"), options2);
        chart2.render();
</script>
@stop