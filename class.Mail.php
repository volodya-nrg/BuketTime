<?
class MyMail{
	private $domain;
	private $port;
	private $smtp_server;
	private $login;
	private $pass;
	private $from;
	private $signature;
	private $smtp_conn;
	
	public  function __construct($domain, $port, $smtp_server, $login, $pass, $signature = ""){
		$this->domain 		= $domain;
		$this->port 		= $port;
		$this->smtp_server  = $smtp_server;
		$this->login  		= $login;
		$this->pass 		= $pass;
		$this->from 		= $this->login;
		$this->signature 	= $signature;
	}
	private function htmlTemplate($msg, $to=""){
		$hr_1 = '<hr style="width:100%; color:#88b759; background-color:#88b759; border:0px none; height:1px; clear:both; margin:10px 0px;">';
		$hr_2 = '<hr style="width:100%; color:#d6d6d6; background-color:#d6d6d6; border:0px none; height:1px; clear:both; margin:10px 0px;">';
		$a_style = 'style="color:#496ebb; text-decoration:none;"';
		$a_style_2 = 'style="color:#496ebb; text-decoration:none; margin-right:10px;"';
		$a_style_3 = 'style="color:#496ebb; text-decoration:none;"';
		
		if($to != ""){
			$dop_text = 'Вы получили это письмо, потому что являетесь пользователем <strong>' . $_SERVER['SERVER_NAME'] . '</strong>.
						 <a href="http://' . $_SERVER['SERVER_NAME'] . '/profile.php?settings" ' . $a_style . '>Настройки сообщений</a>
						 <br>
						 Для <a href="http://' . $_SERVER['SERVER_NAME'] . '" ' . $a_style . '>входа на сайт</a> 
						 используйте свою электронную почту <strong>' . $to . '</strong>.
						 <a href="http://' . $_SERVER['SERVER_NAME'] . '/sendpass.php" ' . $a_style . '>Забыли пароль?</a>
						 <br>
						 <a href="http://' . $_SERVER['SERVER_NAME'] . '/profile.php" ' . $a_style . '>Моя страница</a>
						 &nbsp;&nbsp;&nbsp;
						 <a href="http://' . $_SERVER['SERVER_NAME'] . '/profile.php?subscriptions" ' . $a_style . '>Мои подписки</a>
						 &nbsp;&nbsp;&nbsp;
						 <a href="http://' . $_SERVER['SERVER_NAME'] . '/contact.php" ' . $a_style . '>Помощь</a>';
			$a_reg = "";
		}
		else{
			$dop_text = 'Букеты цветов и подарки для Вас. Присоединяйтесь!';
			$a_reg = '<a href="http://' . $_SERVER['SERVER_NAME'] . '/reg.php" style="padding:5px; color:white; background-color:black; text-decoration:none; border-radius:2px; margin-left:10px">Регистрация</a>';
		}
		
		return '<div style="background-color:whitesmoke; font-family:Arial, sans-serif; font-size:12px; color:#333; padding:20px">	
					<div style="margin:10px auto; background-color:white; width:600px; padding:20px; border:lightgray solid 1px; min-height:300px; border-radius:2px; position:relative">
						<div style="height:26px">
							<a href="http://' . $_SERVER['SERVER_NAME'] . '" style="font-family:Times, serif; color:black; font-size:26px; line-height:26px; float:left; text-decoration:none;">
								BUKETtime
							</a>	
							<div style="float:right; margin-top:4px">
								<a href="http://' . $_SERVER['SERVER_NAME'] . '/service.php" 	' . $a_style_2 . '>
									Подписка на цветы
								</a>
								<a href="http://' . $_SERVER['SERVER_NAME'] . '/collections/" 	' . $a_style_2 . '>
									Букеты
								</a>
								<a href="http://' . $_SERVER['SERVER_NAME'] . '/gifts/" 	' . $a_style_2 . '>
									Подарки
								</a>
								<a href="http://' . $_SERVER['SERVER_NAME'] . '/business/" ' . $a_style_2 . '>
									Для офиса
								</a>
								<a href="http://' . $_SERVER['SERVER_NAME'] . '/blog/" 	' . $a_style_3 . '>
									Блог
								</a>
								' . $a_reg . '
							</div>
						</div>
						' . $hr_1 . '
						<div>
							' . $msg . ' 
						</div>
					</div>
					<center style="color:#999; font-size:10px; width:600px; margin:auto">
						Не отвечайте на письмо &mdash; оно отправлено автоматически
						' . $hr_2 . '
						' . $dop_text . '
						<br> 
						С уважением, интернет-магазин <strong>' . $_SERVER['SERVER_NAME'] . '</strong>
					</center>
				</div>';
	}
	private function get_data(){
		$data = "";
		
		while($str = fgets($this->smtp_conn, 515)){
			$data .= $str;
			
			if(substr($str, 3, 1) == " ")
				break;
		}
		
		return $data;
	}
	private function check_mail($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	public function send($to, $subject, $msg, $for_user=true, $aFile=array()){
		$br = "\r\n";
		$temp_aFile = array();
		
		if(!$this->check_mail($to))
			throw new Exception("неверен е-мэйл получателя!");
		
		$msg = trim($msg);
		if(strlen($msg) <= 0)
			throw new Exception("впишите сообщение для письма!");
		
		if(count($aFile)){
			foreach($aFile as $val){
				if(is_file($val)){
					$fa = fopen($val, "rb");
					$temp_aFile[] = array("name"=>basename($val), "data"=>fread($fa, filesize($val)));
					fclose($fa);
				}
			}
		}
		
		$msg = $for_user? $this->htmlTemplate($msg, $to): $this->htmlTemplate($msg);
		
		$header = "Date: ". date('D, d M Y h:i:s O') . $br;
		$header.= "From: =?utf-8?b?" . base64_encode($this->signature) . "?= <" . $this->from . ">" . $br; 
		$header.= "X-Mailer: The Bat! (v3.99.3) Professional" . $br; 
		$header.= "Reply-To: =?utf-8?b?" . base64_encode($this->signature) . "?= <" . $this->from . ">" . $br;
		$header.= "X-Priority: 3 (Normal)" . $br;
		$header.= "Message-ID: <172562218." . date("YmjHis") . "@" . $this->domain . ">" . $br;
		$header.= "To: <" . $to . ">" . $br;
		$header.= "Subject: =?utf-8?b?" . base64_encode($subject) . "?=" . $br;
		$header.= "MIME-Version: 1.0" . $br;
		
		if(count($temp_aFile)){
			$bound = "--" . md5(uniqid(time()));
			$header.="Content-Type: multipart/mixed; boundary=\"" . $bound . "\"" . $br;
			
			$temp = $br . "--" . $bound . $br;
			$temp.= "Content-Type: text/html; charset=utf-8" . $br;
			$temp.= "Content-Transfer-Encoding: base64" . $br;
			$temp.= $br;
			$temp.= chunk_split(base64_encode($msg));
			
			foreach($temp_aFile as $val){
				$temp.= $br . $br . "--" . $bound . $br;
				$temp.= "Content-Type: application/octet-stream; name=\"" . $val["name"] . "\"" . $br;
				$temp.= "Content-Transfer-Encoding: base64" . $br;
				$temp.= "Content-Disposition: attachment; filename=\"" . $val["name"] . "\"" . $br;
				$temp.= $br;
				$temp.= chunk_split(base64_encode($val["data"]));
			}
			
			$msg = $temp;
		}
		else{
			$header.= "Content-Type: text/html; charset=utf-8" . $br;
			$header.= "Content-Transfer-Encoding: base64" . $br;
			
			$msg = base64_encode($msg);
		}
		
		$this->smtp_conn = fsockopen($this->smtp_server, $this->port, $errno, $errstr, 10);
		
		if(!$this->smtp_conn)
			throw new Exception("соединение серверов не прошло!");
		$data = $this->get_data();
		
		fputs($this->smtp_conn, "EHLO " . $this->domain . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 250){
			fclose($this->smtp_conn);
			throw new Exception("не прошло приветсвие EHLO!");
		}
		
		fputs($this->smtp_conn, "AUTH LOGIN" . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 334){
			fclose($this->smtp_conn);
			throw new Exception("сервер не разрешил начать авторизацию!");
		}
		
		fputs($this->smtp_conn, base64_encode($this->login) . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 334){
			fclose($this->smtp_conn);
			throw new Exception("нет доступа к такому юзеру!");
		}
		
		fputs($this->smtp_conn, base64_encode($this->pass) . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 235){
			fclose($this->smtp_conn);
			throw new Exception("не правильный пароль!");
		}
		
		fputs($this->smtp_conn, "MAIL FROM:" . $this->from . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 250){
			fclose($this->smtp_conn);
			throw new Exception("сервер отказал в команде MAIL FROM!");
		}
		
		fputs($this->smtp_conn, "RCPT TO:" . $to . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 250 && $code != 251){
			fclose($this->smtp_conn);
			throw new Exception("cервер не принял команду RCPT TO!");
		}
		
		fputs($this->smtp_conn, "DATA" . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 354){
			fclose($this->smtp_conn);
			throw new Exception("сервер не принял DATA!");
		}
		
		fputs($this->smtp_conn, $header . $br . $msg . $br . "." . $br);
		$code = substr($this->get_data(), 0, 3);
		if($code != 250){
			fclose($this->smtp_conn);
			throw new Exception("письмо не отправилось!");
		}
		
		fputs($this->smtp_conn,"QUIT" . $br);
		fclose($this->smtp_conn);
	}
}
?>