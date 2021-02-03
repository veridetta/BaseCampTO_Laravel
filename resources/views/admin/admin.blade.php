@extends('adminlte::page')

@section('title', 'Dashboard')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@stop
@section('content')
    <div class="container-fluid col-12">
        <div class='row'>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$dfbaru}}</h3>

                    <p>Pendaftar Minggu Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$total_terdaftar}}</h3>

                    <p>Peserta TO Terbaru</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$total_paket}}</h3>

                    <p>Jumlah Paket TO</p>
                </div>
                <div class="icon">
                    <i class="fas fa-archive"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo number_format($pemasukan_ini,2,",",".");?></h3>

                    <p>Total Pemasukan </p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <section class="col-lg-7 connectedSortable ui-sortable">
                <div class="card">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Pembelian Bintang
                        </h3>
                        <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                            <a class="nav-link active charte" href="#revenue-chart" data-toggle="tab">Area</a>
                            </li>
                            <a class="nav-link charte" href="#revenue-pie" data-toggle="tab">Pie</a>
                            </li>
                        </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                            <div class="tab-pane active" id="revenue-chart">
                                <div id='chart-bulanan'></div>
                            </div>
                            <div class="tab-pane" id="revenue-pie">
                                <div id='chart-bulanan-pie'></div>
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </section>
            <section class="col-lg-5 connectedSortable ui-sortable">
                <div class="card">
                    <div class="card-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Peserta Try Out
                        </h3>
                        <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                            <a class="nav-link active charte" href="#pendaftar-chart" data-toggle="tab">Area</a>
                            </li>
                        </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                            <div class="tab-pane active" id="pendaftar-chart">
                                <div id='chart-pendaftar'></div>
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </section>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
<?php
    function pemasukan_bulan($bintang){
        $pemasukan_bulan=DB::table('riwayat_bintang as r')->join('harga_paket as h','h.jumlah','r.nominal')->select(DB::raw('SUM(h.nominal) as nominal'), DB::raw('MONTH(r.tgl)'))->where('h.jumlah',$bintang)->groupBy(DB::raw('MONTH(r.tgl)'));
        $bulan=array(0,0,0,0,0,0,0,0);
        $ind=0;
        foreach($pemasukan_bulan->get() as $pemasukan_bulanan){
            $bulan[$ind]=$pemasukan_bulanan->nominal;        
            $ind++;
        };
        return json_encode($bulan);
    }
    function pemasukan_pie(){
        $pemasukan_bulan=DB::table('riwayat_bintang as r')->join('harga_paket as h','h.jumlah','r.nominal')->select(DB::raw('SUM(h.nominal) as nominal'),'h.jumlah')->groupBy(DB::raw('h.jumlah'));
        $bulan=array(0,0,0,0);
        $ind=0;
        foreach($pemasukan_bulan->get() as $pemasukan_bulanan){
            $bulan[$ind]=$pemasukan_bulanan->nominal;        
            $ind++;
        };
        return json_encode($bulan);
    }
?>
@section('js')
<script>
//Pembelian chart
$('.charte').click(function(){
    $('.charte').removeClass('active');
    $(this).ToggleClass('active');
});
var pie = {
    series : <?php echo pemasukan_pie();?>,
    chart : {
        width : 450,
        type : 'pie'
    },
    labels : ['100 Bintang','200 Bintang','500 Bintang','1000 Bintang'],
    responsive : [{
        breakpoint : 480,
        options : {
            chart : {
                width : 350
            },
            legend : {
                position : 'bottom'
            }
        }
    }]
};
var chart_pie = new ApexCharts(document.querySelector("#chart-bulanan-pie"), pie);
chart_pie.render();
var options = {
    series : [{
        name: '100 Bintang',
        data: <?php echo pemasukan_bulan('100');?>
    },{
        name: '200 Bintang',
        data: <?php echo pemasukan_bulan('200');?>
    },{
        name: '500 Bintang',
        data: <?php echo pemasukan_bulan('500');?>
    },{
        name: '1000 Bintang',
        data: <?php echo pemasukan_bulan('1000');?>
    }],
    chart: {
        height : 350,
        type : 'area'
    },
    dataLabels:{
        enabled:false
    },
    stroke : {
        curve : 'smooth'
    },
    xaxis : {
        categories : ['Des','Jan','Feb','Mar','Apr','Mei','Jun','Jul']
    },
};
var chart2 = new ApexCharts(document.querySelector("#chart-bulanan"), options);
chart2.render();
//chart pendaftar
var op_pendaftar = {
    series : [{
        name: 'Peserta Try Out',
        data: <?php echo json_encode($arr_pendaftar_to);?>
    }],
    chart: {
        height : 350,
        type : 'area'
    },
    dataLabels:{
        enabled:false
    },
    stroke : {
        curve : 'smooth'
    },
    xaxis : {
        categories : <?php echo json_encode($arr_to);?>
    },
};
var pendaftar = new ApexCharts(document.querySelector("#chart-pendaftar"), op_pendaftar);
pendaftar.render();
</script>
@stop