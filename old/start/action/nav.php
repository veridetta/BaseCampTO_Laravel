<?php
if($_GET){
    include '../../config/connect.php';
   // header('Content-Type: application/json');
        $sesi_id=mysqli_real_escape_string($con,$_GET['id_sesi']);
        $id=mysqli_real_escape_string($con,$_GET['id_siswa']);
        $nama_sesi=mysqli_real_escape_string($con,$_GET['nama']);
        $sq=mysqli_query($con, "select * from user_jawaban where id_siswa='$id' and id_sesi='$sesi_id'");
        while($q=mysqli_fetch_array($sq)){
            $nosoal=$q['nomor_soal'];
            $id_soal=$q['id_soal'];
            $jawaban=$q['jawabanSiswa'];
        ?>
            <button style="margin-left:10px; margin-top:10px;min-width:44px;" class="btn btn-nav <?php if(empty($jawaban)){echo 'btn-outline-warning';}else{echo 'btn-success';};?>" idSoal="<?php echo $id_soal;?>"><?php echo $nosoal;?></button>
            <input type="hidden" id="nosoalupdate" value="1">
        <?php
        }
        ?>
        <script>
            $(".btn-nav").click(function(){
                var sesi=<?php echo $sesi_id;?>;
                var nosoal=$(this).html();
                $("#nosoalupdate").val($(this).html());
                var nosoall=$("#nosoalupdate");
                if(nosoall.val()==1){
                    $("#sebelumnya").prop('disabled', true);
                }else{
                    $("#sebelumnya").prop('disabled', false);
                }
                if(nosoall.val()==20){
                    $("#berikutnya").html('Selesai');
                }else{
                    $("#berikutnya").html('Berikutnya');
                }
                $.get( "action/soal.php?idSesi="+sesi+"&&nomor="+nosoal+"&&nama=<?php echo $nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal);
                    });
            });
            
        </script>
        <?php
}

?>