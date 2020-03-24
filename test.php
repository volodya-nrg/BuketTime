<?
class Encryption {
	private $key; // 12345
	private $iv;
	
	public function __construct($key) {
        $this->key = hash('sha256', $key, true);
		$this->iv = mcrypt_create_iv(32, MCRYPT_RAND);
	}
	
	public function encrypt($value) {
		return strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $value, MCRYPT_MODE_ECB, $this->iv)), '+/=', '-_,');
	}
	
	public function decrypt($value) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, base64_decode(strtr($value, '-_,', '+/=')), MCRYPT_MODE_ECB, $this->iv));
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>test</title>
</head>

<body>
<?
	function send($to){
		$uniq		= md5(uniqid(rand(),true));
		$subject 	= array("Заказ товара с centerdver.com", "Вызвать замерщика", "Не дозвонились?");
		$domen		= array("mail.ru", "yandex.ru", "gmail.ru", "rambler.ru");
		$text		= "The Roman Empire had a huge task in front of them while it was first starting out and while it was becoming a dominant dynasty in the early civilizations. The main problem that the book Discovering the Global Past points out is how the Roman Empire found itself growing a little too quickly. The Roman Empire started out very small on the Tiber River and grew abruptly without warning. Before they knew it, they were not a small power but now one with a great number of people with a great number of cultures intertwined within the Roman Empire. They were now spread over an immense portion of land with much power coming with it. The question now is how they are going to be able to handle all of this power and continue to dominate the world without any blueprints to help them out from previous dynasties because it had never been done before. We now have some evidence to help understand this and to show how it did happen. So courtesy of the Discovering the Global Past they have seven pieces of evidence that will help prove that the Roman Empire could handle the challenge of taking on the mastery of world power.";
		
		shuffle($subject);
		shuffle($domen);
		
		$text = mb_convert_case($text, MB_CASE_LOWER, "UTF-8");
		$text = preg_replace("/[^a-z]/", "", $text);
		$len = rand(5,15);
		$login = substr($text, rand(0, strlen($text)-$len), $len);
		
		mail($to, current($subject), $uniq, "From: ".$login."@".current($domen));	
	}
	
	for($i=0; $i<20; $i++)
		send("info@centrdver.com");
	
	//send("umerovvladimir@yandex.ru");
?>	
</body>
</html>