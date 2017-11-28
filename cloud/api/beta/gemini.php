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
//$currencypair = strtolower($currencypair);

$periodsArray = array(
    '1m'=>'1',
    '5m'=>'5',
    '15m'=>'15',
    '30m'=>'30',
    '1h'=>'60',
    '1d'=>'240',
    '7d'=>'240',
    '1M'=>'1440',
);
$period = $periodsArray[$period];

$currencypair = strtolower($currencypair);

$spliced = array(array(0,0,0,0,0,));
$resp = json_encode($spliced);
echo($resp);
return;

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.gdax.com/products/$currencypair/candles",
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

$respArr = json_decode($resp, true);

$respNew = $respArr;
$respNew1 = array();
for($i=0; $i<count($respNew); $i++){

  $timestamp = intval($respNew[$i][0]);
  $low = floatval($respNew[$i][1]);
  $high = floatval($respNew[$i][2]);
  $open = floatval($respNew[$i][3]);
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