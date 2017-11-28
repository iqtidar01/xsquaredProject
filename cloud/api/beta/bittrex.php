<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'//cloud/models/beta/index.php');
//echo($_SERVER['DOCUMENT_ROOT'].'/cloud/models/beta/index.php');

header('Access-Control-Allow-Methods: GET, POST');


header('Access-Control-Allow-Origin: *');

extract($_REQUEST);

$market = array(
    'BTCUSD' => 'USDT-BTC',
    'ETHUSD' => 'USDT-ETH',
    'BCHUSD' => 'USDT-BTC',
    'ETCUSD' => 'USDT-ETC',
    'LTCUSD' => 'USDT-LTC',
);


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
$currencypair = $market[$currencypair];


$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://bittrex.com/api/v1.1/public/getmarketsummary?market=$currencypair",
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

if(empty($respArr)){
  return;
}

$respArr = array($respArr);
$respArr = $respArr[0]['result'];

$respNew = $respArr;
$respNew1 = array();
for($i=0; $i<count($respNew); $i++){

  $open = floatval($respNew[$i]['BaseVolume']);
  $close = floatval($respNew[$i]['Last']);
  $high = floatval($respNew[$i]['High']);
  $low = floatval($respNew[$i]['Low']);
  $timestamp = intval($respNew[$i]['TimeStamp']);
  $volume = floatval($respNew[$i]['Volume']);

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