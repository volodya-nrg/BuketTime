<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead(); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    
    <div style="margin:0px 10px">
    	<h5>Напомнить пароль</h5>
    </div>
    <?= $Sait->getBr(1) ?>
    <hr>
    <div style="margin:0px 10px; position:relative; top:-5px">
    	Введите адрес электронной почты и мы вышлем Вам пароль.
    </div>
    <?= $Sait->getBr(20) ?>
    <div align=center>
        <div align=justify style="width:800px">
        	
            <div align=center style="margin-bottom:10px">
            	<strong>Мэйл : </strong>
                <input id=send_mail type=text value="" style="width:480px; margin-left:10px" maxlength=50>
            </div>
            <div style="display:table; width:100%; text-align:center; padding-top:10px" >
            	<a class="btn btn_green" style="width:280px" onClick="sendPass($(this))">
                	<img class=img_preloader_small src="/img/preloader_small.png">
                    выслать пароль
                </a>
            </div>

        </div>
    </div>
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>