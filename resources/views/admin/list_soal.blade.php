@extends('adminlte::page')

@section('title', 'Dashboard List Soal')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard List Soal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">List Soal</li>
            </ol>
        </div>
    </div>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    </style>
@stop
@section('content')
<div class="container-fluid col-12">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>    
    <div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6">
                <p class="h4"> {{$dat->nama_sesi}}</p>
            </div>
            <div class="col-6 text-right">
                <a href="{{url()->previous()}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="col-12 row row-imbang" data-toggle="collapse" href="#collapse1">
            <div class="col-6">
            <?php
                $no=0;                
                foreach($qu->get() as $data){
                    $no++;
                    if($no<11){
            ?>
                <p class="h7">Nomor <?php echo $no;?> &nbsp;&nbsp;&nbsp;<button class="button btn btn-success"><span class="text-uppercase">{{$data->kunci}}</span></button>
                <button class="button btn btn-secondary edit-soal" sesi="{{$sesi}}" paket="{{$paket}}" id-nomor="{{$data->id}}" style="margin-right:3px;" href="soal"><i class="fa fa-edit"></i> Ubah</button></p>
                <?php 
                    }
                }
                ?>
            </div>
            <div class="col-6">
            <?php
                $noz=0;
                foreach($qu->get() as $data2){
                    $noz++;
                    if($noz>10){
            ?>
                <p class="h7">Nomor <?php echo $no;?> &nbsp;&nbsp;&nbsp;<button class="button btn btn-success"><span class="text-uppercase">{{$data2->kunci}}</span></button>
                <button class="button btn btn-secondary edit-soal" sesi="{{$sesi}}" paket="{{$paket}}" id-nomor="{{$data2->id}}" style="margin-right:3px;" href="soal"><i class="fa fa-edit"></i> Ubah</button></p>
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
                <button class="btn button btn-success" id="tambah-soal" href="soal" sesi="{{$sesi}}" paket="{{$paket}}"><i class="fa fa-plus"></i> Tambah Soal</button> &nbsp;<button class="btn button btn-primary" id="import-soal" href="soal" sesi="{{$sesi}}" paket="{{$paket}}"><i class="fa fa-file-import"></i> Import Soal</button> &nbsp;<button class="btn button btn-danger" id="hapus-soal" href="soal" sesi="{{$sesi}}" paket="{{$paket}}"><i class="fa fa-trash"></i> Kosongkan Soal</button>
            </div>
        </div>
    </div>
<div>
</div>
@stop
@section('js')
<script>
$(document).ready(function(){
    $('#tambah-soal').click(function(e){
        e.preventDefault();
        var sesi = $(this).attr('sesi');
        var paket = $(this).attr('paket');
        var href = $(this).attr('href');
        window.location.replace('/dashboard/input_soal/'+sesi+'/'+paket+'/0000')
    })
    $('.edit-soal').click(function(e){
        e.preventDefault();
        var sesi = $(this).attr('sesi');
        var paket = $(this).attr('paket');
        var id_nomor = $(this).attr('id-nomor');
        window.location.replace('/dashboard/input_soal/'+sesi+'/'+paket+'/'+id_nomor)
    })
    $('#import-soal').click(function(e){
        e.preventDefault();
        var sesi = $(this).attr('sesi');
        var paket = $(this).attr('paket');
        var id_nomor = $(this).attr('id-nomor');
        window.location.replace('/dashboard/import_soal/'+sesi+'/'+paket+'/0000')
    })
    $('#hapus-soal').click(function(e){
        e.preventDefault();
        var sesi = $(this).attr('sesi');
        var paket = $(this).attr('paket');
        var id_nomor = $(this).attr('id-nomor');
        window.location.replace('/dashboard/hapus_soal/'+sesi+'/'+paket)
    })
})
</script>
@stop