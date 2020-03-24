<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
	
	$data = $Sait->getPage(8);
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
    
    <img class=floatLeft src="/img.php?f=img/about.jpg&w=500&h=500">
    
    <div class="div_dop_page floatRight">
    	<div class=valign_mid>
        	<? if(isset($_SESSION["admin"])): ?>
            	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:-30px; margin-left:30px" 
                	 onClick="Admin.getAboutUs()" >
            <? endif; ?>
					
			<?= $data['content'] ?>
        </div>
    </div>
    
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>