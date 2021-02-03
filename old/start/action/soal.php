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
    $soa=mysqli_query($con, "select j.id as id_jawaban, j.id_paket, j.jawabanSiswa,  j.id_sesi, j.nomor_soal, s.isi, s.id, s.a, s.b, s.c, s.d, s.e from user_jawaban j inner join soal s on s.id=j.id_soal where j.id_sesi='$idnya' and j.nomor_soal='$nomor' and j.id_siswa='$id'");
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
                    <?php 
                    $aj=array("A","B","C","D","E");
                    $opsi=array($soale['a'],$soale['b'],$soale['c'],$soale['d'],$soale['e']);
                    for($i=0;$i<5;$i++){
                        ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="opsi" id="opsi_<?php echo $aj[$i];?>" value="<?php echo $aj[$i];?>" soal="<?php echo $soale['id_jawaban'];?>" <?php if($aj[$i]==$soale['jawabanSiswa']){echo "checked";}?>>
                            <label class="form-check-label" for="opsi_<?php echo $aj[$i];?>">
                                <?php echo $opsi[$i];?>
                            </label>
                        </div>
                        <?php
                        }?>
                    </div>
                </div>
            </fieldset>
        </div>
        <script>
            $("input[name='opsi']").change(function(){
                // Do something interesting here
                var isi=$(this).val();
                var ids=$(this).attr('soal');
                $.ajax({
                    url: 'action/jawab.php',
                    type: 'POST',
                    data: {jawab: isi, 
                        idnya:ids}
                })
                .done(function(data){
                    if(data.success) {
                        $.get( "action/nav.php?id_siswa=<?php echo $id;?>&&id_sesi=<?php echo $idnya;?>&&nama=<?php echo $nama_sesi;?>", function( data ) {
                            $( "#panel-control" ).html( data );
                        });
                    } else {
                        alert('gagal');
                    }
                })
                .fail(function(){
                    alert('Maaf, submit gagal..');
                });
            });
        </script>
    <?php
}