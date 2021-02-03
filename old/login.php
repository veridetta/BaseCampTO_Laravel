<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login - BaseCampTO by Bagja Colelge</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body>
    <?php include 'header.php';?>
    <div class="col-12 row tfull justify-content-center" style="padding:20px;">
        <div class="col-md-5 col-11 my-auto">
            <div class="card">
                <form method="post" name="loginform" id="loginform">
                    <div class="bg-primary card-header">
                        <h2 class=""><i class="fa fa-user"></i> Login</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label id="info"></label>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                                </div>
                                <input type="text" name="email" id="email" class="form-control required" placeholder="Masukan Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-key"></i></span>
                                </div>
                                <input type="password" name="password" id="password" class="form-control required" placeholder="Masukan Password">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="submit" class="btn btn-primary btn-block" id="submit" name="submit" value="Login">
                        <p></p>
                        <p class="text-center">Belum punya akun? <a href="/signup.php" class="text-primary">Daftar Sekarang</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>
    <script src="assets/js/login.js"></script>
</body>