<?php include '../config/connect.php';
$skrg=date("Y-m-d");
$da=mysqli_query($con, "select r.id, r.id_users, r.status, r.nominal, r.saldo, r.tgl, u.nama, u.id, h.nominal as uang, h.jumlah from riwayat_bintang r inner join user u on u.id=r.id_users inner join harga_paket h on h.jumlah=r.nominal where r.status='1' and r.tgl >= CURDATE() && r.tgl < (CURDATE() + INTERVAL 1 DAY) order by r.id desc");
?>
<div class="card">
    <div class="card-header">
        <p class="h4">Riwayat Pembayaran</p>
    </div>
    <div class="card-body">
        <div class="col-12">
            <p class="h4 font-weight-bold text-info">Pemasukan Hari ini </p>
            <table class="table table-striped table-bordered">
                <tr class="bg-success">
                    <td class="font-weight-bold">No</td>
                    <td class="font-weight-bold">Nama Siswa</td>
                    <td class="font-weight-bold">Bintang</td>
                    <td class="font-weight-bold">Nominal</td>
                </tr>
                <?php
                $no=1;
                $t_bintang=0;
                $t_uang=0;
                while($data=mysqli_fetch_array($da)){
                    ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $data['nama'];?></td>
                        <td><?php echo $data['nominal'];?></td>
                        <td><?php echo number_format($data['uang'],2,",",".");?></td>
                    </tr>
                    <?php
                $no++;
                $t_bintang+=$data['nominal'];
                $t_uang+=$data['uang'];
                }
                ?>
                <tr class="bg-warning">
                    <td class="font-weight-bold"><?php echo $no-1;?></td>
                    <td  class="font-weight-bold">Total </td>
                    <td class="font-weight-bold"><?php echo $t_bintang;?></td>
                    <td class="font-weight-bold">Rp. <?php echo number_format($t_uang,2,",",".");?></td>
                </tr>
            </table>  
            <p class="h4 font-weight-bold text-info">Pemasukan Kemarin </p>
            <table class="table table-striped table-bordered">
                <tr class="bg-success">
                    <td class="font-weight-bold">No</td>
                    <td class="font-weight-bold">Nama Siswa</td>
                    <td class="font-weight-bold">Bintang</td>
                    <td class="font-weight-bold">Nominal</td>
                </tr>
                <?php
                $nok=1;
                $t_bintangk=0;
                $t_uangk=0;
                $dak=mysqli_query($con, "select r.id, r.id_users, r.status, r.nominal, r.saldo, r.tgl, u.nama, u.id, h.nominal as uang, h.jumlah from riwayat_bintang r inner join user u on u.id=r.id_users inner join harga_paket h on h.jumlah=r.nominal where r.status='1' and r.tgl >= (CURDATE() - INTERVAL 1 DAY) && r.tgl < CURDATE() order by r.id desc");
                while($datak=mysqli_fetch_array($dak)){
                    ?>
                    <tr>
                        <td><?php echo $nok;?></td>
                        <td><?php echo $datak['nama'];?></td>
                        <td><?php echo $datak['nominal'];?></td>
                        <td><?php echo number_format($datak['uang'],2,",",".");?></td>
                    </tr>
                    <?php
                $nok++;
                $t_bintangk+=$datak['nominal'];
                $t_uangk+=$datak['uang'];
                }
                ?>
                <tr class="bg-warning">
                    <td class="font-weight-bold"><?php echo $nok-1;?></td>
                    <td  class="font-weight-bold">Total </td>
                    <td class="font-weight-bold"><?php echo $t_bintangk;?></td>
                    <td class="font-weight-bold">Rp. <?php echo number_format($t_uangk,2,",",".");?></td>
                </tr>
            </table>       
        </div>
    </div>
<div>
