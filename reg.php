<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();

if(!empty($_SESSION["user"])){
	header("Location:/profile.php");
	exit;
}

$alert_message = array();
if(!empty($_GET["email"])){
	$email = $_GET["email"];
	
	if(!checkEmail($email)) 
		$alert_message[] = "е-мэйл не корректный!";
	
	$email = mysql_real_escape_string($email);
	if(!empty($_GET["pass"]) && !count($alert_message)){
		$pass = clearText($_GET["pass"]);
		if(strlen($pass) > 50) 
			$pass = cutText($pass, 50);
		
		if($pass != ""){
			$res = getOneSqlQuery('SELECT COUNT(*) FROM users WHERE email="' . $email . '" AND pass="' . mysql_real_escape_string($pass) . '"');
			if($res){
				if(isset($_GET["remember_me"]) && $_GET["remember_me"]=="on"){
					$timestamp = (time() + 3600*24*30);
					$key = substr(md5($email . $timestamp), 0, 16);
					$ar = array($email, $timestamp, $key);
					$sKey = implode('|', $ar);
					setcookie(NAME_COOKIE, $sKey, $timestamp);
					mysql_query('UPDATE users SET key_cookie="' . $key . '" WHERE email="' . $email . '"');
				}
				
				$_SESSION["user"] = getOneSqlQuery('SELECT id FROM users WHERE email="' . $email . '"');
				header('Location:/profile.php');
				exit;
			}
			else
				$alert_message[] = "пара е-мэйл/пароль не соответствуют!";
		}
		else
			$alert_message[] = "пара е-мэйл/пароль не соответствуют!";
	}
	elseif(!empty($_GET["pass_new"]) && !empty($_GET["pass_conf"]) && !empty($_GET["name"]) && !count($alert_message)){
		$pass_new 	= clearText($_GET["pass_new"]);
		$pass_conf 	= clearText($_GET["pass_conf"]);
		$name 		= clearText($_GET["name"]);
		
		if(strlen($pass_new) 	> 50) $pass_new  = cutText($pass_new, 50);
		if(strlen($pass_conf) 	> 50) $pass_conf = cutText($pass_conf, 50);
		if(strlen($name) 		> 50) $name 	 = cutText($name, 50);
		
		if(strlen($pass_new) < 5) 	$alert_message[] = "пароль слишком короткий!";
		if($pass_new != $pass_conf) $alert_message[] = "пароли не совпадают!";
		if(strlen($name) < 2) 		$alert_message[] = "впишите имя!";
		
		if(!count($alert_message)){
			$res = getOneSqlQuery('SELECT COUNT(*) FROM users WHERE email="' . $email . '"');
			if(!$res){
				mysql_query('INSERT INTO users (email, pass, name) VALUE ("' . $email 								. '", 
																		  "' . mysql_real_escape_string($pass_new) 	. '", 
																		  "' . mysql_real_escape_string($name) 		. '")');
				
				$_SESSION["user"] = getOneSqlQuery('SELECT id FROM users WHERE email="' . $email . '"');
				header('Location:/profile.php');
				exit;
			}
			else
				$alert_message[] = "пользователь с таким е-мэйлом уже есть!";
		}
	}
	elseif(!count($alert_message))
		$alert_message[] = "заполните необходимые поля!";
}
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: регистрация"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    
    <div align=center>
        <div align=justify style="width:800px">
        	<?
				if(count($alert_message)){
					$alert = '<ul>';
					
					foreach($alert_message as $val)
						$alert .= '<li>' . $val . '</li>';
					
					$alert .= '</ul>';
					
					echo '<div class="alert_message red">
								<table width=100%>
									<tr>
										<td align=right valign=top width=40%>Ошибка:</td>
										<td align=left valign=top width=60%>' . $alert . '</td>
									</tr>
								</table>
						  </div>';	
				}
			?>
            <form id=reg_form action="/reg.php" method=get>
                <div align=center style="margin-bottom:10px">
                    <strong>E-мэйл : </strong>
                    <input type=text name=email value="" style="width:480px; margin-left:10px" maxlength=50>
                </div>
                
                <div class="floatLeft div_reg selected">
                    <div align=center>
                        <input type=radio name=radio checked> &nbsp;Вход, если есть аккаунт!
                    </div>
                    Пароль: <input type=password name=pass style="width:250px; margin:30px 0px 20px 10px" maxlength=50>
                    <div align=center>
                        <input type=checkbox name=remember_me style="margin-right:5px"> Запомнить меня<br>
                        <a href="/sendpass.php" style="text-decoration:underline; margin:20px 0px 0px 0px; display:block">Забыли пароль?</a>    
                    </div>
                </div>
                
                <div class="floatRight div_reg">
                    <div align=center>
                        <input type=radio name=radio> &nbsp;Регистрация нового аккаунта!
                    </div>
                    Пароль: 	<input type=password name=pass_new 	style="width:250px; margin:30px 0px 10px 10px" 	maxlength=50><br>
                    Повторить: 	<input type=password name=pass_conf style="width:250px; margin-left:10px" 			maxlength=50><br>
                    Ваше имя: 	<input type=text	 name=name 		style="width:250px; margin:10px 0px 0px 10px" 	maxlength=50>
                </div>
                
                <div style="display:table; width:100%; text-align:center; padding-top:10px" >
                    <a class="btn btn_green" style="width:280px" onClick="$('#reg_form').submit()">
                        зарегистрироваться
                    </a>
                </div>
			</form>
        </div>
    </div>
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>