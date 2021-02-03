<!DOCTYPE html>
<html lang="en">
<head>
  <title>Cara Pembayaran - BaseCampTO by Bagja College</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/assets/image/logo.png">
</head>
<body style="background-color:#f8f8f8">
<?php include '../header.php';?>
<style>
#message {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
}
#inner-message {
    margin: 0 auto;
}
.hilang{
    display: none;
}
#liste a{
    color:#000;
}
</style>
<?php
session_start();
$gagal = 0;
if ($_SESSION) {
    $nama = $_SESSION['nama'];
    $id = $_SESSION['id'];
    $ref = $_SESSION['ref'];
    //status riwayat
    //1 topup
    //2 pembelian
    $sa = mysqli_query($con, "select * from riwayat_bintang where id_users='$id' order by id desc limit 1");
    $sal = mysqli_fetch_assoc($sa);
    $hitung = mysqli_num_rows($sa);
    $saldo = $sal['saldo'];
    if ($hitung < 1) {
        $saldo = 0;
    }
} else {
    $gagal = 1;
}
function format_hari_tanggal($waktu){
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date( 'H:i:s', strtotime($waktu));
    
    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari, $tanggal $bulan $tahun";
}
$tag=mysqli_query($con, "select * from tagihan where id_siswa='$id' and expires>=NOW() and status='1'");
    $tex=0;
    //print_r($tag);
    if(mysqli_num_rows($tag)>0){

        $tex=1;
        $tagihan=mysqli_fetch_assoc($tag);
        $timestamp = strtotime($tagihan['dibuat']);

    }else{
        $tex=0;
        $gagal=1;
    }

