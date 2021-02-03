<?php
if($_GET){
    include '../config/connect.php';
    header('Content-Type: application/json');
    session_start();
    $dat= array(
        "data_clicks"=>array()
    );
    if($_SESSION){
        $nama=$_SESSION['nama'];
        $id=$_SESSION['id'];
        $ref=$_SESSION['ref'];
    }else{
         $gagal=1;
    }
    for($i=0;$i<4;$i++){
        $ar['name']="Option";
        $ar['data']=array();
        $dak=array(
            "x"=>"",
            "y"=>""
        );
        for($u=0;$u<6;$u++){
            $arx['x']="hello";
            $arx['y']=$u;
            array_push($ar["data"], $arx);
        }
        array_push($dat["data_clicks"], $ar);
    }
    $dat['data']=$ar;
    echo json_encode($dat);
}
?>