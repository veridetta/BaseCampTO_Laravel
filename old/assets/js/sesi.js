$('#tambah-soal').click(function(e){
    e.preventDefault();
    var sesi = $(this).attr('sesi');
    var paket = $(this).attr('paket');
    var href = $(this).attr('href');
    $('#content-disini').load('buatsoal.php?sesi='+sesi+'&&paket='+paket, function() {
        /// can add another function here
        $.getScript("../assets/js/"+href+".js");
    });
});
$('.edit-soal').click(function(e){
    e.preventDefault();
    var sesi = $(this).attr('sesi');
    var paket = $(this).attr('paket');
    var href = $(this).attr('href');
    var id_soal = $(this).attr('id-nomor');
    $('#content-disini').load('editsoal.php?sesi='+sesi+'&&paket='+paket+'&&soal='+id_soal, function() {
        /// can add another function here
        $.getScript("../assets/js/"+href+".js");
    });
});