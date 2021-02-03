<?php
$curl = curl_init();
reset ($_FILES);
$temp = current($_FILES);
$client_id = 'c419515babedb27';
$cilent_secret = '14e6f931567bc234e294d87559e4edf51a51253d';
$token= '70184e627b51dc5592bc2cfb58e0f35c2a6611cc';
$data = $temp['tmp_name'];
$data_name = "Bagja-College-".$temp['name'];
$handle = fopen($data, "r");
$file= fread($handle, filesize($data));
$url = 'https://api.imgur.com/3/image';
$headers = array("Authorization: Bearer $token");
$pvars  = array('image' =>base64_encode($file), 'title' => $data_name, 'album' =>'nsb2q7D', 'description' => 'Bagja College Try Out');
$curl = curl_init();
curl_setopt_array($curl, array(
   CURLOPT_URL=> $url,
   CURLOPT_TIMEOUT => 30,
   CURLOPT_POST => 1,
   CURLOPT_RETURNTRANSFER => 1,
   CURLOPT_HTTPHEADER => $headers,
   CURLOPT_POSTFIELDS => $pvars
));
$json_returned = curl_exec($curl); // blank response
  $pms = json_decode($json_returned,true);
  $url=$pms['data']['link'];
curl_close ($curl); 
echo json_encode(array('location' => $url));

?>