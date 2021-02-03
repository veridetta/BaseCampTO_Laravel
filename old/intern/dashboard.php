<?php 
include '../config/connect.php';
function myStat($v){
    $stat="";
    switch ($v) {
        case '0':
            $stat="Idle";
            break;
        case '1':
            $stat="Terjadwal";
            break;
        case '2':
            $stat="Dimulai";
            break;
        case '3':
            $stat="Selesai";
            break;
        case '4':
            $stat="Pembahasan";
            break;
        default:
            $stat="Idle";
            break;
    }
    return $stat;
};
?>
<div class="card">
    <div class="card-header">
        <p class="h4">Event</p>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>Nama</td>
                    <td>Keterangan</td>
                    <td>Kategori</td>
                    <td>Harga</td>
                    <td>Status</td>
                    <td>Pelaksanaan</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $s=mysqli_query($con, "select * from paket_soal order by id desc");
                    while($su=mysqli_fetch_array($s)){
                        $nama=$su['nama'];
                        $keterangan=$su['keterangan'];
                        $kategori=$su['kategori'];
                        $status=$su['status'];
                        $bintang=$su['bintang'];
                        $mulai=$su['tgl_mulai'];
                        $selesai=$su['tgl_selesai'];
                ?>
                    <tr>
                        <td><?php echo $nama;?></td>
                        <td><?php echo $kategori;?></td>
                        <td><?php echo $keterangan;?></td>
                        <td><?php echo $bintang;?> <i class="text-warning fa fa-star"></i></td>
                        <td><?php echo myStat($status);?></td>
                        <td><?php echo $mulai." sampai ".$selesai;?></td>
                        <td><button class="btn button btn-primary pilih-sesi edit-soal" sesi="<?php echo $su['id'];?>" href="sesi" paket="<?php echo $su['id'];?>"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <?php
                    }
                    ?>
            </tbody>
        </table>
    </div>
<div>
<div class="modal" id="editSoal">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Paket Soal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="#" name="EditSoale" id="EditSoale">
                    <input type="hidden" name="ide" id="ide" value="">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="number" name="harga" id="harga" class="form-control required" placeholder="Harga">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="validationTooltipUsernamePrepend"> <i class="text-warning fa fa-star"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row">
                        <div class="col-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <select name="kategori" class="form-control" id="kategori">
                                        <option value="0">Idle</option>
                                        <option value="1">Terjadwal</option>
                                        <option value="2">Dimulai</option>
                                        <option value="3">Selesai</option>
                                        <option value="4">Pembahasan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input name="voucher" oninput="this.value = this.value.toUpperCase()" placeholder="Kode Voucher" class="form-control" id="voucher" maxlength="8">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="tgl_mulai">Mulai</label>
                                <div class="input-group">
                                    <input type="date" id="mulai" name="mulai" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="tgl_akhir">Berakhir</label>
                                <div class="input-group">
                                    <input type="date" id="akhir" name="akhir" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan paket soal"></textarea>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="input-group">
                            <input type="submit" class="btn button btn-success" Value="Edit">
                        </div>
                    </div> 
                    <div id="hasile"></div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
<script>
$(".edit-soal").click(function(){
    $("#editSoal").modal('show');
    $("#ide").val($(this).attr('paket'));
});
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
                window.location.replace("home.php?menu=dashboard");
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