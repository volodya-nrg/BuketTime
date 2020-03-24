<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: Уведомление о платеже."); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <?= $Sait->getTopLinkDefault() ?>
    <hr>
    
    <div align=center>
        <div align=justify style="width:800px">
        	<center style="background-color:red">Ошибка: платёж не выполнен!</center>
        </div>
    </div>
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>