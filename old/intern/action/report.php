<?php
include '../../config/connect.php';
header('Content-Type: application/json');
session_start();
/* Generate Token */
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

function BrivaUpdate($token) {
    $client_id = 'xanuXo0i6auDxRKmVa5NF8EDYfmUERei';
    $secret_id = 'WqASWC9i23UVQMeI';
    $timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
    $secret = $secret_id;
    $institutionCode = "H9BZ27953CN";
    $brivaNo = "12666";
    $startDate = "20201224";
    $endDate = "20201224";
    $payload = null;
    $path = "/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;
    $verb = "GET";
    $base64sign = BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
    $request_headers = array(
        "Authorization:Bearer " . $token,
        "BRI-Timestamp:" . $timestamp,
        "BRI-Signature:" . $base64sign,
    );

    $urlPost ="https://partner.api.bri.co.id/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;
    $chPost = curl_init();
    curl_setopt($chPost, CURLOPT_URL, $urlPost);
    curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET"); 
    curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
    curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);

    $resultPost = curl_exec($chPost);
    $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
    curl_close($chPost);
    echo "Response Post : ".$resultPost;
    return json_decode($resultPost, true);
}
$clientid="xanuXo0i6auDxRKmVa5NF8EDYfmUERei";
$clientsecret="WqASWC9i23UVQMeI";
$se=mysqli_query($con, "select * from briapi where expires>=NOW()");
echo mysqli_error($con);
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
BrivaUpdate($token);