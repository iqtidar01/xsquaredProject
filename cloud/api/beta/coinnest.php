<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'//cloud/models/beta/index.php');
//echo($_SERVER['DOCUMENT_ROOT'].'/cloud/models/beta/index.php');

header('Access-Control-Allow-Methods: GET, POST');


header('Access-Control-Allow-Origin: *');

extract($_REQUEST);



if(!isset($period)){
  $period = '1h';
}

if(!isset($limit)){
  $limit = '100';
}

if(!isset($currencypair)){
  $currencypair = 'ETHUSD';
}

if($period == "1D"){
  $period = "1d";
}

if($period == "7D"){
  $period = "7d";
}
$currencypair = strtolower($currencypair);

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.coinnest.co.kr/api/pub/ticker/'.$currencypair,
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

$respArr = json_decode($resp, true);

//echo '<pre>';
//print_r($respArr);
//echo '</pre>';
//exit;

$respArr = array($respArr);

$respNew = $respArr;
$respNew1 = array();
for($i=0; $i<count($respNew); $i++){

  $open = floatval($respNew[$i]['buy']);
  $close = floatval($respNew[$i]['last']);
  $high = floatval($respNew[$i]['high']);
  $low = floatval($respNew[$i]['low']);
  $timestamp = intval($respNew[$i]['time']);
  $volume = floatval($respNew[$i]['vol']);

  $respNew1[$i][0] = $timestamp;
  $respNew1[$i][1] = $open;
  $respNew1[$i][2] = $high;
  $respNew1[$i][3] = $low;
  $respNew1[$i][4] = $close;
  $respNew1[$i][5] = $volume;

}

$spliced = array_slice($respNew1, -100);
$resp = json_encode($spliced);
echo($resp);

?>