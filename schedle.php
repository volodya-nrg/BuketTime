<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
	
	if(empty($_SESSION["user"])){
		header("Location:/reg.php");
		exit;
	}
	if(empty($_GET["collection"])){
		header("Location:/collections/");
		exit;
	} 
	if( !getOneSqlQuery('SELECT COUNT(*) FROM collections WHERE id=' . mysql_real_escape_string($_GET["collection"])) ){
		header("Location:/collections/");
		exit;
	}
	
	$collection_id = $_GET["collection"];
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead(); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <hr>
    
    <div align=center>
    	<?
			$img = scandir("collections/");
			$obj = array();
			foreach($img as $val){
				if(is_file("collections/" . $val) && (substr($val, -4)==".jpg") && (substr($val, 0, strpos($val, "_")) == $collection_id) ){
					$obj[] = $val;	
				}
			}
			
			$getAllPic = "";
			foreach($obj as $value){
				$temp = substr($value, 0, -4);
				$getAllPic .= '<span class=span_schedle_img_icon mytag=' . $temp . '>
									<img border=0 hspace=0 vspace=0 width=88 height=85 src="img.php?f=collections/' . $value . '&w=90&h=90" />
									<input type=checkbox mytag=schedle_img_icon value=' . $temp . '>
							   </span>';
			}
		?>
        <div align=justify style="width:800px">
        	<br>
            <h6 style="text-transform:none">Мы доставляем цветы по Москве, в пределах МКАД</h6>
            <br>
            <hr>
            <div class=div_schelde>
            	<div>
                	Как часто присылать Вам коллекцию?
                </div>
                <div>
                    <button mytag=how_often class=mybtn value=4 onClick="changeSchedleBtn($(this), 'how_often')">1 раз в неделю</button>
                    <button mytag=how_often class=mybtn value=2 onClick="changeSchedleBtn($(this), 'how_often')">2 раза в месяц</button>
                    <button mytag=how_often class=mybtn value=1 onClick="changeSchedleBtn($(this), 'how_often')">1 раз в месяц</button>
                    <script>
                    	$("button[mytag=how_often]:first-child").click();
                    </script>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	По каким дням осуществлять доставку?
                </div>
                <div>
                	<button mytag=on_what_days class=mybtn value=6 onClick="changeSchedleBtn($(this), 'on_what_days')">суббота</button>
                    <button mytag=on_what_days class=mybtn value=7 onClick="changeSchedleBtn($(this), 'on_what_days')">воскресенье</button>
                    <script>
                    	$("button[mytag=on_what_days]:first-child").click();
                    </script>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Время доставки :
                </div>
                <div>
                	<button class=mybtn_select disabled>9:00 - 23:00</button>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Какие букеты Вы бы хотели получить * :
                </div>
                <div>
                	<?= $getAllPic ?>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Пометка :
                </div>
                <div>
                	Если Вы заказали букеты по цене от 1990 руб., то мы предоставим вазу в подарок!
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Текст для открытки :
                </div>
                <div>
                	<textarea id=schedle_text_on_postcard></textarea>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Адрес доставки * :
                </div>
                <div>
                	<input id=schedle_address type=text maxlength=255>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Ближайшее метро :
                </div>
                <div>
                	<select id=schedle_metro>
                    	<option value=0></option>
                    	<?= $Sait->getOptionsMosMetro(); ?>
                    </select>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Номер телефона * :
                </div>
                <div>
                	<input id=schedle_tel type=text maxlength=15 style="width:160px">
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Дополнительная информация от Вас:
                </div>
                <div>
                	<textarea id=schedle_dop_info></textarea>
                </div>
            </div>
            <div class=div_schelde>
            	<div>
                	Способ оплаты:
                </div>
                <div>
                	<input type=radio name=schedle_oplata value=1 checked > банковской картой (рекуррентные платежи)<br>
                    <input type=radio name=schedle_oplata value=2 > наличными
                </div>
            </div>

            <?= $Sait->getBr(20) ?>
            <div align=left>
            	Доставка бесплатная!<br>
                * поля обязательны для заполнения
            </div>
    		<?= $Sait->getBr(20) ?>        
            <a class="btn btn_green floatRight" style="width:150px;" onClick='collectDataFromSchedle($(this), <?= $collection_id ?>)'>
            	<img class=img_preloader_small src="/img/preloader_small.png">
                оформить
            </a>
        </div>
    </div>
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>