<?php
if($_POST){
    include '../../config/connect.php';
    header('Content-Type: application/json');
    session_start();
    $nom=mysqli_real_escape_string($con,$_POST['nominal']);
    $nomiz=str_replace(".","",$nom);
    $nomi=str_replace("Rp","",$nomiz);
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
        "token" => '',
        "signature" => '',
        "va" => '',
        "tagihan" => '',
        "expires" => '',
        "keterangan" => '',
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
    function BrivaCreate($client_id,$secret_id, $token, $nama, $id_siswa, $jenis, $keterangane,$jumlah,$verb){
        $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
        $secret = $secret_id;
        //generate token
        $d_user=sprintf('%04d', $id_siswa);
        $d_jenis=sprintf('%04d', $jenis);
        $institutionCode = "H9BZ27953CN";
        $brivaNo = "12666";
        $custCodex="02".$d_user.$d_jenis;
        $expiredDate=date("Y-m-d H:i:s", time()+172800);
        $datas = array('institutionCode' => $institutionCode ,
            'brivaNo' => $brivaNo,
            'custCode' => $custCodex,
            'nama' => $nama,
            'amount' => $jumlah,
            'keterangan' => $keterangane,
            'expiredDate' => $expiredDate);
            $payload = json_encode($datas, true);
            $path = "/v1/briva";
            //generate signature
            $signature=BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
            $request_headers = array(
                                "Content-Type:"."application/json",
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $signature,
                            );
            $urlPost ="https://partner.api.bri.co.id/v1/briva";
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, $verb); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);
            $jsonPost = json_decode($resultPost, true);
            //print_r($jsonPost);
            $datax['custCode']=$jsonPost['data']['custCode'];
            $datax['amount']=$jsonPost['data']['amount'];
            $datax['keterangan']=$jsonPost['data']['keterangan'];
            $datax['expiredDate']=$jsonPost['data']['expiredDate'];
            return $datax;
    }
    function BrivaUpdate($client_id,$secret_id,$token, $id_siswa, $jenis, $verb){
        $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
        $secret = $secret_id;
        $institutionCode = "H9BZ27953CN";
        $brivaNo = "12666";
        $d_user=sprintf('%04d', $id_siswa);
        $d_jenis=sprintf('%04d', $jenis);
        $custCode = "02".$d_user.$d_jenis;
        $statusBayar = "N";
        $datas = array('institutionCode' => $institutionCode ,
        'brivaNo' => $brivaNo,
        'custCode' => $custCode,
        'statusBayar'=> $statusBayar);

            $payload = json_encode($datas, true);
            $path = "/v1/briva/status";
            $verb = $verb;
            //genertae signature
            $base64sign = BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
            $request_headers = array(
                                "Content-Type:"."application/json",
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $base64sign,
                            );

            $urlPost ="https://partner.api.bri.co.id/v1/briva/status";
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "PUT"); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);

            $jsonPost = json_decode($resultPost, true);

            //echo "<br/> <br/>";
            //echo "Response Post : ".$resultPost;
    }
    define('TIMEZONE', 'Asia/Jakarta');
    date_default_timezone_set(TIMEZONE);
    $clientid="xanuXo0i6auDxRKmVa5NF8EDYfmUERei";
    $clientsecret="WqASWC9i23UVQMeI";
    $se=mysqli_query($con, "select * from briapi where expires>=NOW()");
    //print_r($se);
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
    //status tagihan
    //0 expired
    //1 aktif
    //2 dibayar
    $u=mysqli_query($con, "select * from tagihan where id_siswa='$id' and expires>=NOW() and status='1'");
    $uh=mysqli_num_rows($u);
    if($uh>0){
        $nominal=$nomi;
        $us=mysqli_fetch_assoc($u);
        $va=$us['va'];
        $last=$us['expires'];
        $ket=$us['keterangan'];
        if($us['tagihan']==$nominal){
            $amount=$us['tagihan'];
        }else{
            $update=BrivaUpdate($clientid,$clientsecret,$token,$id,'1',"PUT");
            $briva=BrivaCreate($clientid,$clientsecret,$token,$nama,$id,'1','Pembelian Bintang',$nominal,'PUT');
            $amount=$briva['amount'];
            $last=$briva['expiredDate'];
            $buat=date("Y-m-d H:i:s");
            $inp=mysqli_query($con, "update tagihan set tagihan='$amount', dibuat='$buat',expires='$last' where id='$us[id]'");
            echo mysqli_error($con);
        }
    }else{
        $briva=BrivaCreate($clientid,$clientsecret,$token,$nama,$id,'1','Pembelian Bintang',$nomi,'POST');
        $dibuat =date("Y/m/d H:i:s");
        $va=$briva['custCode'];
        $amount=$briva['amount'];
        $last=$briva['expiredDate'];
        $ket=$briva['keterangan'];
        $inp=mysqli_query($con, "insert into tagihan (id_siswa, va, dibuat, tagihan, expires, status, keterangan)values('$id','$briva[custCode]','$dibuat','$briva[amount]','$briva[expiredDate]','1','$briva[keterangan]')");
    }
        $return_arr['pesan']="<strong>Info!</strong> Pembayaran sebesar <strong>$nom</strong> berhasil dibuat. lihat cara pembayaran <a href='cara.php' class='text-danger'>disini</a>";
        $return_arr['token']=$token;
        $return_arr['va']=$va;
        $return_arr['tagihan']=$amount;
        $return_arr['expires']=$last;
        $return_arr['keterangan']=$ket;
        $return_arr['success']=true;
        $output = json_encode($return_arr);
        die($output);
}else{
    $gagal=1; 
    if($gagal>0){
        header('location:/home.php');
    }
}