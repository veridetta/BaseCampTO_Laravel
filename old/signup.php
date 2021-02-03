<!DOCTYPE html>
<html lang="en">
<head>
  <title>Signup - BaseCampTO</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body>
    <?php include 'header.php';?>
    <div class="col-12 row tfull justify-content-center" style="padding:20px;">
        <div class="col-md-5 col-11 my-auto">
            <div class="card">
                <form method="post" name="signup" id="signup" >
                    <div class="bg-primary card-header">
                        <h2 class=""><i class="fa fa-user"></i> Daftar Baru</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label id="info"></label>
                        </div>
                        <div class="form-group">
                            <label for="nama">Identitas</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-address-card"></i></span>
                                </div>
                                <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukan Nama Lengkap">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-university"></i></span>
                                </div>
                                <input type="text" name="sekolah" id="sekolah" class="form-control required" required placeholder="Masukan Asal Sekolah">
                            </div>
                        </div>
                        <div class="form-group">
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-phone"></i></span>
                                </div>
                                <input type="text" required name="hp" id="hp" class="form-control required" placeholder="Masukan Nomor Handphone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Login</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"  id="basic-addon1"><i class="fa fa-envelope"></i></span>
                                </div>
                                <input type="text" name="email" required id="email" class="form-control required" placeholder="Masukan Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-key"></i></span>
                                </div>
                                <input type="password" required name="password" id="password" class="form-control required" pattern=".{8,}" title="8 characters minimum" placeholder="Masukan Password">
                                <input type="hidden" name="ref_friend" id="ref_friend" class="form-control" pattern=".{6,}" title="8 characters minimum" placeholder="Referal Teman">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="submit" class="btn btn-primary btn-block" id="submit" name="submit" value="Daftar">
                        <p></p>
                        <p class="text-center">Sudah punya akun? <a href="login.php" class="text-primary">Login Sekarang</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>
    <script src="assets/js/signup.js"></script>
</body>