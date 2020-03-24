<?php
header("Content-type: image/jpeg");

if(!isset($_REQUEST['f'])) exit();

$src  = imagecreatefromjpeg($_REQUEST['f']);

$w_src = imagesx($src); 
$h_src = imagesy($src);

$myW = $_REQUEST['w'];
$myH = $_REQUEST['h'];
// узнаём на сколько нужно смаштабировать картинку необходимую для изменения
if($myW > $myH){
	if( ($w_src-$h_src) > 0 ){
		// тут ширина больше чем высота
		$w_out = $myW;
		$mid_val = $w_src/$myW;
		$h_out = $h_src/$mid_val;
		
	}elseif(($w_src-$h_src) <= 0){
		// тут высота больше чем ширина
		$h_out = $myH;
		$mid_val = $h_src/$myH;
		$w_out = $w_src/$mid_val;
	}else{
		$h_out = $myH;
		$w_out = $myW;
	}
}elseif($myW < $myH){
	if( ($w_src-$h_src) > 0 ){
		// тут ширина больше чем высота
		$w_out = $myW;
		$mid_val = $w_src/$myW;
		$h_out = round($h_src/$mid_val);
	}elseif(($w_src-$h_src) < 0){
		// тут высота больше чем ширина
		$h_out = $myH;
		$mid_val = $h_src/$myH;
		$w_out = round($w_src/$mid_val);
	}else{
		$h_out = $myH;
		$w_out = $myW;
	}
}else{
	if( ($w_src-$h_src) > 0 ){
		// тут ширина больше чем высота
		$w_out = $myW;
		$mid_val = $w_src/$myW;
		$h_out = $h_src/$mid_val;
	}elseif(($w_src-$h_src) < 0){
		// тут высота больше чем ширина
		$h_out = $myH;
		$mid_val = $h_src/$myH;
		$w_out = $w_src/$mid_val;
	}else{
		$h_out = $myH;
		$w_out = $myW;
	}
}

$dest = imagecreatetruecolor($myW, $myH);

if(isset($_REQUEST['bg']) && ($_REQUEST['bg'] == "black") ) $bg = imagecolorallocate($dest,0,0,0);
elseif(isset($_REQUEST['bg']) && ($_REQUEST['bg'] == "white") ) $bg = imagecolorallocate($dest,255,255,255);
else $bg = imagecolorallocate($dest,255,255,255);

imagefill($dest, 0, 0, $bg);
imagecopyresampled($dest, $src, (($myW - $w_out)/2), (($myH - $h_out)/2), 0, 0, $w_out, $h_out, $w_src, $h_src);
	
if( isset($_REQUEST['q']) && $_REQUEST['q']>0 && $_REQUEST['q']<=100 ) imagejpeg($dest, '', $_REQUEST['q']);
else imagejpeg($dest, '', 90);

imagedestroy($src);
imagedestroy($dest);

?>