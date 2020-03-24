<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Mail.php");

$Sait = new Sait();

if( !empty($_REQUEST["update_cart"]) ){
	foreach($_REQUEST["update_cart"] as $key=>$value){
		foreach($_SESSION["cart"] as $key2=>$value2){
			if($key == $key2){
				$res = settype($value, "int");
				if($value > 999) $value=999;
				if(!$value) $value=0;
				if($value == 0) unset($_SESSION["cart"][$key2]);
				else $_SESSION["cart"][$key2] = $value;
			}
		}
	}
	
	$output = array("result" => true);
}
elseif( !empty($_REQUEST["send_pass"]) ){
	$result 	= false;
	$message 	= "";
	$email 		= $_REQUEST["send_pass"];
	$MyMail 	= new MyMail($_SERVER['SERVER_NAME'], 25, "localhost", SUPPORT, EMAIL_PASS, "BUKETtime.ru");
	
	if(checkEmail($email)){
		$pass = getOneSqlQuery('SELECT pass FROM users WHERE email="' . mysql_real_escape_string($email) . '"');
		if($pass != ""){
			$str = 'С сайта Bukettime.ru был запрошен пароль на восстановление.<br>
					Ваши данные для входа: е-мэйл ' . $email . ', пароль ' . $pass;
			try{
				$MyMail->send($email,  "Восстановление пароля", $str, true);
			}
			catch(Exception $e){
				mail(EMAIL_CREATOR, "Ошибка в класс MyMail, страница ". $_SERVER['PHP_SELF'] . ", сайт " . $_SERVER['SERVER_NAME'] , $e->getMessage());
			}

			$message = 'Ваш пароль выслан на почту.';
			$result = true;
		}
		else 
			$message = 'Ошибка: пользователя с таким е-мэйлом нет!';
	}
	else 
		$message = 'Ошибка: е-мэйл не корректен!';
	
	$output = array("result" => $result, "message" => $message);
}
elseif(!empty($_GET["send_subscribe"])){
	$result 	= false;
	$message 	= "";
	$email 		= $_GET["send_subscribe"];
	
	if( checkEmail($email) ){
		$temp = getOneSqlQuery('SELECT COUNT(*) FROM subscription WHERE email="' . $email . '"');
		if(!$temp){
			mysql_query('INSERT INTO subscription(email) VALUE("' . $email . '")');
			$message = "Е-мэйл успешно добавлен.";
			$result = true;	
		}
		else
			$message = "Ошибка: е-мэйл уже присутствует в базе данных!";	
	}
	else
		$message = "Ошибка: впишите корректный е-мэйл!";

	$output = array("result" => $result, "message" => $message);
}
elseif( !empty($_GET["curPass"]) && !empty($_GET["newPass"]) && !empty($_GET["confirmNewPass"]) && !empty($_SESSION["user"]) ){
	$result 	= false;
	$message 	= array();
	
	$curPass 		= clearText($_GET["curPass"]);
	$newPass 		= clearText($_GET["newPass"]);
	$confirmNewPass = clearText($_GET["confirmNewPass"]);
	
	if(strlen($curPass) 		> 50) $curPass  		= cutText($curPass, 50);
	if(strlen($newPass) 		> 50) $newPass 			= cutText($newPass, 50);
	if(strlen($confirmNewPass) 	> 50) $confirmNewPass 	= cutText($confirmNewPass, 50);
	
	if(strlen($curPass) < 5) 		$message[] = "- текущий пароль слишком короткий";
	if(strlen($newPass) < 5) 		$message[] = "- новый пароль слишком короткий";
	if($newPass != $confirmNewPass) $message[] = "- новые пароли не совподают";
	
	if(!count($message)){
		$newPass = mysql_real_escape_string($newPass);
		$curPass = mysql_real_escape_string($curPass);
		mysql_query('UPDATE users SET pass="' . $newPass . '" WHERE id=' . $_SESSION["user"]);
		$res = getOneSqlQuery('SELECT COUNT(*) FROM users WHERE id="' . $_SESSION["user"] . '" AND pass="' . $newPass . '"');
		
		if($res) 
			$result = true;
		else     
			$message[] = "- пароли не обновились, данные не соответствуют";
	}
	
	$output = array("result" => $result, "message" => "Ошибка:\n" . implode("\n", $message) );
}
elseif( isset($_GET["setting_send_news"]) && !empty($_SESSION["user"]) ){
	$val = $_GET["setting_send_news"]? 1: 0;
	mysql_query('UPDATE users SET send_news=' . $val . ' WHERE id=' . $_SESSION["user"]);
	$output = array("result" => true);
}
elseif( !empty($_GET["name"]) && !empty($_GET["address"]) && !empty($_GET["city"]) && !empty($_GET["country"]) && !empty($_GET["tel"]) && !empty($_SESSION["user"]) ){
	$result = false;
	$message = array();
	
	$name 		= clearText($_GET["name"]);
	$address 	= clearText($_GET["address"]);
	$city 		= clearText($_GET["city"]);
	$country 	= clearText($_GET["country"]);
	$tel 		= (string)clearTel($_GET["tel"]);
	
	if(myStrlen($name) 	> 50)  		$name  		= cutText($name, 50);
	if(myStrlen($address) > 255) 	$address 	= cutText($address, 255);
	if(myStrlen($city) 	> 50)  		$city  		= cutText($city, 50);
	if(myStrlen($tel) 	> 15)  		$tel  		= cutText($tel, 15);
	if(!is_numeric($country) || $country<0 || $country>205) $country = 43;
	
	if(myStrlen($name) 	< 2) 	$message[] = "- имя слишком короткое";
	if(myStrlen($address) < 5) 	$message[] = "- адрес слишком короткий";
	if(myStrlen($city) 	< 2) 	$message[] = "- название города слишком короткое";
	if(strlen($tel) 	< 7) 	$message[] = "- номер телефона слишком короткий";
	
	if(!count($message)){
		mysql_query('UPDATE users SET 	name="' . 				mysql_real_escape_string($name) 	. '",
										billing_address="' . 	mysql_real_escape_string($address) 	. '",
										town="' . 				mysql_real_escape_string($city) 	. '",
										country=' . 			$country 							. ',
										tel="' . 				mysql_real_escape_string($tel) 		. '"
						WHERE id=' . $_SESSION["user"]);
		$result = true;
	}
	
	$output = array("result" => $result, "message" => "Ошибка:\n" . implode("\n", $message) );
}
elseif( !empty($_POST['collection_id']) && is_numeric($_POST['collection_id']) && 
		!empty($_POST['how_often']) && is_numeric($_POST['how_often']) && 
		!empty($_POST['what_day'])  && is_numeric($_POST['what_day']) && !empty($_POST['bouquets']) && 
		!empty($_POST['address']) && !empty($_POST['tel']) && 
		!empty($_POST['option_to_buy']) && is_numeric($_POST['option_to_buy']) && !empty($_SESSION["user"]) ){
	$message = array();
	
	if($_POST['how_often']!=1 && $_POST['how_often']!=2 && $_POST['how_often']!=4) $message[] = "- не известно как часто присылать коллекцию";
	if($_POST['what_day']<6 || $_POST['what_day']>7) $message[] = "- не известно в какой день присылать";
	
	$_POST['address'] = clearText($_POST['address']);
	if(myStrlen($_POST['address'])) $_POST['address'] = cutText($_POST['address'], 255); 
	if( $_POST['address'] == "" ) 	$message[] = "- укажите адрес доставки";
	
	$_POST['tel'] = clearTel($_POST['tel']);
	if( empty($_POST['tel']) ) $message[] = "- укажите номер телефона";
	
	if($_POST['option_to_buy']<1 || $_POST['option_to_buy']>2) $message[] = "- не известен способ оплаты";
	if(!count($_POST['bouquets'])) $message[] = "- выберите букет";
	
	if(!count($message)){
		$how_often 		= $_POST['how_often'];
		$what_day 		= $_POST['what_day'];
		$address 		= $_POST['address'];
		$tel 			= $_POST['tel'];
		$option_to_buy 	= $_POST['option_to_buy'];
		$bouquets 		= implode("|", $_POST['bouquets']);
		$cost 			= getOneSqlQuery('SELECT cost FROM collections WHERE id=' . $_POST['collection_id']);
	}else{
		$output = array("result" => false, "message" => "Ошибка:\n" . implode("\n", $message) );
		die(json_encode($output));
	}
	
	$text_for_a_card 	= (!empty($_POST['text_for_a_card']))? clearBigText($_POST['text_for_a_card']): "";
	$dop_info 			= (!empty($_POST['dop_info']))? clearBigText($_POST['dop_info']): "";
	$metro 				= (is_numeric($_POST['metro']) && $_POST['metro']>0)? $_POST['metro']: 0;

	$userData = $Sait->getUserData();
	
	if($option_to_buy == 2){
		$html 	= "";
		$MyMail = new MyMail($_SERVER['SERVER_NAME'], 25, "localhost", SUPPORT, EMAIL_PASS, "BUKETtime.ru");
		$subject= "Bukettime.ru: Заказ букетов, оплата наличными.";
		
		try{
			$MyMail->send(SUPPORT,  		  $subject, $html, false);
			$MyMail->send($userData["email"], $subject, $html, true);
		}
		catch(Exception $e){
			mail(EMAIL_CREATOR, "Ошибка в класс MyMail, страница ". $_SERVER['PHP_SELF'] .", сайт " . $_SERVER['SERVER_NAME'] , $e->getMessage());
		}
		
		$output = array("result" => true, "message" => 2);
		die(json_encode($output));
	}

	mysql_query('INSERT INTO order_collection (user_id, 
											   collection_id, 
											   how_often, 
											   what_day, 
											   bouquets, 
											   text_for_a_card, 
											   address, metro, 
											   tel, 
											   more_info, 
											   option_to_buy) 
										VALUE (' . $_SESSION["user"] . ', 
											   ' . $_POST['collection_id'] . ', 
											   ' . $how_often . ', 
											   ' . $what_day . ', 
											   "' . $bouquets . '", 
											   "' . mysql_real_escape_string($text_for_a_card) . '", 
											   "' . mysql_real_escape_string($address) . '", 
											   ' . $metro . ', 
											   ' . $tel . ', 
											   "' . mysql_real_escape_string($dop_info) . '", 
											   ' . $option_to_buy . ')');

	$id = getOneSqlQuery('SELECT id FROM order_collection WHERE user_id=' . $_SESSION["user"] . ' ORDER BY id DESC LIMIT 1');
	$form = '<form id=form_for_chronopay action="https://payments.chronopay.com/" method=post>
				<input type=hidden name=product_id value="' . PRODUCT . '" />
				<input type=hidden name=product_price value=' . $cost . ' />
				<input type=hidden name=cb_url value="' . $Sait->host . '/callback/index.php?rebill" />
				<input type=hidden name=success_url value="' . $Sait->host . '/payment_success/" />
				<input type=hidden name=decline_url value="' . $Sait->host . '/payment_failed/" />
				<input type=hidden name=sign value="' . md5(PRODUCT . "-" . $cost . "-" . CHRONOPAY_KEY) . '" />
				<input type=hidden name=f_name 	value="' . $userData["name"] . '" />
				<input type=hidden name=city 	value="' . $userData["town"] . '" />
				<input type=hidden name=phone 	value="' . $userData["tel"] . '" />
				<input type=hidden name=street 	value="' . $userData["billing_address"] . '" />
				<input type=hidden name=email 	value="' . $userData["email"] . '" />
				<input type=hidden name=cs1 	value="' . $id . '" />
				<input type=hidden name=currency value=RUB />
				<input type=hidden name=payment_type_group_id value=1 />
			 </form>';
	
	$output = array("result" => true, "message" => 1, "chronopay" => $form);
}
elseif( !empty($_REQUEST["show_choose_cart"]) ){
	$message = "";
	$gifts = "";
	
	if(!count($_SESSION["cart"])) 
		die(json_encode(array("result" => false, "message" => "Корзина пуста!")));
	
	foreach($_SESSION["cart"] as $key=>$value){
		$q = mysql_query('SELECT name, cost FROM gifts WHERE id=' . $key);
		$data = mysql_fetch_array($q);
		$title = $data['name'];
		$cost  = $data['cost'];

		if(!empty($_SESSION["user"])) $cost -= ($Sait->discount*$cost)/100;

		$gifts .= '<div class=div_cart_last>
						<div mytag=name>
							' . $title . '
						</div>
						<img border=0 hspace=0 vspace=0 width=100 height=100 src="/img.php?f=gifts/' . $key . '.jpg&w=100&h=100">
						<div mytag=parent>
							<div mytag=title>
								Цена за 1 шт.:
							</div>
							<div mytag=cost>
								' . $cost . ' руб.
							</div>
						</div>
						<div mytag=parent>
							<div mytag=title>
								Количество:
							</div>
							<div mytag=cost>
								' . $value  . '
							</div>
						</div>
						<div mytag=parent>
							<div mytag=title>
								Общая цена:
							</div>
							<div mytag=cost>
								' . ($cost * $value) . ' руб.
							</div>
						</div>
					</div>';
	}
	
	$message = '
			<br>
			<h6 style="float:left; text-transform:none">Выбран товар на сумму - ' . $Sait->countCart(1) . ' руб.</h6>
			<font color=gray style="float:right">' . changeEnMonthToRus(date("n")) . ' ' . date("j, Y (H:i:s)") . '</font>
			<hr>
			<div style="display:table">
				' . $gifts . '
			</div>
			<h6 style="float:left">дополнительная информация</h6>
			<hr>
			<div class=div_cart_last_dop_info>
				<div>
					Текст на открытке:
				</div>
				<div>
					<textarea id=cart_last_text_on_postcard></textarea>
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Имя/фамилия * :
				</div>
				<div>
					<input id=cart_last_ifo type=text maxlength=255>
				</div>
			</div>
            <div class=div_cart_last_dop_info>
				<div>
					Адрес доставки * :
				</div>
				<div>
					<input id=cart_last_address type=text maxlength=255>
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Ближайшее метро:
				</div>
				<div>
					<select id=cart_last_metro>
						<option value=""></option>
						' . $Sait->getOptionsMosMetro() . '
					</select>
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Дата доставки * :
				</div>
				<div>
					' . $Sait->getSmallCalendar() . '
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Номер телефона * :
				</div>
				<div>
					<input id=cart_last_tel type=text maxlength=20 style="width:160px">
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Отправить копию на e-мэйл * :
				</div>
				<div>
					<input id=cart_last_mail type=text maxlength=50>
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Дополнительная информация от Вас:
				</div>
				<div>
					<textarea id=cart_last_dop_info></textarea>
				</div>
			</div>
			<div class=div_cart_last_dop_info>
				<div>
					Оплатить с помощью * :
				</div>
				<div>
					<span style="display:table; margin-bottom:10px">
						<span class=payment>
							<input type=radio name=payment_type_group_id value=1 checked=checked>
							<img border=0 hspace=0 vspace=0 width=75 height=25 src="/img/logo_visa.png" />
						</span>
						<span class=payment>
							<input type=radio name=payment_type_group_id value=21>
							<img border=0 hspace=0 vspace=0 width=75 height=25 src="/img/logo_qiwi.png" />
						</span>
						<span class=payment>
							<input type=radio name=payment_type_group_id value=16>
							<img border=0 hspace=0 vspace=0 width=75 height=25 src="/img/logo_yamoney.png" />
						</span>
						<span class=payment>
							<input type=radio name=payment_type_group_id value=15>
							<img border=0 hspace=0 vspace=0 width=75 height=25 src="/img/logo_webm.png" />
						</span>
						<hr class=hr_4>
						или
						<hr class=hr_4>
						<span class=payment style="width:95px;">
							<input type=radio name=payment_type_group_id value=22>
							<div style="margin-top:5px">наличными</div>
						</span>
						
					</span>
					<img border=0 hspace=0 vspace=0 width=106 height=65 src="/img/Verfied_by_VISA.jpg">
					<img border=0 hspace=0 vspace=0 width=120 height=65 src="/img/MasterCard_SecureCode.jpg">
				</div>
			</div>
			' . $Sait->getBr(20) . '
			<div align=left>
				Доставка бесплатная!<br>
				* поля обязательны для заполнения
			</div>
			' . $Sait->getBr(20) . '
			<center>
				<a id=btn_from_cart_send class="btn btn_green" style="width:200px;" onClick="collectFinalDataFromcartSendAndBuy($(this))">
					<img class=img_preloader_small src="/img/preloader_small.png">
					отправить и оплатить
				</a>
			</center>';
	
	$output = array("result" => true, "message" => $message);
}
elseif( !empty($_REQUEST["cart_send_data"]) && count($_SESSION["cart"])){
	$obj = $_REQUEST["cart_send_data"];
	
	$gifts = "";
	foreach($_SESSION["cart"] as $key=>$value){
		$q = mysql_query('SELECT name, cost FROM gifts WHERE id=' . $key);
		$data = mysql_fetch_array($q);
		$title = $data['name'];
		$cost  = $data['cost'];

		if(!empty($_SESSION["user"])) 
			$cost -= ($Sait->discount*$cost)/100;

		$gifts .= '<div style="float:left; margin: 0px 10px 10px 0px; border:#ccc solid 1px; padding:10px">
						<div style="margin-bottom:10px; border-bottom:#eee solid 1px;">
							<strong>' . $title . '</strong>
						</div>
						<div align=center>
							<img width=100 height=100 src="' . HOST . '/img.php?f=' . HOST . '/gifts/' . $key . '.jpg&w=100&h=100">
						</div>
						<div>
							<font color=gray size="-1">Цена за 1 шт.:</font> ' . $cost . ' руб.
						</div>
						<div>
							<font color=gray size="-1">Количество:</font> ' . $value . '
						</div>
						<div>
							<font color=gray size="-1">Общая цена:</font> ' . ($cost * $value) . ' руб.
						</div>
				  </div>';
	}

	$err = "";
	if($gifts == "") 
		die(json_encode(array("result" => false, "message" => "Корзина пуста!")));

	$obj["tel"] = (string)clearTel($obj["tel"]);
	if(strlen($obj["tel"]) > 15) $obj["tel"] = substr($obj["tel"], 0, 15);
	
	$obj["ifo"] = clearText($obj["ifo"]);
	if(myStrlen($obj["ifo"]) > 50) $obj["ifo"] = substr($obj["ifo"], 0, 50);	
	
	$obj["address"] = clearText($obj["address"]);
	if(myStrlen($obj["address"]) > 255) $obj["address"] = substr($obj["address"], 0, 255);
	
	$obj["mail"] = checkEmail($obj["mail"]);
	
	$obj["date"] = clearText($obj["date"]);
	if(strlen($obj["date"]) > 10) $obj["date"] = substr($obj["date"], 0, 10);
	
	$obj["text_on_postcard"] = clearBigText($obj["text_on_postcard"]);
	if(myStrlen($obj["text_on_postcard"]) > 500) $obj["text_on_postcard"] = substr($obj["text_on_postcard"], 0, 500);
	
	$obj["metro"] = clearText($obj["metro"]);
	if(myStrlen($obj["metro"]) > 100) $obj["metro"] = substr($obj["metro"], 0, 100);
	
	$obj["dop_info"] = clearBigText($obj["dop_info"]);
	if(myStrlen($obj["dop_info"]) > 500) $obj["dop_info"] = substr($obj["dop_info"], 0, 500);

	settype($obj["payment_type_group_id"], "int");
	if( ($obj["payment_type_group_id"] != 1)  && 
		($obj["payment_type_group_id"] != 15) && 
		($obj["payment_type_group_id"] != 16) &&
		($obj["payment_type_group_id"] != 21) &&
		($obj["payment_type_group_id"] != 22)	){
		$obj["payment_type_group_id"] = 1;		
	}

	if($obj["payment_type_group_id"] === 1) 	 $payment = "банковская карта";
	elseif($obj["payment_type_group_id"] === 15) $payment = "Webmoney";
	elseif($obj["payment_type_group_id"] === 16) $payment = "Яндекс деньги";
	elseif($obj["payment_type_group_id"] === 21) $payment = "Qiwi";
	elseif($obj["payment_type_group_id"] === 22) $payment = "наличные";
	else 										 $payment = "(неизвестно)";

	if(strlen($obj["tel"]) < 5) 	$err .= "Ошибка: необходим корректный Ваш номер телефона для уточнения заказа!\n";
	if(strlen($obj["ifo"]) < 2) 	$err .= "Ошибка: впишите имя/фамилию!\n";
	if(strlen($obj["address"]) < 5) $err .= "Ошибка: необходим адресс доставки подарока(ов)!\n";
	if(!$obj["mail"]) 				$err .= "Ошибка: необходим корректный е-мэйл для фиксации заказа!\n";
	if(strlen($obj["date"]) < 5) 	$err .= "Ошибка: необходима дата достави!\n";
	
	if($err != "") 
		die(json_encode(array("result" => false, "message" => $err)));
	
	$border = "border-bottom:whitesmoke solid 1px;";
	$color_font = "color:gray;";
	$html = '<table align=center cellspacing=5 cellpadding=5 width=100%>
				<tr style="' . $border . '">
					<td align=right width=300 style="' . $color_font . '">
						Торговое наименование предприятия:
					</td>
					<td align=left>
						BUKETtime
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						ID товара в системе ChronoPay:
					</td>
					<td align=left width=600>
						' . PRODUCT . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Дата оформление заказа:
					</td>
					<td align=left>
						' . changeEnMonthToRus(date("n")) . ' ' . date("j, Y (H:i:s)") . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Общая сумма заказа:
					</td>
					<td align=left>
						' . $Sait->countCart(1) . ' руб.
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Корзина:
					</td>
					<td align=left>
						' . $gifts . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Текст на открытке:
					</td>
					<td align=left>
						' . $obj["text_on_postcard"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Имя/фамилия:
					</td>
					<td align=left>
						' . $obj["ifo"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Адрес:
					</td>
					<td align=left>
						' . $obj["address"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Метро:
					</td>
					<td align=left>
						' . $obj["metro"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Дата доставки:
					</td>
					<td align=left>
						' . $obj["date"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Номер телефона заказчика:
					</td>
					<td align=left>
						' . $obj["tel"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Е-мэйл:
					</td>
					<td align=left>
						' . $obj["mail"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Дополнительная информация:
					</td>
					<td align=left>
						' . $obj["dop_info"] . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Платёжная cистема:
					</td>
					<td align=left>
						' . $payment . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						URL электронного магазина:
					</td>
					<td align=left>
						<a href="' . HOST . '">' . HOST . '</a>
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Контактный электронный адрес предприятия и контактный телефон:
					</td>
					<td align=left>
						' . SUPPORT . '<br>
						' . TEL . '
					</td>
				</tr>
				<tr style="' . $border . '">
					<td align=right style="' . $color_font . '">
						Тип операции:
					</td>
					<td align=left>
						продажа
					</td>
				</tr>
			</table>';

	$MyMail 	= new MyMail($_SERVER['SERVER_NAME'], 25, "localhost", SUPPORT, EMAIL_PASS, "BUKETtime.ru");
	$subject 	= "Заказ подарка(ов) с сайта bukettime.ru через корзину.";
	
	try{
		$MyMail->send(SUPPORT,  	$subject, $html, false);
		$MyMail->send($obj["mail"], $subject, $html, true);
	}
	catch(Exception $e){
		mail(EMAIL_CREATOR, "Ошибка в класс MyMail, страница ". $_SERVER['PHP_SELF'] . ", сайт " . $_SERVER['SERVER_NAME'] , $e->getMessage());
	}

	if($payment == "наличные") 
		die(json_encode(array("result" => true, "message" => 1)));

	$show = '<form id=form_for_chronopay action="https://payments.chronopay.com/" method=post>
				<input type=hidden name=product_id value="' . PRODUCT . '" />
				<input type=hidden name=product_price value=' . $Sait->countCart(1) . ' />
				<input type=hidden name=cb_url value="' . $Sait->host . '/callback/" />
				<input type=hidden name=success_url value="' . $Sait->host . '/payment_success/" />
				<input type=hidden name=decline_url value="' . $Sait->host . '/payment_failed/" />
				<input type=hidden name=sign value="' . md5(PRODUCT . "-" . $Sait->countCart(1) . "-" . CHRONOPAY_KEY) . '" />
				<input type=hidden name=language value=ru />
				<input type=hidden name=f_name 	value="' . $obj["ifo"] . '" />
				<input type=hidden name=phone 	value="' . $obj["tel"] . '" />
				<input type=hidden name=street 	value="' . $obj["address"] . '" />
				<input type=hidden name=email 	value="' . $obj["mail"] . '" />
				<input type=hidden name=cs1 	value="' . $obj["tel"] . '" />
				<input type=hidden name=cs2 	value="' . $obj["address"] . '" />
				<input type=hidden name=cs3 	value="' . $obj["mail"] . '" />
				<input type=hidden name=currency value=RUB />
				<input type=hidden name=payment_type_group_id value=' . $obj["payment_type_group_id"] . ' />
			 </form>';
	
	$output = array("result" => true, "message" => 2, "chronopay" => $show);
}
elseif( !empty($_REQUEST["on_off_id"]) && !empty($_SESSION["user"]) ){
	$id = $_REQUEST["on_off_id"];
	$res = getOneSqlQuery('SELECT on_off FROM order_collection WHERE id=' . $id . ' AND user_id=' . $_SESSION["user"]);
	$res = ($res)? 0: 1;
	mysql_query('UPDATE order_collection SET on_off=' . $res . ' WHERE id=' . $id . ' AND user_id=' . $_SESSION["user"]);
	$output = array("result" => true);
}
elseif( !empty($_REQUEST["deleteSubscriptions"]) && !empty($_SESSION["user"]) ){
	$id = $_REQUEST["deleteSubscriptions"];
	mysql_query('DELETE FROM order_collection WHERE id=' . $id . ' AND user_id=' . $_SESSION["user"]);
	$output = array("result" => true);
}
elseif( !empty($_SESSION["admin"]) && !empty($_REQUEST["opt"]) ){
	$result = false;
	$html = "";
	$content = "";
	
	if($_REQUEST["opt"] == "about_us" || $_REQUEST["opt"] == "agreement" || $_REQUEST["opt"] == "BenefitsForMembers" || $_REQUEST["opt"] == "business" || $_REQUEST["opt"] == "deliver" || $_REQUEST["opt"] == "OneTimeDelivery" || $_REQUEST["opt"] == "contact" || $_REQUEST["opt"] == "faq"){
		if($_REQUEST["opt"] == "about_us"){
			$id = 8;
			$action_for_save = 'Admin.setAboutUs()';
		} 
		elseif($_REQUEST["opt"] == "agreement"){
			 $id = 1;
			 $action_for_save = 'Admin.setAgreement()';
		}
		elseif($_REQUEST["opt"] == "BenefitsForMembers"){
			 $id = 5;
			 $action_for_save = 'Admin.setBenefitsForMembers()';
		}
		elseif($_REQUEST["opt"] == "business"){
			 $id = 4;
			 $action_for_save = 'Admin.setBusiness()';
		}
		elseif($_REQUEST["opt"] == "deliver"){
			 $id = 7;
			 $action_for_save = 'Admin.setDeliver()';
		}
		elseif($_REQUEST["opt"] == "OneTimeDelivery"){
			 $id = 6;
			 $action_for_save = 'Admin.setOneTimeDelivery()';
		}
		elseif($_REQUEST["opt"] == "contact"){
			 $id = 2;
			 $action_for_save = 'Admin.setContact()';
		}
		elseif($_REQUEST["opt"] == "faq"){
			 $id = 3;
			 $action_for_save = 'Admin.setFaq()';
		}
		
		
		if(!empty($_POST["set"]) && isset($_POST["meta_desc"]) && isset($_POST["meta_keywords"]) && isset($_POST["content"]) 
			&& isset($_POST["title"]) ){
			
			$title 			= $_POST["title"];
			$meta_desc 		= $_POST["meta_desc"];
			$meta_keywords 	= $_POST["meta_keywords"];
			$content 		= $_POST["content"];
			
			$title			= mysql_real_escape_string($title);
			$meta_desc 		= mysql_real_escape_string($meta_desc);
			$meta_keywords 	= mysql_real_escape_string($meta_keywords);
			$content 		= mysql_real_escape_string($content);
			
			mysql_query('UPDATE pages SET meta_desc="'. $meta_desc .'", meta_keywords="'. $meta_keywords .'", 
										  content="'. $content .'", title="' . $title . '" WHERE id=' . $id);
			
			die(json_encode(array("result" => true)));	
		}
		
		$data = $Sait->getPage($id);
		$html = '<table width=100% cellspacing=5>
					<tr>
						<td width=200 align=right>
							Заголовок страницы:
						</td>
						<td>
							<input id=admin_title type=text maxlength=255 value="' . $data['title'] . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Ключевые слова страницы:
						</td>
						<td>
							<input id=admin_meta_keywords type=text maxlength=255 value="' . $data['meta_keywords'] . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Описание страницы:
						</td>
						<td>
							<input id=admin_meta_desc type=text maxlength=255 value="' . $data['meta_desc'] . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Контент:
						</td>
						<td>
							<textarea id=admin_textarea></textarea>
						</td>
					</tr>
					<tr>
						<td colspan=2 align=center>
							<br>
							<a class="btn btn_green" onClick="' . $action_for_save . '">Сохранить</a>
						</td>
					</tr>
				</table>';
		$content = $data['content'];
		$result = true;
	}
	elseif($_REQUEST["opt"] == "blogItem"){
		if(!empty($_GET["id"]) && !empty($_GET["delete"])){
			getOneSqlQuery('DELETE FROM blog WHERE id=' . $_GET["id"]);
			$path = $_SERVER['DOCUMENT_ROOT'] . "/blog";
			$temp = scandir($path);
			$temp_img = array();
			foreach($temp as $val){
				if(is_file($path . "/" . $val) && substr($val, -4) == ".jpg" && substr($val, 0, strpos($val, "_")) == $_GET["id"]){
					 unlink($path . "/" . $val);
				}
			}	
			die(json_encode(array("result" => true)));	
		}
		if(isset($_POST["set"]) && isset($_POST["meta_desc"]) && isset($_POST["meta_keywords"]) && isset($_POST["content"]) 
			&& isset($_POST["title"]) ){
			
			$title 			= urldecode($_POST["title"]);
			$meta_desc 		= urldecode($_POST["meta_desc"]);
			$meta_keywords 	= urldecode($_POST["meta_keywords"]);
			$content 		= urldecode($_POST["content"]);
			
			$title			= mysql_real_escape_string($title);
			$meta_desc 		= mysql_real_escape_string($meta_desc);
			$meta_keywords 	= mysql_real_escape_string($meta_keywords);
			$content 		= mysql_real_escape_string($content);
				
			if( !empty($_POST["set"]) && getOneSqlQuery('SELECT COUNT(*) FROM blog WHERE id=' . $_POST["set"]) ){
				mysql_query('UPDATE blog SET title="' . $title . '", content="'. $content .'", 
											  description="'. $meta_desc .'", keywords="'. $meta_keywords .'" 
									WHERE id=' . $_POST["set"]);
				$id = $_POST["set"];	
			}else{
				mysql_query('INSERT INTO blog (title, content, description, keywords) VALUE ("' . $title . '", "'. $content .'", "'. $meta_desc .'", "'. $meta_keywords .'")');
				$id = getOneSqlQuery('SELECT id FROM blog ORDER BY id DESC LIMIT 1');	
			}
			
			$path = $_SERVER['DOCUMENT_ROOT'] . "/blog";
			$temp = scandir($path);
			$temp_img = array();
			foreach($temp as $val){
				if(is_file($path . "/" . $val) && substr($val, -4) == ".jpg" && substr($val, 0, strpos($val, "_")) == $id){
					 $temp_img[] = substr($val, strpos($val, "_")+1, -4);	 
				}
			}
			$last_int = array_pop($temp_img);
			$last_int = intval($last_int);
			$last_int? $last_int++: 0;
			
			if( count($_FILES['admin_file']["name"])>0 ){
				eachImages($_FILES['admin_file'], $id, $last_int, $_SERVER['DOCUMENT_ROOT'] . "/blog");
			}
			
			die(json_encode(array("result" => true)));	
		}
		
		if(!empty($_GET["id"])){
			$q = mysql_query('SELECT * FROM blog WHERE id=' . $_GET["id"]);
			$data = mysql_fetch_array($q);
			
			$id 			= $_GET["id"];
			$meta_keywords 	= $data["keywords"];
			$meta_desc 		= $data["description"];
			$title 			= $data["title"];
			$content 		= $data["content"];
		}else{
			$id 			= "";
			$meta_keywords 	= "";
			$meta_desc 		= "";
			$title 			= "";
			$content 		= "";	
		}
		
		$html = '<table width=100% cellspacing=5>
					<tr>
						<td width=200 align=right>
							Загрузка картинок:
						</td>
						<td>
							<input id=admin_file type=file style="width:500px" name="admin_file[]" multiple=true>
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Ключевые слова страницы:
						</td>
						<td>
							<input id=admin_meta_keywords type=text maxlength=255 value="' . $meta_keywords . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Описание страницы:
						</td>
						<td>
							<input id=admin_meta_desc type=text maxlength=255 value="' . $meta_desc . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Заголовок:
						</td>
						<td>
							<input id=admin_title type=text maxlength=255 value="' . $title . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Контент:
						</td>
						<td>
							<textarea id=admin_textarea></textarea>
						</td>
					</tr>
					<tr>
						<td colspan=2 align=center>
							<br>
							<a class="btn btn_green" onClick="Admin.setBlogItem(' . $id . ')">Сохранить</a>
						</td>
					</tr>
				</table>';
		$result = true;
	}
	elseif($_REQUEST["opt"] == "collection"){
		if(!empty($_GET["id"]) && !empty($_GET["delete"])){
			getOneSqlQuery('DELETE FROM collections WHERE id=' . $_GET["id"]);
			$path = $_SERVER['DOCUMENT_ROOT'] . "/collections";
			$temp = scandir($path);
			$temp_img = array();
			foreach($temp as $val){
				if(is_file($path . "/" . $val) && substr($val, -4) == ".jpg" && substr($val, 0, strpos($val, "_")) == $_GET["id"]){
					 unlink($path . "/" . $val);
				}
			}	
			die(json_encode(array("result" => true)));	
		}
		if(isset($_POST["set"]) && isset($_POST["meta_desc"]) && isset($_POST["meta_keywords"]) && isset($_POST["content"]) 
			&& isset($_POST["title"]) && isset($_POST["cost"]) && isset($_POST["discount"]) ){
			
			$title 			= urldecode($_POST["title"]);
			$meta_desc 		= urldecode($_POST["meta_desc"]);
			$meta_keywords 	= urldecode($_POST["meta_keywords"]);
			$content 		= urldecode($_POST["content"]);
			$cost			= urldecode($_POST["cost"]);
			$discount		= urldecode($_POST["discount"]);
			
			$title			= mysql_real_escape_string($title);
			$meta_desc 		= mysql_real_escape_string($meta_desc);
			$meta_keywords 	= mysql_real_escape_string($meta_keywords);
			$content 		= mysql_real_escape_string($content);
			$cost 			= intval($cost);
			$discount 		= intval($discount);
				
			if( !empty($_POST["set"]) && getOneSqlQuery('SELECT COUNT(*) FROM collections WHERE id=' . $_POST["set"]) ){
				mysql_query('UPDATE collections SET name="' . $title . '", cost=' . $cost . ', discount=' . $discount . ', about="'. $content .'", 
											  description="'. $meta_desc .'", keywords="'. $meta_keywords .'" 
									WHERE id=' . $_POST["set"]);
				$id = $_POST["set"];	
			}else{
				mysql_query('INSERT INTO collections (name, cost, discount, about, description, keywords) 
								VALUE ("' . $title . '", ' . $cost . ', ' . $discount . ', "'. $content .'", "'. $meta_desc .'", "'. $meta_keywords .'")');
				$id = getOneSqlQuery('SELECT id FROM collections ORDER BY id DESC LIMIT 1');	
			}
			
			$path = $_SERVER['DOCUMENT_ROOT'] . "/collections";
			$temp = scandir($path);
			$temp_img = array();
			foreach($temp as $val){
				if(is_file($path . "/" . $val) && substr($val, -4) == ".jpg" && substr($val, 0, strpos($val, "_")) == $id){
					 $temp_img[] = substr($val, strpos($val, "_")+1, -4);	 
				}
			}
			$last_int = array_pop($temp_img);
			$last_int = intval($last_int);
			$last_int? $last_int++: 0;
			
			if( count($_FILES['admin_file']["name"])>0 ){
				eachImages($_FILES['admin_file'], $id, $last_int, $_SERVER['DOCUMENT_ROOT'] . "/collections");
			}
			
			die(json_encode(array("result" => true)));	
		}
		
		if(!empty($_GET["id"])){
			$q = mysql_query('SELECT * FROM collections WHERE id=' . $_GET["id"]);
			$data = mysql_fetch_array($q);
			
			$id 			= $_GET["id"];
			$meta_keywords 	= $data["keywords"];
			$meta_desc 		= $data["description"];
			$title 			= $data["name"];
			$content 		= $data["about"];
			$cost			= $data["cost"];
			$discount		= $data["discount"];
		}else{
			$id 			= "";
			$meta_keywords 	= "";
			$meta_desc 		= "";
			$title 			= "";
			$content 		= "";
			$cost			= 0;
			$discount		= 0;	
		}
		
		$html = '<table width=100% cellspacing=5>
					<tr>
						<td width=200 align=right>
							Загрузка картинок:
						</td>
						<td>
							<input id=admin_file type=file style="width:500px" name="admin_file[]" multiple=true>
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Ключевые слова страницы:
						</td>
						<td>
							<input id=admin_meta_keywords type=text maxlength=255 value="' . $meta_keywords . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Описание страницы:
						</td>
						<td>
							<input id=admin_meta_desc type=text maxlength=255 value="' . $meta_desc . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Заголовок:
						</td>
						<td>
							<input id=admin_title type=text maxlength=255 value="' . $title . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Цена:
						</td>
						<td>
							<input id=admin_cost type=text maxlength=5 value=' . $cost . ' />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Скидка:
						</td>
						<td>
							<input id=admin_discount type=text maxlength=2 value=' . $discount . ' />
						</td>
					</tr>
					<tr>
						<td align=right>
							Контент:
						</td>
						<td>
							<textarea id=admin_textarea style="width:570px; height:50px">' . $content . '</textarea>
						</td>
					</tr>
					<tr>
						<td colspan=2 align=center>
							<br>
							<a class="btn btn_green" onClick="Admin.setCollection(' . $id . ')">Сохранить</a>
						</td>
					</tr>
				</table>';
		$result = true;
	}
	elseif($_REQUEST["opt"] == "gift"){
		if(!empty($_GET["id"]) && !empty($_GET["delete"])){
			getOneSqlQuery('DELETE FROM gifts WHERE id=' . $_GET["id"]);
			unlink($_SERVER['DOCUMENT_ROOT'] . "/gifts/" . $_GET["id"] . ".jpg");
			die(json_encode(array("result" => true)));	
		}
		if(isset($_POST["set"]) && isset($_POST["meta_desc"]) && isset($_POST["meta_keywords"]) && isset($_POST["content"]) 
			&& isset($_POST["title"]) && isset($_POST["title_page"]) && isset($_POST["cost"]) && isset($_POST["discount"]) && isset($_POST["dop_gifts"]) ){
			
			$title 			= urldecode($_POST["title"]);
			$title_page 	= urldecode($_POST["title_page"]);
			$meta_desc 		= urldecode($_POST["meta_desc"]);
			$meta_keywords 	= urldecode($_POST["meta_keywords"]);
			$content 		= urldecode($_POST["content"]);
			$cost			= urldecode($_POST["cost"]);
			$discount		= urldecode($_POST["discount"]);
			$dop_gifts		= urldecode($_POST["dop_gifts"]);
			
			$title			= mysql_real_escape_string($title);
			$title_page		= mysql_real_escape_string($title_page);
			$meta_desc 		= mysql_real_escape_string($meta_desc);
			$meta_keywords 	= mysql_real_escape_string($meta_keywords);
			$content 		= mysql_real_escape_string($content);
			$cost 			= intval($cost);
			$discount 		= intval($discount);
			$dop_gifts		= mysql_real_escape_string($dop_gifts);
			
			$temp = explode("\\n", $dop_gifts);
			$a_dop_gifts = array();
			foreach($temp as $val){
				$temp0 = parse_url($val);
				list($var, $value_id) = explode("=", $temp0["query"]);
				$a_dop_gifts[] = $value_id;
			}
			$dop_gifts = implode("|", $a_dop_gifts);
			
			if( !empty($_POST["set"]) && getOneSqlQuery('SELECT COUNT(*) FROM gifts WHERE id=' . $_POST["set"]) ){
				mysql_query('UPDATE gifts SET name="' . $title . '", 
											  title_page="' . $title_page . '", 
											  cost=' . $cost . ', 
											  discount=' . $discount . ', 
											  about="'. $content .'", 
											  description="'. $meta_desc .'", 
											  keywords="'. $meta_keywords .'" 
									WHERE id=' . $_POST["set"]);
				$id = $_POST["set"];
				
				if(!getOneSqlQuery('SELECT COUNT(id) FROM dop_gifts WHERE gift=' . $id))
					mysql_query('INSERT INTO dop_gifts (gift, dop_gifts) VALUE (' . $id . ', "' . $dop_gifts . '");');	
				else
					mysql_query('UPDATE dop_gifts SET dop_gifts="' . $dop_gifts . '" WHERE gift=' . $id);	
			
			}else{
				mysql_query('INSERT INTO gifts (name, title_page, cost, discount, about, description, keywords) 
								VALUE ("' . $title . '", "' . $title_page . '", ' . $cost . ', ' . $discount . ', "'. $content .'", "'. $meta_desc .'", "'. $meta_keywords .'");');
				$id = getOneSqlQuery('SELECT id FROM gifts ORDER BY id DESC LIMIT 1');
				mysql_query('INSERT INTO dop_gifts (gift, dop_gifts) VALUE (' . $id . ', "' . $dop_gifts . '");');			
			}
			
			if(!empty($_FILES['admin_file']["name"])){
				$ext = getExtension($_FILES['admin_file']["name"]);
				if( ($ext == "jpg") || ($ext == "jpeg") ){
					$name = $id . ".jpg";
					if( move_uploaded_file($_FILES['admin_file']["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/gifts/" . $name) ) 
						image_resize($_SERVER['DOCUMENT_ROOT'] . "/gifts/" . $name, 380);
				}
			}
			
			die(json_encode(array("result" => true)));	
		}
		
		if(!empty($_GET["id"])){
			$q = mysql_query('SELECT * FROM gifts WHERE id=' . $_GET["id"]);
			$data = mysql_fetch_array($q);
			
			$id 			= $_GET["id"];
			$meta_keywords 	= $data["keywords"];
			$meta_desc 		= $data["description"];
			$title 			= $data["name"];
			$title_page 	= $data["title_page"];
			$content 		= $data["about"];
			$cost			= $data["cost"];
			$discount		= $data["discount"];
			$dop_gifts		= getOneSqlQuery('SELECT dop_gifts FROM dop_gifts WHERE gift=' . $id);
			
			if($dop_gifts != ""){
				$a_dop_gifts = explode("|", $dop_gifts);
				$dop_gifts = "";
				foreach($a_dop_gifts as $val){
					$dop_gifts .= HOST . "/gifts/?id=" . $val . "\n";
				}
			}
		}else{
			$id 			= "";
			$meta_keywords 	= "";
			$meta_desc 		= "";
			$title 			= "";
			$title_page		= "";
			$content 		= "";
			$cost			= 0;
			$discount		= 0;
			$dop_gifts		= "";	
		}
		
		$html = '<table width=100% cellspacing=5>
					<tr>
						<td width=200 align=right>
							Загрузка картинки:
						</td>
						<td>
							<input id=admin_file type=file style="width:500px" name="admin_file">
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Ключевые слова страницы:
						</td>
						<td>
							<input id=admin_meta_keywords type=text maxlength=255 value="' . $meta_keywords . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Описание страницы:
						</td>
						<td>
							<input id=admin_meta_desc type=text maxlength=255 value="' . $meta_desc . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Заголовок страницы:
						</td>
						<td>
							<input id=admin_title_page type=text maxlength=255 value="' . $title_page . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Заголовок:
						</td>
						<td>
							<input id=admin_title type=text maxlength=255 value="' . $title . '" />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Цена:
						</td>
						<td>
							<input id=admin_cost type=text maxlength=5 value=' . $cost . ' />
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Скидка:
						</td>
						<td>
							<input id=admin_discount type=text maxlength=2 value=' . $discount . ' />
						</td>
					</tr>
					<tr>
						<td align=right>
							Дополнительные товары:
						</td>
						<td>
							<textarea id=admin_dop_gifts>' . $dop_gifts . '</textarea>
						</td>
					</tr>
					<tr>
						<td align=right>
							Контент:
						</td>
						<td>
							<textarea id=admin_textarea></textarea>
						</td>
					</tr>
					<tr>
						<td colspan=2 align=center>
							<br>
							<a class="btn btn_green" onClick="Admin.setGift(' . $id . ')">Сохранить</a>
						</td>
					</tr>
				</table>';
		$result = true;
	}
	elseif($_REQUEST["opt"] == "pressa"){
		if(!empty($_GET["id"]) && !empty($_GET["delete"])){
			getOneSqlQuery('DELETE FROM pressa WHERE id=' . $_GET["id"]);
			unlink($_SERVER['DOCUMENT_ROOT'] . "/pressa/" . $_GET["id"] . ".jpg");
			die(json_encode(array("result" => true)));	
		}
		if(isset($_POST["set"]) && isset($_POST["link"]) && isset($_POST["content"]) ){
			
			$link 			= urldecode($_POST["link"]);
			$content 		= urldecode($_POST["content"]);
			
			$link			= mysql_real_escape_string($link);
			$content 		= mysql_real_escape_string($content);
				
			if( !empty($_POST["set"]) && getOneSqlQuery('SELECT COUNT(*) FROM pressa WHERE id=' . $_POST["set"]) ){
				mysql_query('UPDATE pressa SET url="' . $link . '", about="'. $content .'" WHERE id=' . $_POST["set"]);
				$id = $_POST["set"];	
			}else{
				mysql_query('INSERT INTO pressa (url, about) VALUE ("' . $link . '", "'. $content .'")');
				$id = getOneSqlQuery('SELECT id FROM pressa ORDER BY id DESC LIMIT 1');	
			}
			
			if(!empty($_FILES['admin_file']["name"])){
				$ext = getExtension($_FILES['admin_file']["name"]);
				if( ($ext == "jpg") || ($ext == "jpeg") ){
					$name = $id . ".jpg";
					if( move_uploaded_file($_FILES['admin_file']["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/pressa/" . $name) ) 
					image_resize($_SERVER['DOCUMENT_ROOT'] . "/pressa/" . $name, 201);
				}
			}
			
			die(json_encode(array("result" => true)));	
		}
		
		if(!empty($_GET["id"])){
			$q = mysql_query('SELECT * FROM pressa WHERE id=' . $_GET["id"]);
			$data = mysql_fetch_array($q);
			
			$id 			= $_GET["id"];
			$link 			= $data["url"];
			$content 		= $data["about"];
		}else{
			$id 			= "";
			$link			= "";
			$content 		= "";	
		}
		
		$html = '<table width=100% cellspacing=5>
					<tr>
						<td width=200 align=right>
							Загрузка картинки:
						</td>
						<td>
							<input id=admin_file type=file style="width:500px" name="admin_file">
						</td>
					</tr>
					<tr>
						<td width=200 align=right>
							Ссылка:
						</td>
						<td>
							<input id=admin_link type=text maxlength=255 value="' . $link . '" />
						</td>
					</tr>
					<tr>
						<td align=right>
							Контент:
						</td>
						<td>
							<textarea id=admin_textarea style="width:570px; height:50px">' . $content . '</textarea>
						</td>
					</tr>
					<tr>
						<td colspan=2 align=center>
							<br>
							<a class="btn btn_green" onClick="Admin.setPressa(' . $id . ')">Сохранить</a>
						</td>
					</tr>
				</table>';
		$result = true;
	}
	
	$output = array("result" => $result, "html" => $html, "content" => $content);
}
elseif(!empty($_GET["addDopGiftInCartId"])){
	$result = false;
	$id = intval($_GET["addDopGiftInCartId"]);
	
	$key = array_key_exists($id, $_SESSION["cart"])? true: false;
	
	if($key) $result = false;
	else{
		$_SESSION["cart"][$id] = 1;
		$result = true;	
	}
	
	$output = array("result" => $result, "total" => $Sait->countCart());
}
else 
	$output = array("result" => false);

die(json_encode($output));

?>