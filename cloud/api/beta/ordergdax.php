<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'//cloud/models/beta/index.php');
//echo($_SERVER['DOCUMENT_ROOT'].'/cloud/models/beta/index.php');

header('Access-Control-Allow-Methods: GET, POST'); 


header('Access-Control-Allow-Origin: *');

extract($_REQUEST);

$products = array(
    'BTCUSD' => 'BTC-USD',
    'ETHUSD' => 'ETH-USD',
    'ETCUSD' => 'ETH-USD',
    'LTCUSD' => 'LTC-USD',
    'BCHUSD' => 'ETH-USD',
);

if(!isset($currencypair)){
  $currencypair = 'ETHUSD';
}
$currencypair = $products[$currencypair];

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.gdax.com/products/$currencypair/trades",
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

//$resp = file_get_contents("https://api.bitfinex.com/v2/candles/trade:".$period.":t".$currencypair."/hist?limit=".$limit);

$resp = json_decode($resp, true);
$respArr = array(
  'bids'=>array(),
  'asks'=>array(),
);

foreach ($resp as $item){

  if($item['side']=='sell'){
    $respArr['asks'][] = array($item['price'],$item['size']);
  }
  else if($item['side']=='buy'){
    $respArr['bids'][] = array($item['price'],$item['size']);
  }

}

$resp = json_encode($respArr);
echo($resp);

?>