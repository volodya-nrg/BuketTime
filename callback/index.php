<?php
	if(getenv("REMOTE_ADDR") != "159.255.220.143")
		exit;
	
	$str = 'Идентификатор транзакции 	= ' . 	$_REQUEST["transaction_id"] . '
			ID заказа 					= ' . 	$_REQUEST["product_id"] . '
			Customer ID  				= ' . 	$_REQUEST["customer_id"] . '
			----------------------------------------------------------------------------
			Номер карты 		= ' . 			$_REQUEST["creditcardnumber"] . '
			Имя и фам. владельца карты = ' . 	$_REQUEST["cardholder"] . '
			Срок действия карты = ' . 			$_REQUEST["expire_date"] . '
			----------------------------------------------------------------------------
			Общая сумма			= ' . 			$_REQUEST["total"] . ' руб
			Тип транзакции	 	= ' . 			$_REQUEST["transaction_type"] . '
			Дата 				= ' . 			$_REQUEST["date"] . '
			Имя и фам. держателя платёжного инструмента (б.к.) = ' . $_REQUEST["name"] . '
			Номер телефона		= ' . 			$_REQUEST["phone"] . '
			Адрес				= ' . 			$_REQUEST["street"] . '
			Емейл 				= ' . 			$_REQUEST["email"] . '
			Тип платёжного средства = ' . 		$_REQUEST["payment_type"] . '
			----------------------------------------------------------------------------
			Дополнительные поля:';
	
	if( isset($_REQUEST["rebill"]) ){
		require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	
		$err = "Ошибка: не сработал mysql_query при запросе на rebill, customer_id не поменялся у id=" . $_REQUEST["cs1"] . " !";
		$err = iconv("utf-8", "cp1251", $err);
		$str .= 'ID в базе данных = ' . $_REQUEST["cs1"];
		//$str = iconv("utf-8", "cp1251", $str);
		
		$q = 'UPDATE order_collection SET customer_id="' . $_REQUEST["customer_id"] . '", accept_in_chronopay=1 WHERE id=' . $_REQUEST["cs1"];
		mysql_query($q) or mail(EMAIL_CREATOR, "BUKETtime.ru: mysql query не сработал", $err);
		
		mail(EMAIL_CREATOR, "BUKETtime.ru: успешный ответ на rebill", $str);
	}else{
		$str .='Номер телефона		= ' . $_REQUEST["cs1"] . '
				Адрес				= ' . $_REQUEST["cs2"] . '
				Емейл				= ' . $_REQUEST["cs3"];
		//$str = iconv("utf-8", "cp1251", $str);
		
		if(isset($_SESSION["cart"])) 
			$_SESSION["cart"] = array();
		
		mail(EMAIL_CREATOR, "BUKETtime.ru: успешный ответ на эл-ую оплату.", $str);
	}
?>