@extends('adminlte::page')

@section('title', 'Dashboard Import Soal')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Import Soal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Import Soal</li>
            </ol>
        </div>
    </div>
    <link href="https://www.tiny.cloud/css/codepen.min.css" rel="sylesheet">
    <!--<script src="https://cdn.tiny.cloud/1/txwhcuduagew2m0tl0r799isplysjtwmq7p9zwir1pq9vno8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>>-->
    <script src="https://cdn.tiny.cloud/1/qagffr3pkuv17a8on1afax661irst1hbr4e6tbv888sz91jc/tinymce/4/tinymce.min.js"></script>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    .hidden{
        display:none;
    }
    </style>
@stop
@section('content')
<div class="container-fluid col-12">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <p class="h4"> Import Soal</p>
                </div>
                <div class="col-6 text-right">
                    <a href="{{url()->previous()}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12 row row-imbang" style="padding:20px">
                <div class="col-8 col-xl-8 col-lg-8">
                    <form method="post" name="import" id="import" >
                    @csrf
                        <input type="hidden" name="id_paket" value="{{$paket}}">
                        <input type="hidden" name="id_sesi" value="{{$sesi}}">
                        <div class="form-group">
                            <label for="nama_paket">Nama Paket</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_paket" disabled value="{{$data->nama_paket}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama_sesi">Nama Sesi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_sesi" disabled value="{{$data->nama_sesi}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="inputan" class="inputan form-control" name="isi" style="min-height:50vh;">Paste Table Word Disini</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button class="btn-submit btn button btn-success" data-form="artikel" data-jenis="import">SIMPAN</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12" id="hasill">
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
$(document).ready(function() {
    tinymce.init({
  selector: 'textarea',
  height: 400,
  menubar: true,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks advcode fullscreen',
    'insertdatetime media table contextmenu powerpaste'
  ],
  toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image code',
  powerpaste_allow_local_images: true,
  powerpaste_word_import: 'prompt',
  powerpaste_html_import: 'prompt',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tiny.cloud/css/codepen.min.css']
});
function mAlert(judul,pesan,clas){
    $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fas fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
};
            $('#import').submit(function(e){
                $("#hasill").html('<div class="text-center row row-imbang justify-content-center" style="min-height:9vh;">Loading ....<br></div>');
                var info = $('#hasill');
                e.preventDefault();
                $.ajax({
                url: '/dashboard/action/import_soal',
                type: 'POST',
                    data: $(this).serialize()
                    })
                .done(function(data){
                    
                        mAlert(data.judul,data.pesan,'success');
                    $("#hasil").html();
                    setTimeout(function() {
                        window.location.replace("/dashboard/list_soal/<?php echo $sesi.'/'.$paket;?>");
                    }, 1500);
                    
                })
                .fail(function(){
                    $("#hasil").html();
                    mAlert(data.judul,data.pesan,'danger');
                });
            });
})

</script>
@stop