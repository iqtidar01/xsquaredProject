<?php

//include_once($_SERVER['DOCUMENT_ROOT'].'//cloud/models/beta/index.php');
//echo($_SERVER['DOCUMENT_ROOT'].'/cloud/models/beta/index.php');

header('Access-Control-Allow-Methods: GET, POST'); 


header('Access-Control-Allow-Origin: *');

extract($_REQUEST);



if(!isset($currencypair)){
  $currencypair = 'ETHUSD';
}
$currencypair=strtolower($currencypair);

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://www.bitstamp.net/api/v2/order_book/'.$currencypair,
    CURLOPT_USERAGENT => 'Proof Req'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);


$resp=str_replace('timestamp','lastUpdateId',$resp);
//$resp = file_get_contents("https://api.bitfinex.com/v2/candles/trade:".$period.":t".$currencypair."/hist?limit=".$limit);

//$respArr = json_decode($resp, true);
//for($i=0; $i<count($respArr['bids']); $i++) {
//  $respArr['bids'][$i][]=array();
//}

/*
$respNew = $respArr;

$newArr = array("asks"=>array(), "bids"=>array());
for($i=0; $i<count($respNew); $i++){

  if($respNew[$i][2]<0){
    $pusher = [$respNew[$i][0], abs($respNew[$i][2])];
    array_push($newArr['asks'], $pusher);
  }
  else{
   $pusher = [$respNew[$i][0], abs($respNew[$i][2])];
     array_push($newArr['bids'], $pusher );
  }

}
*/

//$resp = json_encode($respArr);
echo($resp);

?>