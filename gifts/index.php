<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
	<?
		if(!empty($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"]>0 && getOneSqlQuery('SELECT COUNT(id) FROM gifts WHERE id='.$_GET["id"]))
			$gift_id = $_GET["id"];
		else
			$gift_id = false;	
		
		if($gift_id){
			$q = mysql_query('SELECT * FROM gifts WHERE id=' . $gift_id);
			$data = mysql_fetch_array($q);
			
			$title 			= $data['name'];
			$title_page 	= $data['title_page'];
			$description 	= $data['title_page'];
			$keywords 		= $data['keywords'];
		}
		else{
			$title 			= "Цветы для офиса | Цветок для офиса";
			$title_page 	= "Цветы для офиса | Цветок для офиса";
			$description 	= "Цветы для офиса | Цветок для офиса";
			$keywords 		= "Цветы для офиса, цветок для офиса, озеленение офисов, доставка цветов,  заказ цветов, доставка букетов, купить цветы, интернет магазин цветов, цветы доставка, заказать цветы, горшечные растения, горшечные цветы";
		}
		
		echo $Sait->getHead($title_page, $keywords, $description);
	?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    
    <? if($gift_id): ?>
    	<? if(isset($_SESSION["admin"])): ?>
        	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:-30px"
                 onClick="Admin.getGift(<?= $data['id'] ?>)" >
            <img class=admin_img src="/img/admin_trash.png" width=20 height=20 style="margin-top:-30px; margin-left:40px"
                 onClick="Admin.delGift(<?= $data['id'] ?>)" >
        <? endif; ?>
		
		<?
			$title 		= $data['name'];
			$cost 		= $data['cost'];
			$discount 	= $data['description'];
			$desc 		= $data['about'];
		?>
		<div style="margin-left:10px">
			<h5>
            	<a href="/gifts" style="color:#558401; text-decoration:none">разовая доставка подарков</a>
				&gt;
				<?= $title ?>
            </h5>
			<? if(empty($_SESSION["user"])): ?>
				<a class="btn btn_green" href="/reg.php" 
				   style="width:200px; position:absolute; margin-left:756px; margin-top:-35px">вступить в клуб</a>
			<? endif; ?>
		</div>
		<hr>
        <table cellpadding=0 cellspacing=0 style="margin-left:10px">
        	<tr>
            	<td align=left valign=top width=490>
					<? $pic = (is_file($data['id'] . ".jpg"))? "gifts/" . $data['id'] . ".jpg": "img/no_foto.jpg"; ?>
                    <img alt="<?= $title ?>" title="<?= $title ?>" width=450 height=450 style="border-bottom:#ccc solid 1px;" 
                         src="/img.php?f=<?= $pic ?>&w=450&h=450">
                    <br>
                    <br>
                    <?= $Sait->getSocial() ?>
                </td>
                <td align=left valign=top width=260 >
                    <h3><?= $title ?></h3>
                    <br> 
                    <strong><font size="+1">Розничная цена: <?= $cost ?> руб.</font></strong>
                    <br>
                    <br>
                    <strong><font color=red>Цена для членов Клуба: <?= ($cost - (($Sait->discount * $cost)/100)) ?> руб.</font></strong>
                    <br>
                    <br>
                    <strong style="color:#558401"><font size="+1">Бесплатная доставка!</font></strong>
                    <br>
                    <br>
                    <div>
                        <?= $desc ?>
                    </div>
                    <br>
                    <a class="btn btn_green" href="/cart.php?gift=<?= $data['id'] ?>&count=1" style="width:215px">добавить в корзину</a>
                </td>
                <td align=left valign=top width=200 style="padding-left:10px;">
                	<?
						$q = mysql_query('SELECT * FROM dop_gifts WHERE gift=' . $data['id']);
						$dop_gift = mysql_fetch_array($q);
						$aGifts = array();
						if(!empty($dop_gift['id'])){
							$aGifts = explode("|", $dop_gift['dop_gifts']);	
						}
					?>
                    <? if(count($aGifts)): ?>
                        С этим товаром еще покупают:
                        <div class=arrow_vert mytag=top style="margin-top:10px" onClick="slideDopGifts($(this))"></div>
                        <div class=window_for_dop_gift>
                        	<div id=indicator style="position:relative; top:0px">
								<?	
                                    $i=0;
                                    foreach($aGifts as $val){
                                        $q = mysql_query('SELECT * FROM gifts WHERE id=' . $val);
                                        $temp = mysql_fetch_array($q);
                                        echo $Sait->getDopGift($temp['id'], $temp['name'], $temp['cost']);
                                        $i++;
                                    }
                                ?>
                            </div>
                        </div>
                        <div class=arrow_vert mytag=bot onClick="slideDopGifts($(this))"></div>
                    <? endif; ?>
                    
					<script>
					<? if($i<4): ?>
						$("div.arrow_vert").addClass("selected");
					<? else: ?>
						$("div.arrow_vert[mytag=bot]").addClass("selected");
					<? endif; ?>
					</script>
                    <br>
                    <strong><a href="/gifts">Посмотреть другие варианты</a></strong>
                </td>
            </tr>
        </table>
    <? else: ?>
		<? if(isset($_SESSION["admin"])): ?>
        	<img class=admin_img src="/img/admin_file_plus.png" width=20 height=20 style="margin-top:-30px" 
            	 onClick="Admin.getGift()">
        <? endif; ?>
        
        <div style="margin:0px 10px">
            <h5 class=floatLeft>разовая доставка подарков</h5>
            <h5 class=floatRight>всегда бесплатная доставка</h5>
        </div>
        <?= $Sait->getBr(1) ?>
        <hr>
		
		<? if(empty($_SESSION["user"])): ?>
        	<a class="btn btn_green floatRight" href="/reg.php" style="width:200px; margin-right:10px">вступить в клуб</a>
        <? endif; ?>
       
        <?  $q = mysql_query('SELECT * FROM gifts ORDER BY `time` DESC');
            $i=0;
            $gifts = "";
            while($row = mysql_fetch_array($q)){
				$pic = is_file($_SERVER['DOCUMENT_ROOT'] . "/gifts/" . $row["id"] . ".jpg")? 'gifts/' . $row["id"] . '.jpg': "img/no_foto.jpg";
                $gifts .= '<div class=div_gift_one_cell>
                                <a class=a_gift_one_cell href="/gifts/?id=' . $row["id"] . '">
                                    <img align=middle alt="' . $row["name"] . '" title="' . $row["name"] . '" width=300 height=300 
                                         src="/img.php?f=' . $pic . '&w=300&h=300">
                                    <div>
                                        <h4>' . $row["name"] . '</h4>
                                        <span>' . strip_tags($row["about"]) . '</span>
                                        <font>Розничная цена: ' . $row["cost"] . ' руб.</font>
                                        <font color=red>Цена для членов Клуба: ' . ($row["cost"] - (($Sait->discount*$row["cost"])/100)) . ' руб.</font>    
                                    </div>
                                </a>
                                <a class="btn btn_black btn_buy_now" href="/cart.php?gift=' . $row["id"] . '&count=1">купить</a>
                          </div>';
                $i++;
            }
        ?>
        <?= $Sait->getBr(12); ?>
        <div>
            <div><font id=font_total_gift style="margin-left:10px;"><?= $i ?></font> подарков</div>
            <hr class=hr_2>
        </div>
        <?= $Sait->getBr(7); ?>
        
        <div align="center">
        	<?= $gifts ?>
        </div>
        
    <? endif; ?>
	
	<?= $Sait->getBr(50); ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>