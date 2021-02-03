<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Goutte\Client;
//include_once 'vendor/autoload.php';
use simplehtmldom\HtmlWeb;
//use App\Functions\simple_html;

class AjaxController extends Controller
{
    //
    public function daftar_TO(Request $request){
        $id=Auth::user()->id;
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $user=DB::table('riwayat_bintang')->where('id_users',$id)->orderBy('id','desc')->first();
        if(Str::length($request->voucher)>0){
            $paket=DB::table('paket_soal')->where('id',$request->id_paket_soal)->where('voucher',$request->voucher);
            if($paket->count()>0){
                $sisa=$user->saldo;
                $riwayat=DB::table('riwayat_bintang')->insert([
                    'id_users'   => $id,
                    'nominal'   => $paket->bintang,
                    'status'    => '2',
                    'saldo'     => $sisa
                ]);
                if($riwayat){
                    $insert=DB::table('peserta_paket')->insert([
                        'id_user'   => $id,
                        'id_paket'  => $request->id_paket_soal,
                        'status'    => '1'
                    ]);
                    if($insert){
                        $pesan = "<strong>Sukses</strong> Pembayaran Berhasil, kamu akan segera di alihkan ";
                        $judul="Pembelian berhasil";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=true;
                        $output = json_encode($return_arr);
                        die($output);
                    }else{
                        $pesan = "<strong>Gagal</strong> Error tidak diketahui ";
                        $judul="Pembelian gagal";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=false;
                        $output = json_encode($return_arr);
                        die($output);
                    }
                }else{
                    $pesan = "<strong>Error</strong> Error tidak diketahui ";
                    $judul="Pembelian gagal";
                    $return_arr['pesan']=$pesan;
                    $return_arr['judul']=$judul;
                    $return_arr['success']=false;
                    $output = json_encode($return_arr);
                    die($output);
                }
            }else{
                $pesan = "<strong>Error</strong> Kode Voucher yang dimasukkan tidak berlaku, silahkan periksa kembali kode voucher anda, atau silahkan lakukan pembelian tanpa menggunakan voucher";
                $judul="Pembelian gagal";
                $return_arr['pesan']=$pesan;
                $return_arr['judul']=$judul;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);
            }
        }else{
            $paket=DB::table('paket_soal')->where('id',$request->id_paket_soal)->first();
            if($user->saldo>$paket->bintang){
                $sisa=$user->saldo-$paket->bintang;
                $riwayat=DB::table('riwayat_bintang')->insert([
                    'id_users'   => $id,
                    'nominal'   => $paket->bintang,
                    'status'    => '2',
                    'saldo'     => $sisa
                ]);
                if($riwayat){
                    $insert=DB::table('peserta_paket')->insert([
                        'id_user'   => $id,
                        'paket_soal_id'  => $request->id_paket_soal,
                        'status'    => '1'
                    ]);
                    if($insert){
                        $pesan = "<strong>Sukses</strong> Pembayaran Berhasil, kamu akan segera di alihkan ";
                        $judul="Pembelian berhasil";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=true;
                        $output = json_encode($return_arr);
                        die($output);
                    }else{
                        $pesan = "<strong>Gagal</strong> Error tidak diketahui ";
                        $judul="Pembelian gagal";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=false;
                        $output = json_encode($return_arr);
                        die($output);
                    }
                }else{
                    $pesan = "<strong>Gagal</strong> Error tidak diketahui ";
                    $judul="Pembelian gagal";
                    $return_arr['pesan']=$pesan;
                    $return_arr['judul']=$judul;
                    $return_arr['success']=false;
                    $output = json_encode($return_arr);
                    die($output);
                }
            }else{
                $pesan = "<strong>Gagal</strong> Bintang kamu tidak mencukupi ";
                $judul="Pembelian gagal";
                $return_arr['pesan']=$pesan;
                $return_arr['judul']=$judul;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);
            }
        }
        return response()->json(['success'=>true,'pesan'=>$request->id_user,'judu'=>'Judul']);
    }

    public function kategori_paket(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('kategori_soal')->insert(['nama'=>$request->nama]);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Menambahkan";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function delete_kategori_paket(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('kategori_soal')->where('id',$request->id)->delete();
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Menghapus";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function update_kategori_paket(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('kategori_soal')->where('id',$request->id)->update(['nama'=>$request->nama]);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Mengupdate";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function update_paket(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('paket_soal')->where('id',$request->id)->update(['nama'=>$request->nama,'kategori'=>$request->kategori,'keterangan'=>$request->keterangan,'status'=>$request->status,'voucher'=>$request->voucher,'bintang'=>$request->bintang,'tgl_mulai'=>$request->tgl_mulai,'tgl_selesai'=>$request->tgl_selesai]);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Mengupdate";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function buat_paket(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('paket_soal')->insert(['nama'=>$request->nama,'kategori'=>$request->kategori,'keterangan'=>$request->keterangan,'status'=>$request->status,'voucher'=>$request->voucher,'bintang'=>$request->bintang,'tgl_mulai'=>$request->tgl_mulai,'tgl_selesai'=>$request->tgl_selesai]);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Dibuat";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function buat_sesi(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('sesi_soal')->insert(['id_paket_soal'=>$request->id_paket_soal,'nama_sesi'=>$request->nama_sesi,'durasi'=>$request->durasi,'urutan'=>$request->urutan,'induk_sesi'=>$request->induk_sesi]);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Ditambahkan";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function tambah_soal(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('soal')->insert(['id_paket_soal'=>$request->paket_id,'id_sesi_soal'=>$request->sesi,'isi'=>$request->isi,'kunci'=>$request->kunci,'pembahasan'=>$request->pembahasan,'a'=>$request->opsi_a,'b'=>$request->opsi_b,'c'=>$request->opsi_c,'d'=>$request->opsi_d,'e'=>$request->opsi_e,'score'=>'','menjawab_benar'=>'']);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Ditambahkan";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }
    public function ubah_soal(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $ins=DB::table('soal')->where('id',$request->id_soal)->update(['isi'=>$request->isi,'kunci'=>$request->kunci,'pembahasan'=>$request->pembahasan,'a'=>$request->opsi_a,'b'=>$request->opsi_b,'c'=>$request->opsi_c,'d'=>$request->opsi_d,'e'=>$request->opsi_e,'score'=>'','menjawab_benar'=>'']);
        if($ins){
            $pesan = "<strong>Sukses </strong> Berhasil Ditambahkan";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);

    }

    public function upload_image(Request $request){
        $validatedData=$request->validate([
            'file'=> 'required|file',
        ]);
        //$pathinfo=explode('/',$request->file('mimeType'));
        $imageName=time().'.'.$request->file('file')->extension();
        $path = $request->file('file')->move('images_soal', $imageName);
        //$path = $request->file('file')->store('images_soal');
        return ['location'=>url($path)];
        //return response()->json(array('location' => 'images_soal/'.$imageName));
    }
    function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 
    
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
    }
    function insert_image($mentah){
        $soal= str_get_html(html_entity_decode($mentah));
        $cek_img=$soal->find('img');
        foreach($cek_img as $img){
            $soal_image = $img->getAttribute('src');
            if(strpos($soal_image, 'data:image/') !== false) {
                $soal_image_array = preg_split("@[:;,]+@", $soal_image);
                $extensi = explode('/', $soal_image_array[1]);
                $nama_gambar= time().'.'.$extensi[1];
                $path = 'images_soal/'.$nama_gambar;
                $path_ganti='/images_soal'.'/'.$nama_gambar;
                //$file_name = $posisi.'/'.uniqid().'.'.$extensi[1];
                // menyimpan file dari base64
                //file_put_contents($file_name, base64_decode($soal_image_array[3]));
                Image::make(file_get_contents(self::base64_to_jpeg($soal_image,'contoh')))->save($path);
                $soal = str_replace($soal_image, $path_ganti, $soal);
            }
        }
        return $soal;
    }
    public function import_soal(Request $request){
        //include(app_path() . '/functions/simple_html.php');
        //$dom = HtmlDomParser::file_get_html( $file_name );
        $table = array();
        $html = str_get_html(html_entity_decode($request->isi));
        $nok=0;
        $body=$html->find('tbody',0);
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        foreach($body->children() as $row) {
                $no = $row->find('td',0)->plaintext;
                $soal = $row->find('td',1)->innertext;
                $a = $row->find('td',2)->innertext;
                $b = $row->find('td',3)->innertext;
                $c = $row->find('td',4)->innertext;
                $d = $row->find('td',5)->innertext;
                $e = $row->find('td',6)->innertext;
                $kunci = trim($row->find('td',7)->plaintext);
                $pembahasan = $row->find('td',8)->innertext;
                $table[$no][$soal] = true;
                if(is_numeric($row->find('td',0)->plaintext)){
                    $ins_soal=self::insert_image($soal);
                    $ins_a=self::insert_image($a);
                    $ins_b=self::insert_image($b);
                    $ins_c=self::insert_image($c);
                    $ins_d=self::insert_image($d);
                    $ins_e=self::insert_image($e);
                    $ins_pembahasan=self::insert_image($pembahasan);
                    //echo $no.". ".$ssoal."<br>".$a."<br>".$b."<br>".$c."<br>".$d."<br>".$e."<br>"."Kunci ".$kunci."<br> Pembahasan<br>".$pembahasan; 
                    $ins=DB::table('soal')->insert(['id_paket_soal'=>$request->id_paket,'id_sesi_soal'=>$request->id_sesi,'isi'=>$ins_soal,'kunci'=>$kunci,'pembahasan'=>$ins_pembahasan,'a'=>$ins_a,'b'=>$ins_b,'c'=>$ins_c,'d'=>$ins_d,'e'=>$ins_e,'score'=>'','menjawab_benar'=>'']);
                    if($ins){
                        $nok++;
                    }
                }
        } 
        if($nok>1){
            $pesan = "<strong>Sukses </strong>".$nok." Soal Berhasil Ditambahkan";
            $judul="Berhasil";
            $status=true;
        }else{
            $pesan = "<strong>Gagal </strong> Error tidak diketahui ";
            $judul="Insert gagal";
            $status=false;
        }
        $return_arr['pesan']=$pesan;
        $return_arr['judul']=$judul;
        $return_arr['success']=$status;
        return response()->json($return_arr);
    }

    public function kosongkan(Request $request){
        $sesi = $request->segment(3);
        $paket = $request->segment(4);
        $del=DB::table('soal')->where('id_sesi_soal',$sesi)->delete();
        return view('admin.sesi_soal');
    }
}
