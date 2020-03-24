<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Mail.php");

$podpischiki 	= array();
$MyMail 		= new MyMail($_SERVER['SERVER_NAME'], 25, "localhost", SUPPORT, EMAIL_PASS, "BUKETtime.ru");

$query = mysql_query('SELECT id FROM order_collection WHERE accept_in_chronopay=1 AND on_off=1 AND how_often=4');
while($ar = mysql_fetch_array($query)) 
	$podpischiki[] = $ar[0];

if( !date('W', time())%2 ){
	$query = mysql_query('SELECT id FROM order_collection WHERE accept_in_chronopay=1 AND on_off=1 AND how_often=2');
	while($ar = mysql_fetch_array($query)) 
		$podpischiki[] = $ar[0];
}

if( !date('W', time())%4 ){
	$query = mysql_query('SELECT id FROM order_collection WHERE accept_in_chronopay=1 AND on_off=1 AND how_often=1');
	while($ar = mysql_fetch_array($query)) 
		$podpischiki[] = $ar[0];
}

foreach($podpischiki as $value){
	$query 	= mysql_query('SELECT * FROM order_collection WHERE id=' . $value);
	$data 	= mysql_fetch_array($query);
	
	$customer_id 	= $data["customer_id"];
	$cost 			= getOneSqlQuery('SELECT cost FROM collections WHERE id=' . $data["collection_id"]);
	$email 			= getOneSqlQuery('SELECT email FROM users WHERE id=' . 		$data["user_id"]);
	
	$request = '<?xml version="1.0" encoding="utf-8"?><request><Opcode>3</Opcode><hash>' . md5(CHRONOPAY_KEY . "-3-" . PRODUCT) . '</hash><Customer>' . $customer_id . '</Customer><Product>' . PRODUCT . '</Product><Money><amount>' . $cost . '</amount></Money></request>';
	
	$curl_options = array (
	  CURLOPT_URL 				=> 'https://gate.chronopay.com/',
	  CURLOPT_POST 				=> TRUE,
	  CURLOPT_RETURNTRANSFER 	=> FALSE,
	  CURLOPT_HEADER 			=> array(
			'POST /index.php HTTP/1.1', 
			'Host: www.gate.chronopay.com', 
			'Content-Type: text/xml; charset=utf-8', 
			'Content-Length: ' . strlen(($request))
		),
	  CURLOPT_POSTFIELDS 		=> ($request)
	);
	
	$err = "";
	
	$curl = curl_init() 					or $err .= "Bukettime.ru, Cron, " . $customer_id . ": cURL init error"; 
	curl_setopt_array($curl, $curl_options) or $err .= "Bukettime.ru, Cron, " . $customer_id . ": cURL set options error" . curl_error($curl);
	$response = curl_exec($curl) 			or $err .= "Bukettime.ru, Cron, " . $customer_id . ": cURL execute error" . 	curl_error($curl);
	curl_close($curl);
	
	if($err != "")
		mail(EMAIL_CREATOR, $_SERVER['SERVER_NAME'] . ", Cron: ошибка в curl у " . $customer_id, "Результат: " . $response);
	else{
		$title = "BUKETtime.ru (" . $customer_id . "): рекуррертные платежи (списание)";
		$msg   = "Списание счёта с ID-заказа - " . $customer_id . ". Результат: " . $response;
	
		try{
			$MyMail->send(SUPPORT, $title, $msg, false);
			$MyMail->send($email,  $title, $msg, true);
		}
		catch(Exception $e){
			mail(EMAIL_CREATOR, "Ошибка в класс MyMail, страница ". $_SERVER['PHP_SELF'] . ", сайт " . $_SERVER['SERVER_NAME'] , $e->getMessage());
		}
	}
}
?>