<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: Подписка на цветы | Регулярная доставка цветов", "доставка цветов,  доставка цветов по москве, заказ цветов, доставка букетов, купить цветы, магазин цветов, интернет магазин цветов, цветы доставка, заказать цветы, заказать доставку цветов, доставка цветов недорого, цветы доставка москва недорого, недорогие букеты, продажа цветов, цветы для офиса"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>
    
<div class=div_body>
    <!-- меняющиеся большие картинки-->
    <div style="height:490px;">
    	<?
			$path = $_SERVER['DOCUMENT_ROOT'] . "/img/big_photo_index";
			$img = scandir($path);
			$i=1;
			$full_pic = "";
			$radio_pic = "";
			
			foreach($img as $value){
				if(is_file($path . "/" . $value) && (substr($value, -4)==".jpg") ){
					$full_pic .= '<div mytag=bg_image_on_index_' . $i . ' style="z-index:' . $i . '; 
									   background:url(/img/big_photo_index/' . $value . ') center center no-repeat">
								  </div>';
					
					$radio_pic .= '<a class=a_change_big_img_on_index mytag=btn_bg_img_index_' . $i . ' 
									  active=false onClick="changeBgImageOnIndex(' . $i . ')">
            						  &bull;
            					   </a>';
					$i++;		
				}
			}
		?>
    	
        <?= $full_pic ?>
        <div class=div_in_index_baner>
        	<div mytag=text align=center style="border-bottom:white solid 1px;">
    			<div class=valign_mid>
                	BUKETtime
                </div> 
            </div>
            <div mytag=text>
    			<div class=valign_mid>
                	Подписка на цветы
                </div>
            </div>
            <div align=center mytag=link>
            	<div class=valign_mid>
                    <a href="/collections" class="btn btn_black" style="width:200px; margin:20px 0px 20px 0px">
                        для дома
                    </a>
                    <a href="/business" class="btn btn_black" style="width:200px; margin:0px 0px 20px 0px">
                        для офиса
                    </a>
                </div>
            </div>
            <div mytag=text>
    			<div class=valign_mid>
                	Всегда бесплатная доставка
                </div>
            </div>
        </div>
        <div id=div_change_logo_img_on_index>
        	<?= $radio_pic ?>    
        </div>
        
		<? if(empty($_SESSION["user"])): ?>
        	<a id=a_enter_in_club_on_index class="btn btn_green" href="/reg.php">вступить в клуб</a>
        <? endif; ?>
        
        <div class=div_in_index_baner style="margin-left:750px">
        	<div mytag=text align=center style="border-bottom:white solid 1px; padding:18px 0px;">
    			<div class=valign_mid>
                	<a href="http://www.facebook.com/BUKETtime" class=a_social target=_blank title="Facebook"><img align=absmiddle border=0 hspace=0 vspace=0 width=40 height=40 src="/img/social/facebook.png"></a>
                    <a href="https://twitter.com/AlmiraBUKETtime" class=a_social  target=_blank title="Twitter"><img align=absmiddle border=0 hspace=0 vspace=0 width=40 height=40 src="/img/social/twitter.png"></a>
                    <a href="http://almirabukettime.livejournal.com" class=a_social  target=_blank title="LiveJournal"><img align=absmiddle border=0 hspace=0 vspace=0 width=40 height=40 src="/img/social/livejournal.png"></a>
                </div>
            </div>
            <div mytag=text style="padding:10px">
    			<div class=valign_mid style="background-color:white;">
                	<div class=fb-like-box data-href="http://www.facebook.com/BUKETtime" data-width=210 data-height=390 data-show-faces=true data-stream=false data-header=true></div>
                </div>
            </div>
        </div> 
    </div>
    
	<!-- ссылки на цветы -->
    <?
		$q = mysql_query('SELECT * FROM collections ORDER BY name ASC');
		while($row = mysql_fetch_array($q)){
			echo $Sait->getAnimateGrayBigLinkOneCell("/collections/?id=" . $row['id'], $row['name'], "", "collections/" . $row['id'] . "_0.jpg", false, false);
		}
	?>
    
    <?= $Sait->getBr(50) ?>
   
   	<?= $Sait->getAnimateGrayBigLinkOneCell("/deliver/", "доставляем?", "куда мы", "", true, false) ?>
    <?= $Sait->getAnimateGrayBigLinkOneCell("/one-time_delivery/", "букетов и подарков?", "разовая доставка", "", true, false) ?>
    <?= $Sait->getAnimateGrayBigLinkOneCell("/benefits_for_members/", "членов клуба", "преимущества для", "", true, false) ?>
    <?= $Sait->getAnimateGrayBigLinkOneCell("mailto:support@bukettime.ru", "+7 (495) 507-91-91", "support@bukettime.ru", "", true, true) ?>
    
    <?= $Sait->getBr(50) ?>
</div>
<?= $Sait->getFooter(); ?>
</body>
<?= $Sait->dopCode(); ?>