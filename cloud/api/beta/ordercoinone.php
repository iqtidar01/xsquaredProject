<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'//cloud/models/beta/index.php');
//echo($_SERVER['DOCUMENT_ROOT'].'/cloud/models/beta/index.php');

header('Access-Control-Allow-Methods: GET, POST');


header('Access-Control-Allow-Origin: *');

extract($_REQUEST);

if(!isset($currencypair)){
  $currencypair = 'ETHUSD';
}
//$currencypair=strtolower($currencypair);

$currencyFirst = substr($currencypair,0,3);

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.coinone.co.kr/orderbook/?currency=$currencyFirst",
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);


$resp=str_replace('bid','bids',$resp);
$resp=str_replace('ask','asks',$resp);
$respArr = json_decode($resp, true);

for ($i=0;$i<sizeof($respArr['bids']);$i++){
  $respArr['bids'][$i] = array_values((array)$respArr['bids'][$i]);
}
for ($i=0;$i<sizeof($respArr['asks']);$i++){
  $respArr['asks'][$i] = array_values((array)$respArr['asks'][$i]);
}

$resp = json_encode($respArr);
echo($resp);

?>