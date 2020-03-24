<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();

$key = false;
if(!empty($_GET['id']) && is_numeric($_GET['id']) && $_GET['id']>0){
	$res = getOneSqlQuery('SELECT COUNT(*) FROM collections WHERE id=' . $_GET['id']);
	if($res) 
		$key = $_GET['id'];	
}

$collection = array();
$i=0;
$q = mysql_query('SELECT * FROM collections ORDER BY id');
while($row = mysql_fetch_array($q)){
	$collection[$i]['id'] 		= $row['id'];
	$collection[$i]['name'] 	= $row['name'];
	$collection[$i]['cost'] 	= $row['cost'];
	$collection[$i]['discount'] = $row['discount'];
	$collection[$i]['about'] 	= $row['about'];
	$i++;
}

if($key){
	$collection_items = array();
	$files = scandir(".");
	foreach($files as $val)
		if(is_file($val) && (substr($val, -4)==".jpg") && (substr($val, 0, strpos($val, "_")) == $key) )
			$collection_items[] = $val;
}
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: Доставка букетов", "доставка цветов, доставка букетов, доставка цветов по москве, заказ цветов, купить цветы, магазин цветов, интернет магазин цветов, цветы доставка, заказать цветы, заказать доставку цветов, доставка цветов недорого, цветы доставка москва недорого, недорогие букеты, продажа цветов, цветы для офиса"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait(); ?>
    
    <? if(isset($_SESSION["admin"])): ?>
		<? if($key): ?>
            <img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:-30px"
                 onClick="Admin.getCollections(<?= $key ?>)" >
            <img class=admin_img src="/img/admin_trash.png" width=20 height=20 style="margin-top:-30px; margin-left:40px"
                 onClick="Admin.delCollection(<?= $key ?>)" >
        <? else: ?>
            <img class=admin_img src="/img/admin_file_plus.png" width=20 height=20 style="margin-top:-30px" 
                 onClick="Admin.getCollections()">
        <? endif; ?>
    <? endif; ?>
 
    <div style="margin-left:10px">
        <? foreach($collection as $key_0=>$val): ?>
        	<? if($key):?>
            	<a class=a_under_top_link href="/collections/?id=<?= $val['id'] ?>"><?= $val['name'] ?></a>
            <? else: ?>
            	<a class=a_under_top_link mytag=<?= $val['id'] ?> onClick="showSlideOneFoto($(this), <?= $val['id'] ?>)"><?= $val['name'] ?></a>
            <? endif; ?>
        <? endforeach; ?>
    </div>
    <hr>
    
    <?= $Sait->getBr(20) ?>
        
    <div class=div_window_for_big_pic>
        <div id=div_prev class=div_prev_next onClick="showSlideOneFoto($(this), 'prev')"></div>
        <div id=div_next class=div_prev_next onClick="showSlideOneFoto($(this), 'next')"></div>
        
        <div id=div_place_all_big_foto>
        	<? if($key): ?>
            	<? foreach($collection_items as $val): ?>
                    <div class="floatLeft div_big_tovar" 
                           style="background:white url(/collections/<?= $val ?>) center center no-repeat;">
                        <div mytag=title>
                            <a class=a_big style="text-decoration:none; cursor:default"><?= $collection[$key-1]['name'] ?></a>
                        </div>
                        <div mytag=desc>
                            <div class=white_shadow_text>
                                <?= $collection[$key-1]['about'] ?>
                            </div>
                            <a class="btn btn_green" href="/schedle.php?collection=<?= $collection[$key-1]['id'] ?>" 
                               style="width:200px; margin-left: 766px">подписаться</a>
                        </div>
                    </div>
                <? endforeach; ?>
            <? else: ?>
				<? foreach($collection as $val): ?>
                    <div class="floatLeft div_big_tovar" 
                           style="background:white url(/collections/<?= $val['id'] ?>_0.jpg) center center no-repeat;">
                        <div mytag=title>
                            <a class=a_big href="/collections/?id=<?= $val['id'] ?>"><?= $val['name'] ?></a>
                            <a class=a_see_more_examples href="/collections/?id=<?= $val['id'] ?>">
                                просмотреть варианты&nbsp;&raquo;
                            </a>
                        </div>
                        <div mytag=desc>
                            <div class=white_shadow_text>
                                <?= $val['about'] ?>
                            </div>
                            <a class="btn btn_green" href="/schedle.php?collection=<?= $val['id'] ?>" 
                               style="width:200px; margin-left: 766px">подписаться</a>
                        </div>
                    </div>
                <? endforeach; ?>
            <? endif; ?>
        </div>
    </div>
    
	<? if($key): ?>
        <? foreach($collection_items as $key_0=>$val): ?>
            <div class=div_icon_small mytag=<?= ($key_0+1) ?> onClick="showSlideOneFoto($(this), <?= ($key_0+1) ?>)">
                <img width=100 height=100 src="/img.php?f=collections/<?= $val ?>&w=100&h=100">
            </div>
        <? endforeach; ?>
    <? else: ?>
        <? foreach($collection as $val): ?>
            <div class=div_icon_small mytag=<?= $val['id'] ?> onClick="showSlideOneFoto($(this), <?= $val['id'] ?>)">
                <img width=100 height=100 src="/img.php?f=collections/<?= $val['id'] ?>_0.jpg&w=100&h=100">
            </div>
        <? endforeach; ?>
    <? endif; ?>
    
	<?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>