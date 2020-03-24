<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                   
header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");         
header("Cache-Control: post-check=0, pre-check=0", false);           
header("Pragma: no-cache");                                           
header("Content-Type:image/jpeg");

session_start();

function createPass(){
	$chars 		= "abdefhknrstyz23456789"; 
	$length 	= rand(4,7); 
	$numChars 	= strlen($chars);
	$str 		= "";
	
	// Генерируем код
	for ($i = 0; $i < $length; $i++)
		$str .= substr($chars, rand(1, $numChars) - 1, 1);
	
	$array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
	srand((float)microtime()*1000000); // Изменяет начальное число генератора псевдослучайных чисел
	shuffle($array_mix);
	
	return implode("", $array_mix);
}

$code 		= createPass();
$width		= 100;
$height		= 25;
$linenum 	= rand(3, 7);

$im 	= imagecreate(100,25);

$black 	= imagecolorallocate($im, 0, 0, 0);		 //black
$grey 	= imagecolorallocate($im, 221, 221, 221);//#ccc
$grey2 	= imagecolorallocate($im, 153, 153, 153);//#999
$white 	= imagecolorallocate($im, 255, 255, 255);//white

imagefill($im, 0, 0, $grey);

// Рисуем линии на подстилке
for ($i=0; $i<$linenum; $i++){
	$color = imagecolorallocate($im, rand(150, 255), rand(150, 200), rand(150, 255)); // Случайный цвет c изображения
	imageline($im, 0, rand(0, 25), 100, rand(0, 25), $color);
}
$colorText = imagecolorallocate($im, rand(0, 200), 0, rand(0, 200)); // Опять случайный цвет. Уже для текста.

// Накладываем текст капчи				
$x = rand(3, 30);
for($i = 0; $i < strlen($code); $i++) {
	$x 		+= 8;
	$y  	= rand(3, 5);
	$letter	= substr($code, $i, 1);
	
	imagestring($im, 5, $x, $y+1, $letter, $white);
	imagestring($im, 5, $x, $y, $letter, $colorText);
}

imagejpeg($im, NULL, 100);
imagedestroy($im);

$_SESSION['antibot'] = $code;
?>
