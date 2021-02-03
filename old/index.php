<!DOCTYPE html>
<html lang="en">
<head>
  <title>BaseCampTO</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body>
<?php include 'header.php';
  if($_SESSION){
    header('location:/home.php');}
  ?>
<div class="jumbotron bg-white" style="min-height:100vh;">
  <div class="row align-items-center justify-content-center h-100">
    <div class="col-md-6 order-2 mt-3 mt-md-0 order-md-first">
      <p class="h2 text-danger">Try Out UTBK 2020-2021 hanya di BaseCampTO!</p>
      <p class="h5">Dengan sistem penilain dan analisa jawaban yang lengkap dan relevan berdasarkan UTBK 2021</p>
      <p class="h3 "><span class="badge badge-primary"><i class="fa fa-file text-white"></i> Road To Warrior!</span></p>
      <p class="h2"><i class="fa fa-calendar text-danger"></i> <span class="badge badge-warning"></i> 16 - 18 Januari 2021</span></p>
      <hr>
      <a type="button" href="signup.php" class="btn btn-info"><p class="h4">Daftar</p></a> <a type="button" href="login.php" class="btn btn-danger"><p class="h4">Masuk</p></a>
      <hr>
      <p class="h5 text-primary">#AyoIkutTOSekarang #LebihCepatLebihBaik</p>
    </div>
    <div class="col-md-6 col-8 h-100 order-first order-md-2 d-flex flex-column justify-content-end">
      <img class="img img-fluid img-block" src="assets/image/cbt.png"/>
    </div>
  </div>
   
</div>
  
<div class="container">
  <div class="col-12" style="min-height:100vh;margin-bottom:12px;" id="tentang" name="tentang">
    <p class="display-4 text-center text-info">Mengapa BC TryOut?</p>
    <hr>
    <div class="row align-items-center justify-content-center h-100">
      <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
        <div class="card border-info">
          <div class="card-body">
            <p class="h5 text-center"><span class="badge badge-info">Our System</span></p>
            <img class="img img-fluid" src="assets/image/system.png"/>
            <p class="">Try Out Sudah menggunakan sistem UTBK 2020</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
        <div class="card border-warning">
          <div class="card-body">
            <p class="h5 text-center"><span class="badge badge-warning">Our Ratings</span></p>
            <img class="img img-fluid" src="assets/image/ratings.png"/>
            <p class="">Soal sudah menggunakan sistem penilaian butir sehingga membuat soal yang mudah mendapatkan nilai kecil begitu sebaliknya</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
        <div class="card border-danger">
          <div class="card-body">
            <p class="h5 text-center"><span class="badge badge-danger">Our Analytics</span></p>
            <img class="img img-fluid" src="assets/image/graph.png"/>
            <p class="">BC TO menyediakan analisa jawaban masing-masing siswa secara lengkap, sehingga siswa dapat melakukan evaluasi terhadap jawabannya.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
        <div class="card border-secondary">
          <div class="card-body">
            <p class="h5 text-center"><span class="badge badge-secondary">Our Session</span></p>
            <img class="img img-fluid" src="assets/image/conversation.png"/>
            <p class="">BC TO menyediakan pembahasan yang mudah dipahami.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
        <div class="card border-success">
          <div class="card-body">
            <p class="h5 text-center"><span class="badge badge-success">Our Payment</span></p>
            <img class="img img-fluid" src="assets/image/bill.png"/>
            <p class="">Pembayaran yang mudah</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- 
  <div class="col-12 " style="min-height:100vh" id="produk" name="produk">
    <p class="display-4 text-center">Produk Kami</p>
    <hr>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="col-12 row justify-content-center">
          <div class="col-8">
            <ul class="nav nav-pills nav-justified nav-tabs">
              <li data-target="#carouselExampleIndicators" class="text-center active" data-slide-to="0" ><a href="#">Try Out UTBK</a></li>
              <li data-target="#carouselExampleIndicators" class="text-center" data-slide-to="1"><a href="#">Try Out Simak UI</a></li>
              <li data-target="#carouselExampleIndicators" class="text-center" data-slide-to="2"><a href="#">Lainnya</a></li>
            </ul>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <div class="card" style="padding:30px 20px 20px 30px">
                    <div class="card-body">
                    <p class="h1 text-primary">Tryout UTBK</p>
                    Platform pendidikan terdepan bagi pelajar Indonesia dengan sistem teknologi terkini untuk bantu kamu gapai PTN impian.
                    </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="card" style="padding:30px 20px 20px 30px">
                    <div class="card-body">
                    <p class="h1 text-primary">Tryout SIMAK UI</p>
                    Platform pendidikan terdepan bagi pelajar Indonesia dengan sistem teknologi terkini untuk bantu kamu gapai PTN impian.
                    </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="card" style="padding:30px 20px 20px 30px">
                    <div class="card-body">
                    <p class="h1 text-primary">Tryout Lainnya</p>
                    Platform pendidikan terdepan bagi pelajar Indonesia dengan sistem teknologi terkini untuk bantu kamu gapai PTN impian.
                    </div>
                </div>
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
    </div>
  </div>
  END-->
</div>
<?php include 'footer.php';?>
<script>
  $(document).ready( function() {
      $('#carouselExampleIndicators').carousel({
        interval:   4000
    });
    
    var clickEvent = false;
    $('#carouselExampleIndicators').on('click', '.nav a', function() {
        clickEvent = true;
        $('.nav li').removeClass('active');
        $(this).parent().addClass('active');		
    }).on('slid.bs.carousel', function(e) {
      if(!clickEvent) {
        var count = $('.nav').children().length -1;
        var current = $('.nav li.active');
        current.removeClass('active').next().addClass('active');
        var id = parseInt(current.data('slide-to'));
        if(count == id) {
          $('.nav li').first().addClass('active');	
        }
      }
      clickEvent = false;
    });
  });
</script>
</body>
</html>