<?php
if($_POST){
    include '../../config/connect.php';
    header('Content-Type: application/json');
    session_start();
    //$nom=mysqli_real_escape_string($con,$_POST['nominal']);
    if($_SESSION){
        $nama=$_SESSION['nama'];
        $id=$_SESSION['id'];
        $ref=$_SESSION['ref'];
        //status riwayat
        //1 topup
        //2 pembelian
        $sa=mysqli_query($con, "select * from riwayat_bintang where id_users='$id' order by id desc limit 1");
        $sal=mysqli_fetch_assoc($sa);
        $hitung=mysqli_num_rows($sa);
        $saldo=$sal['saldo'];
        if($hitung<1){
            $saldo=0;
        }
    }else{
         $gagal=1;
    }
    $return_arr= array(
        "id" => '',
        "pesan" => '',
        "success" => false);
    date_default_timezone_set('Asia/Jakarta');
    //Get Token
    function BRIVAgenerateToken($client_id, $secret_id){
        $url ="https://partner.api.bri.co.id/oauth/client_credential/accesstoken?grant_type=client_credentials";
        $data = "client_id=$client_id&client_secret=$secret_id";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  //for updating we have to use PUT method.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($result, true);
        $expires=$json['expires_in'];
        $token = $json['access_token'];
        $mulai =date("Y/m/d H:i:s");
        $akhir=date("Y/m/d H:i:s", time()+$expires);
        $datax['token']=$token;
        $datax['expires']=$expires;
        $datax['mulai']=$mulai;
        $datax['akhir']=$akhir;
        return $datax;
    }
    /*Generate signature*/
    function BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret){
        $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=$payload";
        $signPayload = hash_hmac('sha256', $payloads, $secret, true);
        return base64_encode($signPayload);
    }
    //BUAT BRIVA BARU
    function BrivaUpdate($client_id,$secret_id,$token, $id_siswa, $jenis, $verb){
        $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
        $secret = $secret_id;
        $institutionCode = "H9BZ27953CN";
        $brivaNo = "12666";
        $d_user=sprintf('%04d', $id_siswa);
        $d_jenis=sprintf('%04d', $jenis);
        $custCode = "02".$d_user.$d_jenis;
            $payload = null;
            $path = "/v1/briva/status/".$institutionCode."/".$brivaNo."/".$custCode;
            //generate signature
            $base64sign = BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

            $request_headers = array(
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $base64sign,
                            );
            $urlPost ="https://partner.api.bri.co.id/v1/briva/status/".$institutionCode."/".$brivaNo."/".$custCode;
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET"); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);

            $jsonPost = json_decode($resultPost, true);
            $datax['statusBayar']=$jsonPost['data']['statusBayar'];
            return $datax;
    }
    $clientid="xanuXo0i6auDxRKmVa5NF8EDYfmUERei";
    $clientsecret="WqASWC9i23UVQMeI";
    $se=mysqli_query($con, "select * from briapi where expires>=NOW()");
    $seh=mysqli_num_rows($se);
    $accesstoken="";
    if($seh>0){
        $sel=mysqli_fetch_assoc($se);
        $token=$sel['token'];
        $signature=$sel['signature'];
        //$pesan = "Token Not Generate";
    }else{   
        $token_all=BRIVAgenerateToken($clientid,$clientsecret);
        $token=$token_all['token'];
        $ins=mysqli_query($con, "insert into briapi(client_id, client_secret, token, signature, expires, last_update) values('$clientid','$clientsecret','$token','','$token_all[akhir]','$token_all[mulai]')");
       // $pesan = "Token New Generate";
    }
    $u=mysqli_query($con, "select * from tagihan where id_siswa='$id' and expires>=NOW() and status='1'");
    $uh=mysqli_num_rows($u);
    if($uh>0){
        $us=mysqli_fetch_assoc($u);
        $cek=BrivaUpdate($clientid,$clientsecret,$token,$id,'1',"GET");
        if($cek['statusBayar']=='N'){
            $status=false;
            $pesan="<strong>Oops!</strong> Pembayaran belum berhasil, silahkan hubungi kami untuk informasi lebih lanjut 08817769047 (WA only).";
        }else{
            //$up=true;
            $up=mysqli_query($con, "update tagihan set status='2' where id='$us[id]'");
            $mati=mysqli_query($con, "update tagihan set status='0' where <> $us[id] and status<>2");
            if($up){
                $b=mysqli_query($con, "select * from riwayat_bintang where id_users='$id' order by id DESC limit 1");
                if(mysqli_num_rows($b)>0){
                    $bin=mysqli_fetch_assoc($b);
                    $nominal=$us['tagihan'];
                    $ha=mysqli_query($con, "select * from harga_paket where nominal='$nominal'");
                    $har=mysqli_fetch_assoc($ha);
                    $saldo=(int)$har['jumlah']+(int)$bin['saldo'];
                    $buat=date("Y-m-d H:i:s");
                    $in=mysqli_query($con, "insert into riwayat_bintang (id_users, nominal, status, saldo,tgl)values('$id','$har[jumlah]','1','$saldo','$buat')");
                    if($in){
                        $pesan="<strong>Sukses!</strong> Berhasil melakukan pembelian bintang sebesar Rp $nominal sebanyak ".$har['jumlah']." Bintang";
                        $status=true;
                    }else{
                        $pesan ="<strong>Oops!</strong>Gagal menghubungkan dengan database";
                        $status=false;
                    }
                }else{
                    $bin=mysqli_fetch_assoc($b);
                    $nominal=$us['tagihan'];
                    $ha=mysqli_query($con, "select * from harga_paket where nominal='$nominal'");
                    $har=mysqli_fetch_assoc($ha);
                    $saldo=(int)$har['jumlah']+0;
                    $buat=date("Y-m-d H:i:s");
                    $in=mysqli_query($con, "insert into riwayat_bintang (id_users, nominal, status, saldo,tgl)values('$id','$har[jumlah]','1','$saldo','$buat')");
                    if($in){
                        $pesan="<strong>Sukses!</strong> Berhasil melakukan pembelian bintang sebesar Rp $nominal sebanyak ".$har['jumlah']." Bintang";
                        $status=true;
                    }else{
                        $pesan ="<strong>Oops!</strong>Gagal menghubungkan dengan database";
                        $status=false;
                    }
                }                
            }
        }
    }else{
        $pesan="<strong>Oops!</strong>Tidak ada tagihan aktif";
        $status=false;
    }
        $return_arr['pesan']=$pesan;
        $return_arr['success']=$status;
        $output = json_encode($return_arr);
        die($output);
    //status tagihan
    //0 expired
    //1 aktif
    //2 dibayar
}else{
    echo "gada";
    $gagal=1; 
    if($gagal>0){
        header('location:/home.php');
    }
}