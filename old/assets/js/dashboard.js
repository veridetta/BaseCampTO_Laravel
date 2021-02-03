$('#EditSoale').submit(function(e){
    $("#hasile").html('<div class="text-center row row-imbang justify-content-center" style="min-height:100vh;">Loading ....<br></div>');
    var info = $('#hasile');
    e.preventDefault();
    $.ajax({
    url: 'action/pro_edit_paket.php',
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