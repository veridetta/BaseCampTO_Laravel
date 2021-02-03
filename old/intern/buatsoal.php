<?php
    include '../config/connect.php';
    
    $ssoal = isset($_GET['soal']) ? $_GET['soal'] : '';
    if($ssoal==""){
        $isi="Masukan soal";
        $pembahasan="Masukan pembahasan";
        $kunci="Masukan kunci";
        $a="Opsi A";
        $b="Opsi B";
        $c="Opsi C";
        $d="Opsi D";
        $e="Opsi E";
    }else{
        $qu=mysqli_query($con, "select * from soal where id='$_GET[soal]' LIMIT 1");
        while($query=mysqli_fetch_assoc($qu)){
            $isi=$query['isi'];
            $pembahasan=$query['pembahasan'];
            $kunci=$query['kunci'];
            $a=$query['a'];
            $b=$query['b'];
            $c=$query['c'];
            $d=$query['d'];
            $e=$query['e'];
        }
    }
?>
<div id="content" style="display:none";?>
    <div id="iso"><?php echo $isi;?></div>
    <div id="o-a"><?php echo $a;?></div>
    <div id="o-b"><?php echo $b;?></div>
    <div id="o-c"><?php echo $c;?></div>
    <div id="o-d"><?php echo $d;?></div>
    <div id="o-e"><?php echo $e;?></div>
    <div id="c-pembahasan"><?php echo $pembahasan;?></div>
</div>
<div class="card">
    <div class="card-header">
        <p class="h4"><small><a href="home.php?menu=sesi&&sesi=<?php echo $_GET['sesi'];?>&&paket=<?php echo $_GET['paket'];?>" class="text-primary"><i class="fa fa-arrow-left"></i></a></small>Tambah Soal</p>
    </div>
    <div class="card-body">
            <div class="col-12 row row-imbang" style="padding:20px">
                <div class="col-8 col-xl-8 col-lg-8">
                    <form method="post" name="artikel" id="artikel" >
                        <input name="image" type="file" id="upload" class="hidden" onchange="">
                        <input type="hidden" name="paket_id" id="paket_id" value="<?php echo $_GET['paket'];?>">
                        <input type="hidden" name="soal_id" id="soal_id" value="<?php echo $_GET['sesi'];?>">
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="inputan" class="inputan form-control" name="isi" style="min-height:50vh;">Siapa</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi-a" class="inputan form-control" name="opsi-a" style="min-height:50vh;">Opsi A</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi-b" class="inputan form-control" name="opsi-b" style="min-height:50vh;">Opsi B</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi-c" class="inputan form-control" name="opsi-c" style="min-height:50vh;">Opsi C</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi-d" class="inputan form-control" name="opsi-d" style="min-height:50vh;">Opsi D</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="opsi-e" class="inputan form-control" name="opsi-e" style="min-height:50vh;">Opsi E</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kunci" id="kunci" placeholder="Kunci Jawaban" value="<?php echo $kunci;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <textarea id="pembahasan" class="inputan form-control" name="pembahasan" style="min-height:50vh;">Pembahasan</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button class="btn button btn-success">SIMPAN</button>
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
    <div class="card-footer">
        <div class="col-12 row justify-content-center h-60">
            <div class="my-auto">
               
            </div>
        </div>
    </div>
<div>
<script>
$(document).ready(function() { /// Wait till page is loaded
            tinymce.init({
                selector: '#inputan',
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
                min_height: 500,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        editor.setContent($('#iso').html());
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
                selector: '#opsi-a',
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
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
                selector: '#opsi-b',
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
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
                selector: '#opsi-c',
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
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
                selector: '#opsi-d',
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
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
                selector: '#opsi-e',
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
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
                images_upload_url: 'upload_img.php',
                content_css : '/assets/css/tiny.css',
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
        });
        $('#artikel').submit(function(e){
                $("#hasill").html('<div class="text-center row row-imbang justify-content-center" style="min-height:100vh;">Loading ....<br></div>');
                var info = $('#hasill');
                e.preventDefault();
                $.ajax({
                url: 'action/pro_buat_soal.php',
                type: 'POST',
                    data: $(this).serialize()
                    })
                .done(function(data){
                    if(data.success) {
                        info.html(data.pesan).css('color', 'green').slideDown();
                        setTimeout(function() {
                            window.location.replace("home.php?menu=sesi&&sesi=<?php echo $_GET['sesi'];?>&&paket=<?php echo $_GET['paket'];?>");
                        }, 1500);
                    } else {
                        info.html(data.pesan).css('color', 'red').slideDown();
                    }
                })
                .fail(function(){
                    alert('Maaf, submit gagal..');
                });
            });
</script>
            