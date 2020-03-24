<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();

$data = $Sait->getPage(4);
?>
<!DOCTYPE HTML>
<head>
	<?= $Sait->getHead($data["title"], $data["meta_keywords"], $data["meta_desc"]); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <?= $Sait->getTopLinkDefault() ?>
    <hr>
   
    <img class=floatLeft style="margin-left:10px" src="/img.php?f=img/business.jpg&w=500&h=500">
    
    <div class="div_dop_page floatRight">
    	<div class=valign_mid>
        	<? if(isset($_SESSION["admin"])): ?>
            	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:-30px;" 
                	 onClick="Admin.getBusiness()" >
            <? endif; ?>
					
			<?= $data['content'] ?>
        </div>
    </div>
    
    <a class="btn btn_green floatRight" href="/collections/" style="width:200px; margin-right:10px">подписаться</a>
    
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>