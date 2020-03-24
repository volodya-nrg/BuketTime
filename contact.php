<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Mail.php");
	
	$Sait 	= new Sait();
	$MyMail = new MyMail($_SERVER['SERVER_NAME'], 25, "localhost", SUPPORT, EMAIL_PASS, "BUKETtime.ru");
	
	$data = $Sait->getPage(2);
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: Контакты"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <hr>
   	<div align=center>
    	<div align=justify style="width:800px;">
			<? if(isset($_SESSION["admin"])): ?>
            	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:0px; margin-left:-30px" 
                	 onClick="Admin.getContact()" >
            <? endif; ?>
					
			<?= $data['content'] ?>
            <br>
            <table>
            	<tr>
                	<td align=left valign=top width=120>
                    	<strong>Мы на Facebook</strong><br>
            			<a href="http://www.facebook.com/BUKETtime" target=_blank class=a_social>
                        	<img height=80 width=80 src="/img/social/full_facebook.png">
                       	</a>
                    </td>
                    <td align=left valign=top width=120>
                    	<strong>Мы в Twitter</strong><br>
            			<a href="https://twitter.com/AlmiraBUKETtime" target=_blank class=a_social>
                        	<img height=80 width=80 src="/img/social/full_twitter.png">
                        </a>
                    </td>
                    <td align=left valign=top width=120>
                    	<strong>Мы в LiveJournal</strong><br>
            			<a href="http://almirabukettime.livejournal.com" target=_blank class=a_social>
                        	<img src="/img/social/full_livejournal.png" height=80  width=80>
                        </a>
                    </td>
                </tr>
            </table>
         	<? if(isset($_SESSION["admin"])): ?>
            	<br><br><br>
            	<h6>Отправка сообщения пользователям</h6>
                <? 
					if(!empty($_POST["email"]) && !empty($_POST["message"])){
						$aEmail = substr_count($_POST["email"], ",")? myExplode(",", $_POST["email"]): array($_POST["email"]);
						$temp = array();
						
						foreach($aEmail as $val)
							if(checkEmail($val)) 
								$temp[] = $val;
						
						$aEmail 		= $temp;
						$message 		= clearBigText($_POST["message"]);
						$tema 			= !empty($_POST["tema"])? clearBigText($_POST["tema"]): "";
						$valid_types 	= array("jpg", "jpeg", "png", "gif", "doc", "xls", "txt", "rtf", "zip", "rar", "pdf");
						$aAttachFile	= array();
						$err 			= "";
						$is_our_email	= !empty($_POST["our_email"])? 1: 0;
						
						if(!count($aEmail)) 
							$err .= "Ошибка: впишите корректный(ые) е-мэйл(ы)!";
						if(myStrlen($message) < 5){
							if($err != "") 
								$err .= '<br>';
							$err .= "Ошибка: впишите сообщение (больше 5 символов)!";	
						} 
						
						if(empty($err)){
							if( isset($_FILES["attach_file"]) ){
								$aFiles = aGetInputFiles($_FILES["attach_file"]);
								
								foreach($aFiles as $val){
									$ext = getExtension($val["name"]);
									$temp_file_name = $_SERVER['DOCUMENT_ROOT'] . "/attach_file/" . $val["name"];
									
									if(in_array($ext, $valid_types) && move_uploaded_file($val["tmp_name"], $temp_file_name))
										$aAttachFile[] = $temp_file_name;
								}
							}
							
							try{
								foreach($aEmail as $email)
									$MyMail->send($email, $tema, $message, $is_our_email, $aAttachFile);
								foreach($aAttachFile as $val)
									unlink($val);
							}
							catch(Exception $e){
								mail(EMAIL_CREATOR, "Не отправилось письмо с " . $_SERVER['SERVER_NAME'], $e->getMessage());
							}
							
							echo '<br><div class=alert_message>Сообщение отправлено</div>';
							$aEmail 	= array();
							$message 	= "";
							$tema 		= "";
						}else
							echo '<br><div class="alert_message red">' . $err . '</div>';
					}else{
						$aEmail 	= array();
						$message 	= "";
						$tema 		= "";
					}
				?>
                <form id=form_for_send_message action="" method=post  enctype="multipart/form-data">
                    <table border=0 cellpadding=0 cellspacing=10>
                        <tr>
                            <td align=right valign=middle width=120>
                                
                            </td>
                            <td align=left valign=middle>
                                <input type=checkbox name=our_email checked value=1 style="margin-right:10px" />
                                Зарегистрированный(ые) эл. адрес(а) :
                            </td>
                        </tr>
                        <tr>
                            <td align=right valign=middle>
                                Е-мэйл(ы) * :
                            </td>
                            <td align=left valign=middle>
                                <input type=text name=email maxlength=255 style="width:500px" value="<?= implode(",", $aEmail) ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align=right valign=middle>
                                Тема:
                            </td>
                            <td align=left valign=middle>
                                <input type=text name=tema maxlength=255 style="width:500px" value="<?= $tema ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align=right valign=middle>
                                Сообщение * :
                            </td>
                            <td align=left valign=middle>
                                <textarea name=message style="width:500px; height:100px"><?= $message ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td align=right valign=top>Прикрепить файл:</td>
                            <td align=left  valign=middle>
                            	<input type=file name="attach_file[]" multiple="multiple" style="width:500px; margin-bottom:5px;" />
                                <div>
                                	<font color=gray>
                                    	типы: JPG, JPEG, PNG, GIF, DOC, XLS, RTF, ZIP, RAR, PDF
                                    </font>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align=left colspan=2>
                                <font color=gray>* поля необходимы для заполнения</font>
                            </td>
                        </tr>
                        <tr>
                            <td align=center colspan=2>
                            	<a class="btn btn_green" onClick="$('#form_for_send_message').submit()">Отправить</a>
                            </td>
                        </tr>
                    </table>
                </form>
            <? endif; ?>
         </div>
    </div>
   	<?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>