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

$currencyFirst = substr($currencypair,0,3);
$currencySecond = substr($currencypair,3,3);
$date = date('Ymd', strtotime('-1 day'));

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://cex.io/api/ohlcv/hd/$date/$currencyFirst/$currencySecond",
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

$respArr = json_decode($resp, true);

if(empty($respArr)){

  $resp=array(array(0,0,0,0,0,0));
  $resp = json_encode($resp);
  echo($resp);
  return;
}
if($period == '1m'){
  $respArr = $respArr['data1m'];
}
else if($period == '1h'){
  $respArr = $respArr['data1h'];
}
else if($period == '1D'){
  $respArr = $respArr['data1d'];
}
else{
  $respArr = $respArr['data1m'];
}

$respArr = json_decode($respArr,true);
$respNew = $respArr;
$respNew1 = array();
for($i=0; $i<count($respNew); $i++){

  $timestamp = intval($respNew[$i][0]);
  $open = floatval($respNew[$i][1]);
  $high = floatval($respNew[$i][2]);
  $low = floatval($respNew[$i][3]);
  $close = floatval($respNew[$i][4]);
  $volume = floatval($respNew[$i][5]);

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