if($gagal>0){
    header('location:/home.php');
}
?>
<div class="col-12">
    <div class="col-12 " style="background-color:white;padding:20px;margin-bottom:12px;margin-top:12px;">
        <div class="col-12" id="tatacara">
            <div class="card">
                <p class="h2 text-center" style="margin-top:12px;"><span class="badge badge-secondary" style="white-space:normal">Dibuat : <?php echo format_hari_tanggal(date("Y-m-d", $timestamp));?> Pukul <?php echo date("H:i", $timestamp);?> (2x24jam)</span></p>
                <p class="display-3 text-center"><span class="badge badge-warning">Rp. <?php echo number_format($tagihan['tagihan'],2,",",".");?></span></p>
                <p class="text-center">Silahkan lakukan transfer ke nomor berikut:</p>
                <p class="h2 text-center"><span class="">No Briva : 12666<?php echo $tagihan['va'];?></span></p>
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-10" style="margin-bottom:12px;">
                        <div class="card">
                            <div class="card-body">
                                <p class="h5"><span class="badge badge-info"><i class="fa fa-info"></i> <strong>Transfer dengan nominal yang sesuai</strong></span></p>
                                <small class="text-muted">Perpedaan nominal transfer akan membuat transaksi tidak dapat di proses</small>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="col-12 " style="background-color:white;padding:20px;margin-bottom:12px;margin-top:12px;">
        <p>Berikut ini cara melakukan pembayaran BRIVA (BRI Virtual Account).</p>
        <div class="alert alert-info">
            <ol id="liste">
                <li><a href=#atm-bri>ATM BRI</a></li>
                <li><a href=#atm-link>ATM Link</a></li>
                <li><a href=#atm-bank-lain>ATM Bank Lain</a></li>
                <li><a href=#ib-bri>Internet Banking BRI</a></li>
                <li><a href=#m-banking-bri>m-Banking BRI</a></li>
                <li><a href=#sms-banking-bri>SMS Banking BRI</a></li>
                <li><a href=#edc-bri>EDC BRI (mini ATM)</a></li>
                <li><a href=#teller-bri>Teller Bank BRI</a></li>
                <li><a href=#teller-bank-lain>Teller Bank Lain</a></li>
            </ol>
        </div>
        <p id="atm-bri" class="h3">Cara bayar BRIVA melalui ATM BRI</p>
        <p>Cara melakukan transfer BRIVA melalui ATM BRI:</p>
        <ol>
            <li>Masukkan kartu ATM dan PIN Anda</li>
            <li>Pilih menu <strong>Transaksi Lain</strong>, kemudian pilih menu <strong>Pembayaran</strong></li>
            <li>Setelah itu klik Menu <strong>Lainnya</strong>, lalu pilih menu <strong>BRIVA</strong></li>
            <li>Masukkan nomor rekening Virtual Account (contoh: <strong>8000812877XXXXX</strong>) dan pilih
                <strong>Benar</strong></li>
            <li>Ketika muncul konfirmasi pembayaran, silahkan periksa dan pilih <strong>Ya</strong> jika sudah benar</li>
            <li><em>Done!</em> Transaksi telah selesai dan silahkan ambil bukti pembayaran anda</li>
        </ol>
        <p id=atm-link class="h3">Pembayaran BRIVA melalui <span style="text-decoration: underline;"><strong>ATM Link</strong></span> BRI:
        </p>
        <ol>
            <li>Masukkan kartu ATM dan PIN Anda</li>
            <li>Pilih menu <strong>Menu Lain</strong></li>
            <li>Pilih menu <strong>Pembayaran / Pembelian</strong></li>
            <li>Setelah itu klik Menu <strong>Pembayaran / Pembelian </strong><strong>Lain</strong>, lalu pilih menu
                <strong>BRIVA</strong></li>
            <li>Masukkan nomor rekening Virtual Account (contoh: <strong>8000812877XXXXX</strong>) dan pilih
                <strong>Benar</strong></li>
            <li>Ketika muncul konfirmasi pembayaran, periksa dan pilih <strong>Ya</strong> jika sudah benar</li>
            <li>Pilih Sumber dana: Tabungan atau Giro, jika jenis simpanan anda bukan Giro pilih saja Tabungan</li>
            <li>Transaksi telah selesai dan silahkan ambil bukti pembayaran BRIVA</li>
        </ol>
        <p id="ib-bri" class="h3">Cara bayar BRIVA melalui Internet Banking BRI</p>
        <p>Cara melakukan pembayaran BRIVA melalui Internet Banking BRI:</p>
        <ol>
            <li>Login Internet Banking dengan <em>username</em> dan <em>password</em> Anda,</li>
            <li>Kemudian pilih Menu <strong>Pembayaran</strong></li>
            <li>Pilih menu BRIVA</li>
            <li>Masukkan nomor rekening Virtual Account (contoh: <strong>8000812877XXXXX</strong>) dan pilih
                <strong>Kirim</strong></li>
            <li>Setelah itu, masukkan <em>password</em>&nbsp;internet banking serta mToken Anda</li>
            <li>Pembayaran BRIVA selesai.<br><em>* mToken hanya berlaku untuk internet banking versi Web.</em></li>
        </ol>
        <p id="m-banking-bri" class="h3">Cara bayar BRIVA melalui Mobile Banking BRI</p>
        <p>Cara melakukan pembayaran BRIVA melalui Mobile Banking (M-Banking) BRI:</p>
        <ol>
            <li>Login BRI Mobile, lalu pilih menu <strong>Pembayaran</strong></li>
            <li>Setelah itu klik menu <strong>BRIVA</strong></li>
            <li>Masukkan nomor rekening Virtual Account (contoh: <strong>8000812877XXXXX</strong>) dan jangan lupa tuliskan
                jumlah nominal pembayaran (harus sesuai)</li>
            <li>Lalu masukkan <strong>PIN</strong> Mobile Banking dan klik <strong>Kirim</strong></li>
            <li>Transaksi selesai. Bukti pembayaran anda akan dikirimkan melalui notifikasi SMS</li>
        </ol>
        <p id="sms-banking-bri" class="h3">Cara bayar BRIVA melalui SMS Banking BRI</p>
        <p>Format untuk melakukan pembayaran BRIVA melalui SMS Banking BRI yaitu:</p>
        <blockquote class=wp-block-quote>
            <p>BAYAR&lt;spasi&gt;BRV&lt;spasi&gt;Kode BRIVA&lt;spasi&gt;Nominal Pembayaran BRIVA&lt;spasi&gt;PIN</p>
        </blockquote>
        <p>Dikirim ke nomor: <strong>3300</strong></p>
        <p>Contoh:<br>BAYAR BRV <strong>8000812877XXXXX</strong>&nbsp;170000 123456</p>
        <p id="edc-bri" class="h3">Cara bayar BRIVA melalui Mini ATM BRI (EDC BRI)</p>
        <p>Cara melakukan pembayaran BRIVA melalui Mini ATM (EDC BRI). Dimana kita bisa menemukan mini ATM BRI? Salah satunya
            yaitu melalui jasa layanan Agen BRILink. Berikut caranya:</p>
        <ol>
            <li>Pilih Menu <strong>Mini ATM</strong>, lalu pilih menu <strong>Pembayaran</strong></li>
            <li>Setelah itu pilih menu <strong>BRIVA</strong></li>
            <li><em>Swipe</em> (gesek) kartu ATM Anda ke EDC BRI</li>
            <li>Masukkan nomor rekening Virtual Account (contoh: <strong>8000812877XXXXX</strong>)</li>
            <li>Lalu masukkan <strong>PIN</strong>&nbsp;kartu ATM Anda</li>
            <li>Ketika muncul konfirmasi pembayaran, silahkan periksa dan pilih <strong>Lanjut</strong> jika sudah benar</li>
            <li>Transaksi selesai&nbsp;dan silahkan ambil bukti pembayaran anda</li>
        </ol>
        <p id="teller-bri" class="h3">Pembayaran BRIVA melalui Teller Bank BRI</p>
        <ol>
            <li>Mengisi Slip Setoran Tunai</li>
            <li>Masukan 15 digit Nomor Virtual Account (Contoh:&nbsp;<strong>8000812877XXXXX</strong>)</li>
            <li>Masukan jumlah pembayaran sesuai nominal (harus pas)</li>
            <li>Nasabah mendapat <em>copy slip</em> setoran tunai sebagai Bukti Bayar</li>
        </ol>
        <p id="atm-bank-lain" class="h3">Cara Transfer BRIVA melalui ATM Bank Lain</p>
        <ol>
            <li>Masukkan kartu ATM Bank Lain dan PIN</li>
            <li>Pilih menu <strong>Transfer Antar Bank</strong></li>
            <li>Masukan Kode Bank BRI : (kode Bank : <strong>002</strong>) + nomor Virtual Account (Contoh:
                <strong>0028000812877XXXXX</strong>)</li>
            <li>Masukan jumlah pembayaran sesuai tagihan</li>
            <li>Proses Pembayaran: pilih <strong>YA</strong></li>
            <li>Transaksi selesai&nbsp;dan silahkan ambil bukti pembayaran anda</li>
        </ol>
        <p id="teller-bank-lain" class="h3">Pembayaran BRIVA melalui Teller Bank Lain</p>
        <ol>
            <li>Nasabah melakukan pembayaran melalui Teller Bank dengan mengisi Slip Pemindah bukuan
                (<em><strong>Transfer</strong></em>)</li>
            <li>Masukan nama Bank tujuan: <strong>Bank BRI</strong></li>
            <li>Masukan nomor rekening tujuan dengan nomor Virtual Account</li>
            <li>Masukan jumlah pembayaran sesuai nominal</li>
            <li>Nasabah mendapat <em>copy slip</em> pemindahbukuan sebagai Bukti Bayar</li>
        </ol>
    </div>
</div>
<script>
/*$(document).keypress(function(event) {
    switch(event.charCode) {
        case 69:
            alert("E");
            break;
        case 65:
            alert("A");
            break;
        case 66:
            alert("B");
            break;
        case 67:
            alert("C");
            break;
        case 68:
            alert("D");
            break;
        default:
            // code block
    }
      //alert('Handler for .keypress() called. - ' + event.charCode);
});*/
</script>