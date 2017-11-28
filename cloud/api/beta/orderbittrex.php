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

if(!isset($currencypair)){
  $currencypair = 'ETHUSD';
}
//$currencypair=strtolower($currencypair);

$currencypair = $market[$currencypair];

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://bittrex.com/api/v1.1/public/getorderbook?market=$currencypair&type=both",
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);


$resp=str_replace('buy','bids',$resp);
$resp=str_replace('sell','asks',$resp);
//$resp = file_get_contents("https://api.bitfinex.com/v2/candles/trade:".$period.":t".$currencypair."/hist?limit=".$limit);


$respArr = json_decode($resp, true);
$respArr = $respArr['result'];


for ($i=0;$i<sizeof($respArr['bids']);$i++){
  $respArr['bids'][$i] = array_values((array)$respArr['bids'][$i]);
}
for ($i=0;$i<sizeof($respArr['asks']);$i++){
  $respArr['asks'][$i] = array_values((array)$respArr['asks'][$i]);
}

$resp = json_encode($respArr);
echo($resp);

?>