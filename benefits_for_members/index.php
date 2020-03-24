<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
	
	$data = $Sait->getPage(5);
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
    
    <img class=floatLeft style="margin-left:10px" src="/img/benefits_for_members.jpg">
    
    <div class="div_dop_page floatRight">
    	<div class=valign_mid>
        	<? if(isset($_SESSION["admin"])): ?>
            	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:-30px; margin-left:30px" 
                	 onClick="Admin.getBenefitsForMembers()" >
            <? endif; ?>
					
			<?= $data['content'] ?>
        </div>
    </div>
    
    <? if(empty($_SESSION["user"])): ?>
    	<a class="btn btn_green" href="/reg.php" style="width:200px; position:absolute; margin-left:756px; margin-top:-35px">вступить в клуб</a>
    <? endif; ?>
		
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>