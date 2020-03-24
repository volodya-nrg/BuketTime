<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
	
	if(!empty($_GET["gift"]) && is_numeric($_GET["gift"]) && $_GET["gift"]>0 ){
		if(getOneSqlQuery('SELECT COUNT(*) FROM gifts WHERE id=' . $_GET["gift"])){
			$count = 1;
			if( isset($_GET["count"]) && ($_GET["count"]>=0) && ($_GET["count"]<1000) ) $count = $_GET["count"];
			
			$id = $_GET["gift"];
			
			$key = false;
			foreach($_SESSION["cart"] as $key=>$val){
				if($key == $id){
					$key = true; 
					break;
				} 	
			}
			
			if($key){
				if($count == 0) unset($_SESSION["cart"][$id]);
				else $_SESSION["cart"][$id] += $count;	
			}else{
				$_SESSION["cart"][$id] = $count;	
			}
		}
	}
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: корзина"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
	<div style="margin-left:10px">
    	<h5 class=floatLeft>обзор корзины</h5>
    </div>
    <?= $Sait->getBr(1) ?>
    <hr>
    
    <div class=floatLeft style="width:800px; display:table;">
    	<?
			$html = "";
			if(count($_SESSION["cart"])){
				foreach($_SESSION["cart"] as $key=>$value){
					$q = mysql_query('SELECT * FROM gifts WHERE id=' . $key);
					$data = mysql_fetch_array($q);
					
					$name 		= $data["name"];
					$cost 		= $data["cost"];
					$discount 	= $data["discount"];
					$about 		= $data["about"];
					
					// если есть скидка для всех, то отнимаем ее
					//if($discount) $cost -= round(($discount * $cost)/100);
					//  для зарегистрированных пользователей скидка в 20%
					if(!empty($_SESSION["user"])) $cost -= round(($Sait->discount * $cost)/100);
					
					$html .= $Sait->blockCart($key, $value, $cost, $name, $about);
				}	
			}
		?>
        
        <? if($html != ""): ?>
        	<strong class=strong_title_tbl style="width:110px;">общая цена</strong>
            <strong class=strong_title_tbl style="width:60px;">кол-во</strong>
            <strong class=strong_title_tbl style="width:90px;">цена за 1 шт.</strong>
            <hr style="margin-bottom:0px">
            <?= $html ?>
            
            <a class="btn btn_black floatRight" style="margin:10px 100px;" onClick="update_cart($(this))">
                <img class=img_preloader_small src="/img/preloader_small.png">
                пересчитать
            </a>
            
            <div style="display:table; background-color:#eeeeee; clear:both; width:inherit">
                <table class=floatRight width=200 style="margin:10px; text-align:right">
                    <tr style="font-weight:bold">
                        <td>
                            Промеж-я цена 
                        </td>
                        <td>
                            <?= $Sait->countCart(1) ?> руб.
                        </td>
                    </tr>
                    <tr style="font-weight:bold">
                        <td>
                            Доставка
                        </td>
                        <td>
                            0 руб.
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <hr>
                        </td>
                    </tr>
                    <tr style="font-weight:bold; color:#558401; font-size:1.2em">
                        <td>
                            ВСЕГО
                        </td>
                        <td>
                            <?= $Sait->countCart(1) ?> руб.
                        </td>
                    </tr>
                    <tr style="color:#666; font-size:0.8em; font-family:sans-serif">
                        <td colspan=2>
                            *Дополнительные скидки, если таковые имеются, применяются при оформлении заказа
                        </td>
                    </tr>
                </table>    
            </div>
            
        <? else: ?>
        	<strong style="margin-left:10px">Корзина пуста</strong>
        <? endif; ?>
    </div>
	
    <? if($html != ""): ?>
        <div id=div_checkout_now align=center class=floatRight style="border:#ccc solid 1px; padding:10px; width:148px">
            <table width=100% style="text-align:center; margin-bottom:20px">
                <tr style="font-weight:bold; color:#558401; font-size:1.2em">
                    <td align="left">
                        ВСЕГО
                    </td>
                    <td align="right">
                        <?= $Sait->countCart(1) ?> руб.
                    </td>
                </tr>
                <tr height=50 style="color:#666; font-size:0.8em; font-family:sans-serif">
                    <td colspan=2>
                        *Дополнительные скидки, если таковые имеются, применяются при оформлении заказа
                    </td>
                </tr>
            </table>
            <a class="btn btn_green" style="width:100%" onClick="orderCart()">оформить сейчас</a>
        </div>
    <? endif; ?>
    
    <?= $Sait->getBr(70) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>