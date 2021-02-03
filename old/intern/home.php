<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bagja College Try Out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="background-color:#f8f8f8">
<?php include '../header.php';?>

<?php 
if($_SESSION['role']=='admin'){
    $nama=$_SESSION['nama'];
    $ref=$_SESSION['ref'];
    $role=$_SESSION['role'];
    $mensu = isset($_GET['menu']) ? $_GET['menu'] : "kosong";
    $sesi = isset($_GET['sesi']) ? $_GET['sesi'] : "kosong";
    $paket = isset($_GET['paket']) ? $_GET['paket'] : "kosong";
}else{
    header('location:../intern/login.php');
}?>
    <!-- Custom styles for this template -->
    <link href="../assets/css/side_bar.css" rel="stylesheet"> 
    <!--<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
    <script src="../assets/javascripts/ckeditor/ckeditor.js"></script>
    <script src="../assets/javascripts/ckeditor/config.js"></script>-->
    <script src="../assets/js/tinymce/tinymce.min.js"></script>
    <div class="d-flex" id="wrapper">
        <?php include 'sidebar.php';?>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li>
                        <?php echo $nama;?>
                    </li>
                </ul>
                </div>
                <input type="hidden" value="<?php echo $mensu;?>" id="tipemenu" sesi="<?php echo $sesi;?>" paket="<?php echo $paket;?>">
            </nav>
            
            <div class="container-fluid" id="content-disini" style="padding-top:20px;">
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <script type="text/javascript" language="javascript">
        $(document).ready(function() { /// Wait till page is loaded
            var getx=$("#tipemenu").val();
            if (getx==="kosong") {
                $('#content-disini').load('dashboard.php', function() {
                  
                });   
            }else{
                var get2=$("#tipemenu").val();
                var sesi = $("#tipemenu").attr('sesi');
                var paket = $("#tipemenu").attr('paket');
                $('#content-disini').load(get2+'.php?sesi='+sesi+'&&paket='+paket, function() {
                    /// can add another function here
                    $.getScript("../assets/js/"+get2+".js");
                });
            }
            $('.list-group-item-action').click(function(e){
                e.preventDefault();
                var href = $(this).attr('href');
                $('#content-disini').load(href+'.php', function() {
                    /// can add another function here
                    $.getScript("../assets/js/"+href+".js");
                });
            });
            
        }); //// End of Wait till page is loaded
    </script>
    <?php include '../footer.php';?>
</body>
</html>