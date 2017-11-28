<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'//cloud/models/beta/index.php');
//echo($_SERVER['DOCUMENT_ROOT'].'/cloud/models/beta/index.php');

header('Access-Control-Allow-Methods: GET, POST');


header('Access-Control-Allow-Origin: *');

extract($_REQUEST);

if(!isset($currencypair)){
  $currencypair = 'ETHUSD';
}
$currencypair = strtolower($currencypair);

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.gemini.com/v1/book/$currencypair",
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

//$resp = file_get_contents("https://api.bitfinex.com/v2/candles/trade:".$period.":t".$currencypair."/hist?limit=".$limit);

$resp = json_decode($resp, true);

if(isset($resp['error'])){
  $resp = array(
      'bids'=>array(),
      'asks'=>array(),
  );
  $resp = json_encode($resp);
  echo($resp);
  return;
}

for($i=0;$i<sizeof($resp['bids']);$i++){
  $resp['bids'][$i] = array_values((array)$resp['bids'][$i]);
}

for($i=0;$i<sizeof($resp['asks']);$i++){
  $resp['asks'][$i] = array_values((array)$resp['asks'][$i]);
}

$resp = json_encode($resp);
echo($resp);

?>