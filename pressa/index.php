<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: Новости и статьи"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <?= $Sait->getTopLinkDefault() ?>
    <hr>
    <?= $Sait->getBr(15) ?>
    <?
		$q = mysql_query('SELECT * FROM pressa ORDER BY `time` DESC');
		$otziv = "";
		$total=0;
		while($row = mysql_fetch_array($q)){
			$otziv .= '<div class=div_pressa>';
				if(isset($_SESSION["admin"]))
					$otziv .= '<img class=admin_img src="/img/admin_engine.png" width=10 height=10 
								   style="margin-top:0px"
								   onClick="Admin.getPressa(' . $row["id"] . ')" >
							   <img class=admin_img src="/img/admin_trash.png" width=10 height=10 
							   	   style="margin-top:0px; margin-left:30px"
								   onClick="Admin.delPressa(' . $row["id"] . ')" >';
				  
				  	$pic = (is_file($_SERVER['DOCUMENT_ROOT'] . "/pressa/" . $row['id'] . ".jpg"))? "pressa/" . $row['id'] . ".jpg": "img/no_foto.jpg";
					  $otziv .= '<div>
								<a href="' . $row["url"] . '" target="_blank">
									<img mytag=true width=201 height=150 src="/img.php?f=' . $pic . '&w=201&h=150">
								</a>
							</div>
							<font>' . date("F j, Y", strtotime($row["time"])) . '</font>
							<div mytag=true>
								' . $row["about"] . '
							</div>
							<a href="' . $row["url"] . '" target="_blank">далее</a>
						</div>';
			$total++;
		}
	?>
	<? if(isset($_SESSION["admin"])): ?>
    	<img class=admin_img src="/img/admin_file_plus.png" width=20 height=20 style="margin-top:-30px" onClick="Admin.getPressa()">
    <? endif; ?>
		
    <div style="margin:0px 10px">
    	Отзывов <?= $total ?>
    </div>
    <?= $otziv ?>
    
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>