<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();

if(empty($_SESSION["user"])){
	header("Location:/");
	exit;
}
if(isset($_GET["exit"])){
	unset($_SESSION["user"]);
	
	if(isset($_COOKIE[NAME_COOKIE])) 
		setcookie(NAME_COOKIE, "", time() - 3600);
	
	header("Location:/");
	exit;
}
$user = $Sait->getUserData();
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead(); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <div style="margin-left:10px">
    	<a class=a_under_top_link href="/">главная</a>
    	<?
			$curPage = "";
			
			if(isset($_GET["settings"])) 		$curPage = "settings";
			if(isset($_GET["subscriptions"])) 	$curPage = "subscriptions";
			
			if($curPage == "subscriptions") echo '<a class=a_under_top_link style="color:#95b100; cursor:default">мои подписки</a>';
			else							echo '<a class=a_under_top_link href="/profile.php?subscriptions">мои подписки</a>';
			
			if($curPage == "settings") 	echo '<a class=a_under_top_link style="color:#95b100; cursor:default">настройки</a>';
			else						echo '<a class=a_under_top_link href="/profile.php?settings">настройки</a>'; 
		?>
        <a class=a_under_top_link href="/profile.php?exit">выход</a>
    </div>
    <hr>
    
    <div align=center>
        <div align=left style="width:800px;">
        <? if($curPage == "settings"): ?>	
            <div class=div_settings>
                    <span>
                        Информация аккаунта
                    </span>
                    <span>
                        е-мэйл :
                    </span>
                    <span>
                        <input type=text readonly style="background-color:#eee" value="<?= $user["email"] ?>">
                    </span>
                </div>
                
                <hr>
                
                <div class=div_settings style="margin:30px 0px 10px 0px">
                    <span>
                        Поменять пароль
                    </span>
                    <span>
                        текущий пароль * :
                    </span>
                    <span>
                        <input id=setting_cur_pass type=password maxlength=50>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px">
                    <span>
                        
                    </span>
                    <span>
                        новый пароль * :
                    </span>
                    <span>
                        <input id=setting_new_pass type=password maxlength=50>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px">
                    <span>
                        
                    </span>
                    <span>
                        повторить новый пароль * :
                    </span>
                    <span>
                        <input id=setting_new_pass_confirm type=password maxlength=50>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px 30px 0px">
                    <span>
                        
                    </span>
                    <span>
                        
                    </span>
                    <span>
                        <a class="btn btn_green" style="width:200px" onClick="Account.updatePass($(this))">
                            <img class=img_preloader_small src="/img/preloader_small.png">
                            поменять пароль
                        </a>
                    </span>
                </div>
                
                <hr>
                
                <div class=div_settings style="margin:30px 0px 10px 0px">
                    <span>
                        рассылка новостей
                    </span>
                    <span>
                        
                    </span>
                    <span>
                        <input id=setting_send_news type=checkbox style="width:20px" <?= $user["send_news"]? "checked": "" ?> > 
                            получать новости и акции
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px 30px 0px">
                    <span>
                        
                    </span>
                    <span>
                        
                    </span>
                    <span>
                        <a class="btn btn_green" style="width:200px" onClick="Account.updateToSendNews($(this))">
                            <img class=img_preloader_small src="/img/preloader_small.png">
                            изменить рассылку
                        </a>
                    </span>
                </div>
                
                <hr>
                
                <div class=div_settings style="margin:30px 0px 10px 0px">
                    <span>
                        банковская карта
                    </span>
                    <span>
                        имя на банковской карте * :
                    </span>
                    <span>
                        <input id=setting_name type=text value="<?= $user["name"] ?>" maxlength=50>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px">
                    <span>
                        
                    </span>
                    <span>
                        Адрес для выставления счета * :
                    </span>
                    <span>
                        <input id=setting_billing_address type=text value="<?= $user["billing_address"] ?>" maxlength=255>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px">
                    <span>
                        
                    </span>
                    <span>
                        город * :
                    </span>
                    <span>
                        <input id=setting_town type=text value="<?= $user["town"] ?>" maxlength=255>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px">
                    <span>
                        
                    </span>
                    <span>
                        страна * :
                    </span>
                    <span>
                        <select id=setting_country>
                            <?= $Sait->getOptionsCountry() ?>
                        </select>
                        <script>$("#setting_country").val(<?= $user["country"] ?>)</script>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px">
                    <span>
                        
                    </span>
                    <span>
                        телефон * :
                    </span>
                    <span>
                        <input id=setting_tel type=text value="<?= $user["tel"] ?>" maxlength=15>
                    </span>
                </div>
                <div class=div_settings style="margin:10px 0px 30px 0px">
                    <span>
                        
                    </span>
                    <span>
                        
                    </span>
                    <span>
                        <a class="btn btn_green" style="width:200px" onClick="Account.updateBankCard($(this))">
                            <img class=img_preloader_small src="/img/preloader_small.png">
                            обновить данные
                        </a>
                    </span>
                </div>	
		<? elseif($curPage == "subscriptions"): ?>
        	<?
				$key = false;
				$q = mysql_query('SELECT * FROM order_collection WHERE accept_in_chronopay=1 AND user_id=' . $_SESSION["user"]);
				while($row = mysql_fetch_array($q)){
					$vkl  = "mybtn_select";
					$vykl = "mybtn";
					
					if(!$row["on_off"]){
						$vkl  = "mybtn";
						$vykl = "mybtn_select";
					}
					
					$id = $row["id"];
					
					echo '<div id=' . $id . ' class=my_subscriptions>
								<div mytag=title>
									<table cellpadding=0 cellspacing=8 width=100%>
										<tr>
											<td  width=30%>
												<strong>ID подписки: ' . $id . '</strong>		
											</td>
											<td align=center width=40%>
												<button mytag=' . $id . ' class=' . $vkl . ' 
													onClick="changeOnOffSubscriptions($(this), \'' . $id . '\');
															 changeSchedleBtn($(this), \'' . $id . '\')">вкл</button>
												<button mytag=' . $id . ' class=' . $vykl . ' 
													onClick="changeOnOffSubscriptions($(this), \'' . $id . '\');
															 changeSchedleBtn($(this), \'' . $id . '\')">выкл</button>	
											</td>
											<td align=right width=30%>
												<a class="btn btn_black" href="#" style="width:80px;"
												   onClick="deleteSubscriptions(\'' . $id . '\')" >удалить</a>	
											</td>
										</tr>
									</table>
								</div>
							</div>';
					$key = true;	
				}
				
				if(!$key) 
					echo 'У Вас подписок пока нет. Приобрести их можно <a href="/collections">тут</a>.';
			?>
        <? else: ?>
        	<h4 style="text-transform:none">Добро пожаловать, <font style="text-transform:capitalize"><?= $user["name"] ?></font>!</h4>
        <? endif; ?>
        </div>
    </div>
    
	<?= $Sait->getBr(50) ?>
</div>

<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>