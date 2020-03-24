<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("Подписка на цветы", "Подписка на цветы, регулярная доставка цветов, доставка цветов, цветы в офис, цветок в офис, озеленение офисов"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <?= $Sait->getTopLinkDefault() ?>
    <hr>
    <img class=floatLeft style="margin-left:10px" width=720 height=525 src="/img.php?f=img/service.jpg&w=720&h=525">
	
    <div align=center class=div_big_link>
    	<div class=valign_mid>
    		<a href="/collections">
    			подписаться на
                <br>
                <font>цветы</font>
    		</a>
        </div>
    </div>
    <div align=center class=div_big_link>
    	<div class=valign_mid>
    		<a href="/faq.php">
    			вопросы и
                <br>
                <font>ответы</font>
    		</a>
        </div>
    </div>
    <div align=center class=div_big_link>
    	<div class=valign_mid>
    		<a href="/deliver">
    			куда мы
                <br>
                <font>доставляем?</font>
    		</a>
        </div>
    </div>
    <div align=center class=div_big_link style="border-bottom-width:0px">
    	<div class=valign_mid>
    		<a href="/pressa">
    			пресса
                <br>
                <font>о нас</font>
    		</a>
        </div>
    </div>
    <a class="btn btn_green" href="/collections" style="width:200px; position:relative; top: -62px; left: 486px">
    	подписаться
    </a>
    
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>