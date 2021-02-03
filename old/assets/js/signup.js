$(document).ready(function() {
    var form = $('#signup'),
        nama = $('#nama'),
        sekolah = $('#sekolah'),
        hp = $('#hp'),
        email = $('#email'),
        password = $('#password'),
        info = $('#info'),
        submit = $("#submit");
    form.on('input', '#nama, #sekolah, #hp, #email, #password', function() {
      $(this).css('border-color', '');
      info.html('').slideUp();
    });
    submit.on('click', function(e) {
      e.preventDefault();
      if(validate()) {
        $.ajax({
          type: "POST",
          url: "/action/signup_progress.php",
          data: form.serialize(),
          dataType: "json"
        }).done(function(data) {
          if(data.success) {
            nama.val('');
            sekolah.val('');
            hp.val('');
            password.val('');
            email.val('');
            info.html(data.pesan).css('color', 'green').slideDown();
            setTimeout(function() {
                window.location.replace("login.php");
              }, 1500);
          } else {
            info.html(data.pesan).css('color', 'red').slideDown();
          }
        });
      }
    });
    function validate() {
      var valid = true;
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      
      if(!regex.test(email.val())) {
        email.css('border-color', 'red');
        valid = false;
      }
      if($.trim(nama.val()) === "") {
        nama.css('border-color', 'red');
        valid = false;
      }
      if($.trim(sekolah.val()) === "") {
        sekolah.css('border-color', 'red');
        valid = false;
      }
      if($.trim(password.val()) === "") {
        password.css('border-color', 'red');
        valid = false;
      }
      return valid;
    }
  });