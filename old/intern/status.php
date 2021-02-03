<div class="card">
    <div class="card-header">
        <p class="h4">Daftar Siswa Mengikuti TO</p>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <td class="text-center font-weight-bold">No</td>
                <td class="text-center font-weight-bold">Nama Siswa</td>
                <td class="text-center font-weight-bold">Ket</td>
            </tr>
    <?php
    include '../config/connect.php';
    $no=1;
    $sis=mysqli_query($con, "select u.id_siswa, u.id_paket, s.nama, u.mulai, s.id from user_ujian u inner join user s on s.id=u.id_siswa where u.id_paket='4' group by u.id_siswa order by s.nama");
    while($siswa=mysqli_fetch_array($sis)){
        ?>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $siswa['nama'];?></td>
                <td><?php echo $siswa['mulai'];?></td>
            </tr>
        <?php
        $no++;
    }
    ?>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <p class="h4">Daftar Siswa Mendaftar TO</p>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <td class="text-center font-weight-bold">No</td>
                <td class="text-center font-weight-bold">Nama Siswa</td>
                <td class="text-center font-weight-bold">Ket</td>
            </tr>
    <?php
    $no2=1;
    $sis2=mysqli_query($con, "select u.id_paket, u.id_user, s.nama, s.id from peserta_paket u inner join user s on s.id=u.id_user where u.id_paket='4' order by s.nama");
    function hitungg($nor){
        $pesan="Belum";
        if($nor>0){
            if($nor>7){
                $pesan='Selesai';
            }else{
                $pesan=$nor." Sesi";
            }
        }
        return $pesan;
    }
    while($siswa2=mysqli_fetch_array($sis2)){
        $se=mysqli_query($con, "select * from user_ujian where id_siswa='$siswa2[id_user]' and id_paket='$siswa2[id_paket]'");
        $hitungse=mysqli_num_rows($se);
        ?>
            <tr>
                <td><?php echo $no2;?></td>
                <td><?php echo $siswa2['nama'];?></td>
                <td><?php echo hitungg($hitungse);?></td>
            </tr>
        <?php
        $no2++;
    }
    ?>
        </table>
    </div>
</div>