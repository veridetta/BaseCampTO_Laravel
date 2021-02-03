<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class PaymentController extends Controller
{
    
    //
    
    public function cara(){
        $id=Auth::user()->id;
        $tagih=DB::table('tagihan')->where('id_siswa',$id)->where('expires','>=',NOW())->where('status','1');
        $tex=0;
        if($tagih->count()>0){
            $tex=1;
            $tagihan=$tagih->first();
            $timestamp = strtotime($tagihan->dibuat);
            return(view('/siswa/payment/cara',['tex'=>$tex,'tagihan'=>$tagihan,'timestamp'=>$timestamp]));
        }else{
            $gagal=1;
            return redirect('siswa');
        }
        
    }
    public function topup(){
        $id=Auth::user()->id;
        $tagih=DB::table('tagihan')->where('id_siswa',$id)->where('expires','>=','NOW()')->where('status','1')->orderBy('id','DESC');
        $tex=0;
        if($tagih->count()>0){
            $tex=1;
            $tagihan=$tagih->first();
        }else{
            $tex=0;
            $tagihan=null;
        }
        return(view('/siswa/payment/topup',['tex'=>$tex,'tagihan'=>$tagihan]));
    }

    public function generate(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $nom=$request->nominal;
        $nomiz=str_replace(".","",$nom);
        $nomi=str_replace("Rp","",$nomiz);
        $id=Auth::user()->id;
        $nama=Auth::user()->name;
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
        $clientid="xanuXo0i6auDxRKmVa5NF8EDYfmUERei";
        $clientsecret="WqASWC9i23UVQMeI";
        $bri=DB::table('briapi')->where('expires','>=',NOW());
        $accesstoken="";
        if($bri->count()>0){
            $sel = $bri->first();
            //$sel=mysqli_fetch_assoc($se);
            $token=$sel->token;
            $signature=$sel->signature;
        }else{
            $token_all=self::BRIVAgenerateToken($clientid,$clientsecret);
            $token=$token_all['token'];
            $ins=DB::table('briapi')->insert(['client_id'=>$clientid,'client_secret'=>$clientsecret,'token'=>$token,'signature'=>'','expires'=>$token_all['akhir'],'last_update'=>$token_all['mulai']]);
            //$ins=mysqli_query($con, "insert into briapi(client_id, client_secret, token, signature, expires, last_update) values('$clientid','$clientsecret','$token','','$token_all[akhir]','$token_all[mulai]')");
        }
        $tagih=DB::table('tagihan')->where('id_siswa',$id)->where('expires','>=',NOW())->where('status','1');
        if($tagih->count()>0){
            $nominal=$nomi;
            $tagihan=$tagih->first();
            $va=$tagihan->va;
            $last=$tagihan->expires;
            $ket=$tagihan->keterangan;
            if($tagihan->tagihan==$nominal){
                $amount=$tagihan->tagihan;
            }else{
                //$update=self::BrivaUpdate($clientid,$clientsecret,$token,$id,'1',"PUT");
                $briva=self::BrivaCreate($clientid,$clientsecret,$token,$nama,$id,'1','Pembelian Bintang',$nominal,'PUT');
                $amount=$briva['amount'];
                $last=$briva['expiredDate'];
                $buat=date("Y-m-d H:i:s");
                $inp=DB::table('tagihan')->where('id',$tagihan->id)->update(['tagihan'=>$amount,'dibuat'=>$buat,'expires'=>$last]);
            }
        }else{
            $briva=self::BrivaCreate($clientid,$clientsecret,$token,$nama,$id,'1','Pembelian Bintang',$nomi,'POST');
            //dd($briva);
            $dibuat =date("Y/m/d H:i:s");
            $va=$briva['custCode'];
            $amount=$briva['amount'];
            $last=$briva['expiredDate'];
            $ket=$briva['keterangan'];
            $inp=DB::table('tagihan')->insert(['id_siswa'=>$id,'va'=>$briva['custCode'],'dibuat'=>$dibuat,'tagihan'=>$briva['amount'],'expires'=>$briva['expiredDate'],'status'=>'1','keterangan'=>$briva['keterangan']]);
        }
        $return_arr['pesan']="<strong>Info!</strong> Pembayaran sebesar <strong>$nom</strong> berhasil dibuat. lihat cara pembayaran <a href='/siswa/payment/cara' class='text-danger'>disini</a>";
        $return_arr['token']=$token;
        $return_arr['va']=$va;
        $return_arr['tagihan']=$amount;
        $return_arr['expires']=$last;
        $return_arr['keterangan']=$ket;
        $return_arr['success']=true;
        $output = json_encode($return_arr);
        return response()->json($return_arr);
    }

    public function update(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $id=Auth::user()->id;
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "success" => false);
        $clientid="xanuXo0i6auDxRKmVa5NF8EDYfmUERei";
        $clientsecret="WqASWC9i23UVQMeI";
        $bri=DB::table('briapi')->where('expires','>=',NOW());
        $accesstoken="";
        if($bri->count()>0){
            $sel = $bri->first();
            //$sel=mysqli_fetch_assoc($se);
            $token=$sel->token;
            $signature=$sel->signature;
        }else{
            $token_all=self::BRIVAgenerateToken($clientid,$clientsecret);
            $token=$token_all['token'];
            //dd($token_all);
            $ins=DB::table('briapi')->insert(['client_id'=>$clientid,'client_secret'=>$clientsecret,'token'=>$token,'signature'=>'','expires'=>$token_all['akhir'],'last_update'=>$token_all['mulai']]);
            //$ins=mysqli_query($con, "insert into briapi(client_id, client_secret, token, signature, expires, last_update) values('$clientid','$clientsecret','$token','','$token_all[akhir]','$token_all[mulai]')");
        }
        $tagih=DB::table('tagihan')->where('id_siswa',$id)->where('expires','>=',NOW())->where('status','1');
        
        if($tagih->count()>0){
            $tagih2=DB::table('tagihan')->where('id_siswa',$id)->where('expires','>=',NOW())->where('status','1');
            $tagihan=$tagih2->first();
            $cek=self::BrivaCek($clientid,$clientsecret,$token,$id,'1',"GET");
            //dd($cek);
            if($cek['statusBayar']=='N'){
                $status=false;
                $pesan="<strong>Oops!</strong> Pembayaran belum berhasil, silahkan hubungi kami untuk informasi lebih lanjut 08817769047 (WA only).";
            }else{
                $up=DB::table('tagihan')->where('id',$tagihan->id)->update(['status'=>'2']);
                $mati=DB::table('tagihan')->where('id','<>',$tagihan->id)->where('status','<>','2')->update(['status'=>'0']);
                if($up){
                    $riwayat=DB::table('riwayat_bintang')->where('id_users',$id)->orderBy('id','DESC');
                    if($riwayat->count()>0){
                        $bin=$riwayat->first();
                        $nominal=$tagihan->tagihan;
                        $ha=DB::table('harga_paket')->where('nominal',$nominal);
                        $har=$ha->first();
                        $saldo=(int)$har->jumlah+(int)$bin->saldo;
                        try {
                            $in=DB::table('riwayat_bintang')->insert(['id_users'=>$id,'nominal'=>$har->jumlah,'saldo'=>$saldo]);
                        } catch (QueryException $e) {
                            dd($e);
                        }
                        if($in){
                            self::BrivaUpdate($clientid,$clientsecret,$token, $id, '1', "PUT");
                            $pesan="<strong>Sukses!</strong> Berhasil melakukan pembelian bintang sebesar Rp $nominal sebanyak ".$har->jumlah." Bintang";
                            $status=true;
                        }else{
                            
                            $pesan ="<strong>Oops!</strong>Gagal menghubungkan dengan databasee";
                            $status=false;
                        }
                    }else{
                        $nominal=$tagihan->tagihan;
                        $ha=DB::table('harga_paket')->where('nominal',$nominal);
                        $har=$ha->first();
                        $in=DB::table('riwayat_bintang')->insert(['id_users'=>$id,'nominal'=>$har->jumlah,'saldo'=>$har->jumlah]);
                        if($in){
                            self::BrivaUpdate($clientid,$clientsecret,$token, $id, '1', "PUT");
                            $pesan="<strong>Sukses!</strong> Berhasil melakukan pembelian bintang sebesar Rp $nominal sebanyak ".$har->jumlah." Bintang";
                            $status=true;
                        }else{
                            
                            $pesan ="<strong>Oops!</strong>Gagal menghubungkan dengan databasee";
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
        return response()->json($return_arr);
    }

    function BRIVAgenerateToken($client_id, $secret_id){
        date_default_timezone_set('Asia/Jakarta');
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
        date_default_timezone_set('Asia/Jakarta');
        $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=$payload";
        $signPayload = hash_hmac('sha256', $payloads, $secret, true);
        return base64_encode($signPayload);
    }
    //BUAT BRIVA BARU
    function BrivaCreate($client_id,$secret_id, $token, $nama, $id_siswa, $jenis, $keterangane,$jumlah,$verb){
        date_default_timezone_set('Asia/Jakarta');
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
            $signature=self::BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
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
        date_default_timezone_set('Asia/Jakarta');
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
            $base64sign = self::BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
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
            //dd($resultPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);
            
            $jsonPost = json_decode($resultPost, true);
            //echo "<br/> <br/>";
            //echo "Response Post : ".$resultPost;
    }
    function BrivaUpdate2($client_id,$secret_id,$token, $id_siswa, $jenis, $verb){
        date_default_timezone_set('Asia/Jakarta');
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
            $base64sign = self::BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

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
    function BrivaCek($client_id,$secret_id,$token, $id_siswa, $jenis, $verb){
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
            $base64sign = self::BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

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
}
