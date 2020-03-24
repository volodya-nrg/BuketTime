<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();

$data = $Sait->getPage(1);
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
        
        <div align=center>
            <div align=justify style="width:800px">
             <? if(isset($_SESSION["admin"])): ?>
            	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:0px; margin-left:-30px" 
                	 onClick="Admin.getAgreement()" >
             <? endif; ?>
					
			 <?= $data['content'] ?>
            </div>
        </div>
        <?= $Sait->getBr(50) ?>
    </div>	
	
	<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>