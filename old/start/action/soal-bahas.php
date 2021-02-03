<?php
if($_GET){
    include '../../config/connect.php';
    session_start();
    $nama=$_SESSION['nama'];
    $id=$_SESSION['id'];
    $ref=$_SESSION['ref'];
    $idnya=mysqli_real_escape_string($con,$_GET['idSesi']);
    $nama_sesi=mysqli_real_escape_string($con,$_GET['nama']);
    $nomor=mysqli_real_escape_string($con,$_GET['nomor']);
    $soa=mysqli_query($con, "select j.id as id_jawaban, j.id_paket, j.kunci, j.jawabanSiswa,  j.id_sesi, j.nomor_soal, s.isi, s.id, s.pembahasan, s.a, s.b, s.c, s.d, s.e from user_jawaban j inner join soal s on s.id=j.id_soal where j.id_sesi='$idnya' and j.nomor_soal='$nomor' and j.id_siswa='$id'");
    $soale=mysqli_fetch_assoc($soa);
    ?>
        <p class="h4"><?php echo $nama_sesi;?></p>
        <p>Soal Nomor <?php echo $soale['nomor_soal'];?></p>
        <div class="col-12" id="isi-soal" name="isi-soal">
            <?php echo $soale['isi'];?>
        </div>
        <div class="col-12" id="opsi-soal" name="opsi-soal">
            <fieldset class="form-group">
                <div class="row">
                    <div class="col-sm-10">
                        <table class="table">
                        <tbody>
                    <?php 
                    $aj=array("A","B","C","D","E");
                    $opsi=array($soale['a'],$soale['b'],$soale['c'],$soale['d'],$soale['e']);
                    
                    for($i=0;$i<5;$i++){
                        if($soale['kunci']==$soale['jawabanSiswa'] && $aj[$i]==$soale['jawabanSiswa']){
                            ?>
                            <tr class="bg-success">
                                <td><?php echo $aj[$i];?></td>
                                <td><?php echo $opsi[$i];?></td>
                            </tr>
                            <?php
                        }else{
                            if($aj[$i]==$soale['jawabanSiswa']){
                                ?>
                                <tr class="bg-warning">
                                    <td><?php echo $aj[$i];?></td>
                                    <td><?php echo $opsi[$i];?></td>
                                </tr>
                                <?php
                            }else if($aj[$i]==$soale['kunci']){
                                ?>
                                <tr class="bg-info">
                                    <td><?php echo $aj[$i];?></td>
                                    <td><?php echo $opsi[$i];?></td>
                                </tr>
                                <?php
                            }else{
                                ?>
                                <tr class="">
                                    <td><?php echo $aj[$i];?></td>
                                    <td><?php echo $opsi[$i];?></td>
                                </tr>
                                <?php
                            }
                        }
                        
                        ?>
                        <!--<div class="form-check">
                            <input class="form-check-input" type="radio" name="opsi" id="opsi_<?php echo $aj[$i];?>" value="<?php echo $aj[$i];?>" soal="<?php echo $soale['id_jawaban'];?>" <?php if($aj[$i]==$soale['jawabanSiswa']){echo "checked";}?>>
                            <label class="form-check-label" for="opsi_<?php echo $aj[$i];?>">
                                <?php echo $opsi[$i];?>
                            </label>
                        </div>-->
                        <?php
                        }
                        
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-12">
            <hr>
            <p class="font-weight-bold">Keterangan</p>
            <p><span class="bg-warning ">&nbsp;&nbsp;&nbsp;</span> <span class="">Jawaban Siswa</span></p>
            <p><span class="bg-info ">&nbsp;&nbsp;&nbsp;</span> <span class="">Kunci Jawaban</span></p>
            <p><span class="bg-success ">&nbsp;&nbsp;&nbsp;</span> <span class="">Menjawab  Benar</span></p>
        </div>
        <div class="col-12">
            <hr>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa" aria-hidden="true"></i>
                            Pembahasan
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse" >
                    <div class="card-body">
                        <p class=""><?php echo $soale['pembahasan'];?></p>
                        <span class="badge badge-info h4">Jawaban <?php echo $soale['kunci'];?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php
}