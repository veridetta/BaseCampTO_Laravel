@extends('adminlte::page')

@section('title', 'Dashboard Input Soal')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Input Soal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Input Soal</li>
            </ol>
        </div>
    </div>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
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
    <div id="contentt" style="display:none">
        <div id="isoo">{!! $arr_soal['isi'] !!}</div>
        <div id="o-a">{!! $arr_soal['a'] !!}</div>
        <div id="o-b">{!! $arr_soal['b'] !!}</div>
        <div id="o-c">{!! $arr_soal['c'] !!}</div>
        <div id="o-d">{!! $arr_soal['d'] !!}</div>
        <div id="o-e">{!! $arr_soal['e'] !!}</div>
        <div id="c-pembahasan">{!! $arr_soal['pembahasan'] !!}</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <p class="h4"> Input Soal</p>
                </div>
                <div class="col-6 text-right">
                    <a href="{{url()->previous()}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12 row row-imbang" style="padding:20px">
                <div class="col-8 col-xl-8 col-lg-8">
                    <form method="post" name="artikel" id="artikel" >
                    @csrf
                        <input name="image" type="file" id="upload" class="hidden" onchange="">
                        <input type="hidden" name="paket_id" id="paket_id" value="{{$paket}}">
                        <input type="hidden" name="sesi" id="sesi" value="{{$sesi}}">
                        <input type="hidden" name="id_soal" id="id_soal" value="{{$idnomor}}">
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="inputan" class="inputan form-control" name="isi" style="min-height:50vh;">{{$arr_soal['isi']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi_a" class="inputan form-control" name="opsi_a" style="min-height:50vh;">Opsi A</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi_b" class="inputan form-control" name="opsi_b" style="min-height:50vh;">Opsi B</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi_c" class="inputan form-control" name="opsi_c" style="min-height:50vh;">Opsi C</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi_d" class="inputan form-control" name="opsi_d" style="min-height:50vh;">Opsi D</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi_e" class="inputan form-control" name="opsi_e" style="min-height:50vh;">Opsi E</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <select name="kunci" id="kunci" class="form-control">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="pembahasan" class="inputan form-control" name="pembahasan" style="min-height:50vh;">Pembahasan</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button class="btn-submit btn button btn-success" data-form="artikel" data-jenis="<?php if($idnomor!=='0000'){echo  'ubah';}else{echo 'tambah';}?>">SIMPAN</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4">
                    <div id="hasill">
                    
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')

<script>
$(document).ready(function(){
    $.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
        }
    });
    if("<?php echo $arr_soal['kunci'];?>"!==''){
        $('#kunci').val("{{$arr_soal['kunci']}}");   
    }
    function tambah(form,jenis){
    var tform='#'+form;
    var urle="/dashboard/action/"+jenis+"_soal";
    $.ajax({
        type: "POST",
        url: urle,
        data: $(tform).serialize(),
        dataType: "json"
    }).done(function(data) {
        if(data.success) {
            mAlert(data.judul,data.pesan,'success');
            setTimeout(function() {
                window.location.replace("{{url()->previous()}}");
            }, 1500);
        } else {
            mAlert(data.judul,data.pesan,'danger');
        }
    });
}
function mAlert(judul,pesan,clas){
    $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fas fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
};
    tinymce.init({
                selector: '#inputan',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                
                content_css : "{{asset('css/tiny.css')}}",
                min_height: 500,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#isoo').html(), {format: 'raw'});
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
            tinymce.init({
                selector: '#opsi_a',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                content_css : "{{ asset('css/tiny.css') }}",
                min_height: 200,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#o-a').html());
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
            tinymce.init({
                selector: '#opsi_b',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                content_css : "{{ asset('css/tiny.css') }}",
                min_height: 200,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#o-b').html());
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
            tinymce.init({
                selector: '#opsi_c',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                content_css : "{{ asset('css/tiny.css') }}",
                min_height: 200,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#o-c').html());
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
            tinymce.init({
                selector: '#opsi_d',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                content_css : "{{ asset('css/tiny.css') }}",
                min_height: 200,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#o-d').html());
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
            tinymce.init({
                selector: '#opsi_e',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                content_css : "{{ asset('css/tiny.css') }}",
                min_height: 200,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#o-e').html());
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
            tinymce.init({
                selector: '#pembahasan',
                images_upload_url: "{{url('/dashboard/action/upload_image')}}",
                content_css : "{{ asset('css/tiny.css') }}",
                min_height: 500,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#c-pembahasan').html());
                    });
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                paste_data_images: true,
                plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality image",
                "emoticons template paste textcolor colorpicker textpattern template autoresize"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
                toolbar2: "template | link image | print preview media | forecolor backcolor emoticons | fontsizeselect | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry tiny_mce_wiris_CAS" ,
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                image_class_list: [
                {title: 'None', value: ''},
                {title: 'Kecil', value: 'kecil'},
                {title: 'Sedang', value: 'sedang'},
                {title: 'Agak Besar', value: 'agak-besar'}
                ],
                image_advtab: true,
                file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    $('#upload').trigger('click');
                    $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                        alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                    });
                }
                },
               
                templates: [{
                title: 'Test template 1',
                content: 'Test 1'
                }, {
                title: 'Test template 2',
                content: 'Test 2'
                }]
            });
    $('.btn-submit').click(function(e){
        e.preventDefault();
        var form = $(this).attr("data-form");
        var jenis = $(this).attr("data-jenis");
        tambah(form,jenis);
    })
})
</script>

@stop