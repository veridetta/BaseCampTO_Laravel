<?php
if($_GET){
    header('Content-Type: application/json');
    $return_arr= array(
        "total"=> "",
        "totalNotFiltered"=> "",
        "rows"=>array());
    include '../../config/connect.php';
    $search=isset($_GET['search']) ? $_GET['search'] : '';
    $offset=isset($_GET['offset']) ? $_GET['offset'] : '';
    $limit=isset($_GET['limit']) ? $_GET['limit'] : '';
    $sort=isset($_GET['sort']) ? $_GET['sort'] : '';
    $order=isset($_GET['order']) ? $_GET['order'] : '';
    $to=mysqli_query($con, "select * from user where role='user'");
    $total=mysqli_num_rows($to);
    if(strlen($offset)>0){
        $no=$offset+1;
        if(strlen($sort)<1){
            $si=mysqli_query($con, "select * from user where role='user' and nama like '%$search%' limit $limit offset $offset");
            echo mysqli_error($con);
        }else{
            $si=mysqli_query($con, "select * from user where role='user' and nama like '%$search%' order by $sort $order limit $limit offset $offset");
        }
    }else{
        $no=1;
        if(strlen($sort)<1){
            $si=mysqli_query($con, "select * from user where role='user' and nama like '%$search%' limit $limit");
            echo mysqli_error($con);
        }else{
            $si=mysqli_query($con, "select * from user where role='user' and nama like '%$search%' order by $sort $order limit $limit");
        }
    }
    
    $hitung=mysqli_num_rows($si);
    $ide=array();
    $nomor=array();
    $nama=array();
    $bintang=array();
    $ikut=array();
    while($siswa=mysqli_fetch_array($si)){
        $sa=mysqli_query($con, "select * from riwayat_bintang where id_users='$siswa[id]' order by id desc limit 1");
        $sal=mysqli_fetch_assoc($sa);
        $hitung=mysqli_num_rows($sa);
        $saldo=$sal['saldo'];
        if($hitung<1){
            $saldo=0;
        }
        $pak=mysqli_query($con, "select * from peserta_paket where id_user='$siswa[id]'");
        $paket=mysqli_num_rows($pak);
        $jpaket=$paket;
        if($paket<1){
            $jpaket=0;
        }
        $rows['id']=$siswa['id'];
        $rows['no']=$no;
        $rows['nama']=$siswa['nama'];
        $rows['hp']=$siswa['hp'];
        $rows['bintang']=$saldo;
        $rows['ikut']=$jpaket;
        $no++;
        array_push($return_arr["rows"], $rows);
    }
    $return_arr['total']=$total;
    $return_arr['totalNotFiltered']=$total;
    echo json_encode($return_arr); 
}
?>