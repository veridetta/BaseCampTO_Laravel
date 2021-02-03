
$('.pilih-sesi').click(function(e){
    e.preventDefault();
    var sesi = $(this).attr('sesi');
    var paket = $(this).attr('paket');
    var href = $(this).attr('href');
    $('#content-disini').load('sesi.php?sesi='+sesi+'&&paket='+paket, function() {
        /// can add another function here
        $.getScript("../assets/js/"+href+".js");
    });
});
$('#tambah-soal').click(function(e){
    e.preventDefault();
    var sesi = $(this).attr('sesi');
    //var href = $(this).attr('sesi');
    var paket = $(this).attr('paket');
    $('#content-disini').load('buatsoal.php?sesi='+sesi+'&&paket='+paket, function() {
        /// can add another function here
        //CKEDITOR.replace( 'editor1' );
    });
});
$(".btn-sesi").click(function(e){
    var vale = $(this).attr("id-paket-soal");
    $("#id-paket").val(vale);
})

$('#buatsoal').submit(function(e){
    $("#hasile").html('<div class="text-center row row-imbang justify-content-center" style="min-height:100vh;">Loading ....<br></div>');
    var info = $('#hasile');
    e.preventDefault();
    $.ajax({
    url: 'action/pro_paket_soal.php',
    type: 'POST',
        data: $(this).serialize(),
        dataType: "json"
        })
    .done(function(data){
        if(data.success) {
            info.html(data.pesan).css('color', 'green').slideDown();
            setTimeout(function() {
                window.location.replace("home.php?menu=soal");
              }, 1500);
        } else {
            info.html(data.pesan).css('color', 'red').slideDown();
        }
    })
    .fail(function(){
        alert('Maaf, submit gagal..');
    });
});

$('#sesisoal').submit(function(e){
    $("#hasilnya").html('<div class="text-center row row-imbang justify-content-center" style="min-height:100vh;">Loading ....<br></div>');
    var info = $('#hasilnya');
    e.preventDefault();
    $.ajax({
    url: 'action/pro_sesi_soal.php',
    type: 'POST',
        data: $(this).serialize(),
        dataType: "json"
        })
    .done(function(data){
        if(data.success) {
            info.html(data.pesan).css('color', 'green').slideDown();
            setTimeout(function() {
                window.location.replace("home.php?menu=soal");
              }, 1500);
        } else {
            info.html(data.pesan).css('color', 'red').slideDown();
        }
    })
    .fail(function(){
        alert('Maaf, submit gagal..');
    });
});