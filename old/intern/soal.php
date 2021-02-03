<div class="card">
    <div class="card-header">
        <p class="h4">Menu Soal</p>
    </div>
    <div class="card-body">
        <?php
        include '../config/connect.php';
            $q=mysqli_query($con, "select * from paket_soal order by id desc");
            while($qu=mysqli_fetch_array($q)){
                $go=mysqli_query($con, "select * from soal where id_paket_soal='$qu[id]'");
        ?>
        <div class="col-12 row row-imbang" data-toggle="collapse" href="#collapse<?php echo $qu['id'];?>">
            <div class="col-6">
                <p class="h5"><?php echo $qu['nama'];?></p>
                <label class="text-muted"><?php echo $qu['keterangan'];?></label>
            </div>
            <div class="col-6 text-right">
                <p class="h6"><?php echo mysqli_num_rows($go);?> Soal</p>
                <span class="text-muted"><?php echo $qu['kategori'];?></span>
            </div>
        </div>
        <div class="col-12 panel-collapse collapse" id="collapse<?php echo $qu['id'];?>">
            <table class="table table-striped">
                <tbody>
                    <?php
                    $s=mysqli_query($con, "select * from sesi_soal where id_paket_soal='$qu[id]'  order by id asc");
                    while($su=mysqli_fetch_array($s)){
                        $jum=mysqli_query($con, "select * from soal where id_paket_soal='$qu[id]' and id_sesi_soal='$su[id]'");
                        $jumlahe=mysqli_num_rows($jum);
                ?>
                    <tr>
                        <td><?php echo $su['nama_sesi'];?></td>
                        <td><?php echo $su['durasi'];?> Menit</td>
                        <td><?php echo $jumlahe;?> Soal</td>
                        <td><button class="btn button btn-primary pilih-sesi" sesi="<?php echo $su['id'];?>" href="sesi" paket="<?php echo $su['id_paket_soal'];?>"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <hr>
            <div class="col-12 row justify-content-center h-60">
                <div class="my-auto">
                    <button class="btn button btn-primary btn-sesi" id-paket-soal="<?php echo $qu['id'];?>" data-toggle="modal" data-target="#sesiSoal"><i class="fa fa-plus"></i> Tambah Sesi Soal </button>
                </div>
            </div>
        </div>
        <?php
            }
        ?>
        <hr>
        <div class="col-12 row justify-content-center h-60">
            <div class="my-auto">
                <button class="btn button btn-success" data-toggle="modal" data-target="#buatSoal"><i class="fa fa-plus"></i> Buat Paket Soal</button>
            </div>
        </div>
    </div>
    <div class="card-footer">
    </div>
    <div class="modal" id="buatSoal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Buat Paket Soal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="" name="buatsoal" id="buatsoal">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="nama" id="nama" class="form-control required" placeholder="Nama Paket Soal">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <select name="kategori" class="form-control" id="kategori">
                                <option>UTBK</option>
                                <option>SIMAK UI</option>
                                <option>CPNS</option>
                                <option>AKPOL</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan paket soal"></textarea>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="input-group">
                            <button class="btn button btn-success">Buat</button>
                        </div>
                    </div> 
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <div id="hasile"></div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
    <div class="modal" id="sesiSoal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Sesi Soal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="" name="sesisoal" id="sesisoal">
                    <div class="form-group">
                        <div class="input-group">
                            <select name="induk_sesi" id="namasesi" class="form-control required">
                                <option>TPS</option>
                                <option>TKA</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="nama_sesi" id="namasesi" class="form-control required" placeholder="Nama Sesi Soal">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <select name="urutan" id="urutansesi" class="form-control required">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="durasi" id="durasi" class="form-control required" placeholder="Durasi dalam menit, tanpa koma">
                        </div>
                    </div>
                    <input type="hidden" id="id-paket" name="id_paket_soal" value="">
                    <div class="form-group">
                        <div class="input-group">
                            <button class="btn button btn-success">Tambah</button>
                        </div>
                    </div> 
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <div id="hasilnya"></div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
<div>
