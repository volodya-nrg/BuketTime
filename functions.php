<?php
session_start();

ini_set("display_errors",	"On");
ini_set("error_log", 		$_SERVER['DOCUMENT_ROOT'] . "/_errors.log");
ini_set("default_charset",	"utf-8");

define("HOST", "https://" . $_SERVER['SERVER_NAME']);
//define("HOST", 				"http://" . $_SERVER['SERVER_NAME']);
define("LIMIT_SMALL", 		10);
define("NAME_COOKIE", 		"test");
define("PRODUCT", 			"test");
define("CHRONOPAY_KEY", 	"test");
define("EMAIL_CREATOR", 	"volodya-nrg@mail.ru");
define("SUPPORT", 			"support@bukettime.ru");
define("EMAIL_PASS", 		"test");
define("TEL", 				"123123123");

if(!isset($_SESSION["cart"])) 
	$_SESSION["cart"] = array();

function myMysql(){
	mysql_connect("localhost", "bukettim", "test") or die("Could not connect MYSQL");
	mysql_select_db("bukettim_almira") or die("Could not connect DB");
	//mysql_connect("localhost", "root", "test") or die("Could not connect MYSQL");
	//mysql_select_db("bukettime") or die("Could not connect DB");
	mysql_query("set names 'utf8'");
}
function clearText($str){
	$trans = array("~" => "", "`" => "", "!" => "", "$" => "", ";" => "", "%" => "", ":" => "", "^" => "", "?" => "", "*" => "", "=" => "", "{" => "", "}" => "", "|" => "", "\\" => "", "/" => "", "\"" => "", "<" => "", ">" => "", "  " => " ");
	$str = mb_convert_case($str, MB_CASE_LOWER, "UTF-8");
	$str = strip_tags($str);
	$str = strtr($str, $trans);
	$str = trim($str);
	return $str;
}
function clearBigText($str){
	$trans = array("~" => "", "`" => "", "$" => "", "^" => "", "*" => "", "{" => "", "}" => "", "|" => "", "\\" => "", "/" => "", "\"" => "", "<" => "", ">" => "", "  " => " ");
	$str = strip_tags($str);
	$str = strtr($str, $trans);
	$str = trim($str);
	return $str;
}
function clearBigTextLight($str){
	$temp = strip_tags($str, "<a><strong>");
	return trim(strtr($temp, array("~"=>"","`"=>"","$"=>"","^"=>"","{"=>"","}"=>"","|"=>"","\\"=>"","  "=>" ")));
}
function clearTel($tel){
	$output = "";
	for($i=0; $i<strlen($tel); $i++){
		if(is_numeric($tel[$i]) && $tel[$i]>=0 && $tel[$i]<=9)
			$output .= $tel[$i];
	}
	return $output;
}
function cutText($text, $simbols){
	$text = strip_tags($text);
	$text = trim($text);
	
	$text = iconv("utf-8", "cp1251", $text);
	if(strlen($text) > $simbols) $text = substr($text, 0, $simbols);
		
	$text = iconv("cp1251", "utf-8", $text);
	return $text;
}
function checkEmail($email){
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function checkCookie(){
	if( !empty($_COOKIE[NAME_COOKIE]) && !isset($_SESSION["user"]) ){
		$ar = explode("|", $_COOKIE[NAME_COOKIE]);
		$email = $ar[0];
		$timestamp = $ar[1];
		$key = $ar[2];
		$a = $timestamp - time();
		
		if( ($email != "") && ($timestamp != "") && ($key != "") && is_numeric($a) && ($a>0) ){
			$result = getOneSqlQuery('SELECT key_cookie FROM users WHERE email="' . $email . '"');
			
			if($result == $key) $_SESSION["user"] = getOneSqlQuery('SELECT id FROM users WHERE email="' . $email . '"');
		}
	}
}
function createPass(){
	$chars = "0123456789"; 
	$max = 6; 
	$size = strlen($chars)-1; 
	$password = null ; 
	while($max--) 
		$password.=$chars[rand(0,$size)];
	return $password;
}
function getOneSqlQuery($query){
	$q = mysql_query($query);	
	$q = mysql_fetch_array($q);
	return $q[0];
}
function changeEnMonthToRus($val){
	if( !$val || ($val>12) ) return;
	$val--;
	$month = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
	return $month[$val];
}
function image_resize($file, $max_param){
	if(!is_file($file)) return false;
	$aParam = getimagesize($file);
	$srcW = $aParam[0];
	$srcH = $aParam[1];
	$srcType = strtolower($aParam[2]);
	if($srcType != 2) 
		return false;
	if( ($srcW <= $max_param) && ($srcH <= $max_param) ) 
		return false;
	
	$src  = imagecreatefromjpeg($file);
	$razdnica = $srcW - $srcH;
	if($razdnica > 0){
		// ширина больше чем высота
		$kof = $srcW/$max_param;
		$w = $max_param;
		$h = round($srcH/$kof);
	}
	elseif($razdnica < 0){
		// высота больше чем ширина
		$kof = $srcH/$max_param;
		$h = $max_param;
		$w = round($srcW/$kof);
	}else{
		// ширина и высота равны
		$w = $max_param;
		$h = $max_param;
	}

	$dest = imagecreatetruecolor($w, $h);
	imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $srcW, $srcH);
	imagejpeg($dest, $file, 100);
	imagedestroy($src);
	imagedestroy($dest);
	clearstatcache();
	return true;
}
function eachImages($aImages, $id, $last_int, $path){
	for($i=0; $i<count($aImages["name"]); $i++){
		$ext = strtolower(substr( strrchr($aImages["name"][$i], "."), 1 ));
		$name = strtolower( substr( $aImages["name"][$i], 0, strrpos($aImages["name"][$i], ".")) );
		if( ($ext == "jpg") || ($ext == "jpeg") ){
			
			$name = $id . "_" . $last_int . ".jpg";
			
			if( move_uploaded_file($aImages["tmp_name"][$i], $path . "/" . $name) ) 
				image_resize($path . "/" . $name, 380);
			
			$last_int++;
		}
	}
}
function getExtAndFilename($filename){
	$path_info = pathinfo($filename);
	$ext = strtolower($path_info['extension']);
	$file = basename($path_info['basename'], "." . $ext);
	return array("name" => $file, "ext" => $ext);
}
function getExtension($filename){
	$path_info = pathinfo($filename);
	return strtolower($path_info['extension']);
}
function myStrlen($str){
	return strlen(mb_convert_encoding($str, "cp1251", "utf-8"));
}
function myExplode($separator, $str){
	$temp1 = explode($separator, $str);
	$temp2 = array();
	
	foreach($temp1 as $val)
		if($val != "")
			$temp2[] = trim($val);
	
	return $temp2;
}
function aGetInputFiles($files){
	$aRes = array();
	
	for($i=0; $i<count($files['tmp_name']); $i++){
		$aRes[] = array("tmp_name"	=> $files['tmp_name'][$i],
						"name"		=> $files['name'][$i],
						"size"		=> $files['size'][$i]);		
	}
	
	return $aRes;
}

myMysql();
checkCookie();
?>