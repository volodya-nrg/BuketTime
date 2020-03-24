<?php
class Sait{
	public $host 		= HOST;
	public $discount 	= 20;
	public function getHead($title="", $keywords="", $desc=""){
		return '<title>' . $title . '</title>
				
				<meta charset="utf-8" />
				<meta http-equiv="Content-Language" content="ru">
				<meta name="keywords" content="' . $keywords . '">
				<meta name="description" content="' . $desc . '">
				
				<link href="/css.css" rel="stylesheet" />
				<link href="/redactor/redactor.css" rel="stylesheet" />
				<link rel="shortcut icon" href="/img/Favicon.ico" />
				
				<script language="javascript" src="/jquery-1.8.0.min.js"></script>
				<script language="javascript" src="/ajaxfileupload.js"></script>
				<script language="javascript" src="/js.js"></script>
				<script language="javascript" src="/redactor/redactor.min.js"></script>
				
				<script type="text/javascript" src="http://api.sarafanpro.ru/js/api/current.js"> </script>
				<script type="text/javascript">
					sarafanAPI.init_like("80281158053b7fd8fcd20c01239d80b1", "current");
				</script>
				
				<style type="text/css">
					.valign_mid{
						height:inherit;
						vertical-align:middle;
						display:table-cell;
					}
					.marg-t-13{}
				</style>

				<!--[if gt IE 5.5]>
				<style type="text/css">
					.valign_mid{
						height:inherit;
						vertical-align:middle;
						display:table;
					}
					.marg-t-13{
						margin-top:13px;
					}
				</style>
				<![endif]-->';
	}
	public function getHeader(){
		if(!empty($_SESSION["user"])){
			$name = getOneSqlQuery('SELECT name FROM users WHERE id='. $_SESSION["user"]);
			$user = '<a id=a_account class="a_top_menu account floatRight">
						<div class="valign_mid marg-t-13">
							аккаунт &nbsp;&nbsp;&nbsp;
						</div>
						<ul>
							<li style="text-transform:capitalize">' . $name . '</li>
							<li onclick="getPage(\'/profile.php?subscriptions\')">мои подписки</li>
							<li onclick="getPage(\'/profile.php?settings\')">настройки</li>
							<li onclick="getPage(\'/profile.php?exit\')">выход</li>
						</ul>
					</a>';
		}else{
			$user = '<a class="a_top_menu floatRight" href="/reg.php">
					<div class="valign_mid marg-t-13">
						мой профиль
					</div>
				 </a>';
		}

		$html = '<div id=div_top_header_menu align=center style="background-color:black; height:40px">
					<div style="width:980px; height:inherit;">
						<a class="a_top_menu floatLeft" href="/service.php">
							<div class="valign_mid marg-t-13">
								подписка на цветы
							</div>
						</a>
						<a class="a_top_menu floatLeft" href="/collections/">
							<div class="valign_mid marg-t-13">
								букеты
							</div>
						</a>
						<a class="a_top_menu floatLeft" href="/gifts/">
							<div class="valign_mid marg-t-13">
								подарки
							</div>
						</a>
						<a class="a_top_menu floatLeft" href="/business/">
							<div class="valign_mid marg-t-13">
								для офиса
							</div>
						</a>
						<a class="a_top_menu floatLeft" href="/blog/">
							<div class="valign_mid marg-t-13">
								блог
							</div>
						</a>
						' . $user . '
						<a class="a_top_menu floatRight" onMouseOver="$(this).css(\'color\', \'white\')" style="cursor:default">
							<div class="valign_mid marg-t-13">
								+7 (495) 507-91-92
							</div>
						</a>
						<a id=a_cart class="a_top_menu floatRight" href="/cart.php">
							<div class="valign_mid marg-t-13">
								корзина
								<strong id=trash_elem></strong>
							</div>
						</a>
					</div>
				</div>';
		return $html;
	}
	public function getNameLogoSait($text="BUKETtime", $link=""){
			return '<a href="/" class=a_very_big_name_sait>' . $text . '</a>';
		}
	public function getFooter($showBigLink = false){
		if($showBigLink){
			$showBigLink =  $this->getAnimateGrayBigLinkOneCell("/deliver/", "доставляем?", "куда мы", "", true, false, "black") . 
							$this->getAnimateGrayBigLinkOneCell("/one-time_delivery/", "букетов и подарков?", "разовая доставка", "", true, false, "black") . 
							$this->getAnimateGrayBigLinkOneCell("/benefits_for_members/", "членов клуба", "преимущества для", "", true, false, "black") . 
							$this->getAnimateGrayBigLinkOneCell("mailto:support@bukettime.ru", "+7 (495) 507-91-92", "support@bukettime.ru", "", true, true, "black");
		}
		else $showBigLink = "";

		$html = '<div id=div_footer_sait align=center style="background-color:black;">
					<div style="width:980px; display:table">
						' . $showBigLink . '
						' . $this->getBr() . '
						<div align=left style="color:white; display:table; width:100%;">
								<span style="line-height:30px;">
									Подпишитесь, чтобы получать новости и специальные предложения от BUKETtime.
								</span>
								<span class=floatRight>
									<input id=input_subscribe class=input_with_desc type=text mytag="Ваш e-мeйл" title="Пожалуйста, заполните это поле." style="margin-right:10px; height:30px; width:200px; margin-bottom:0px;" maxlength=50>
									<a class="btn btn_green" style="width:120px" onclick="send_subscribe($(this))">
										<img class=img_preloader_small src="/img/preloader_small.png">
										рассылка
									</a>
								</span>
						</div>
						' . $this->getBr() . '
						<div class=div_footer style="border-right:black solid 1px;">
							<a class=a_footer href="/service.php">подписка на цветы</a>
							<a class=a_footer href="/collections/">букеты</a>
							<a class=a_footer href="/deliver/">куда мы доставляем?</a>
						</div>
						<div class=div_footer>
							<a class=a_footer href="/faq.php">вопросы и ответы</a>
							<a class=a_footer href="/about_us/">история <font style="text-transform:none">BUKETtime</font></a>
							<a class=a_footer href="/pressa/">пресса о нас</a>
							<a class=a_footer href="/business/">для офиса</a>
							<a class=a_footer href="/contact.php">контакты</a>
						</div>
						<div class=div_footer style="text-align:center">
							<a class=a_footer href="/agreement/">соглашение</a>
						</div>
						<div class=div_footer style="border-right:black solid 1px;">
							<a class=a_footer_facebook  target=_blank href="http://www.facebook.com/BUKETtime" title="Facebook">&nbsp;</a>
							<a class=a_footer_twitter  target=_blank href="https://twitter.com/AlmiraBUKETtime" title="Twitter">&nbsp;</a>
							<a class=a_footer_livejournal  target=_blank href="http://almirabukettime.livejournal.com" title="LiveJournal">&nbsp;</a>
							<br>
							<br>
							<br>
							<font style="color:#ccc">© 2012-' . date("Y", time()) .  ' BUKETtime</font>
						</div>
						' . $this->getBr() . '
					</div>
				</div>
				<div align=center id=admin_panel>
					<img src="/img/preloader_small_black.png" width=25 height=25 >
				</div>';
		return $html;
	}
	public function dopCode(){
		return '<div id=insertCode></div>
				<div id=div_go_up title="наверх"></div>
				<script>$("#trash_elem").text(' . $this->countCart() . ')</script>
				
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, \'script\', \'facebook-jssdk\'));</script>

				<!-- Yandex.Metrika counter -->
				<script type="text/javascript">
				(function (d, w, c) {
				 (w[c] = w[c] || []).push(function() {
				 try {
				 w.yaCounter18244888 = new Ya.Metrika({id:18244888,
				 webvisor:true,
				 clickmap:true,
				 trackLinks:true,
				 accurateTrackBounce:true});
				 } catch(e) { }
				 });

				var n = d.getElementsByTagName("script")[0],
				 s = d.createElement("script"),
				 f = function () { n.parentNode.insertBefore(s, n); };
				 s.type = "text/javascript";
				 s.async = true;
				 s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

				if (w.opera == "[object Opera]") {
				 d.addEventListener("DOMContentLoaded", f);
				 } else { f(); }
				})(document, window, "yandex_metrika_callbacks");
				</script>
				<noscript><div><img src="//mc.yandex.ru/watch/18244888" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
				<!-- /Yandex.Metrika counter -->

				<script>
				var _gaq = _gaq || [];
				 _gaq.push(["_setAccount", "UA-36324733-1"]);
				 _gaq.push(["_trackPageview"]);
				(function() {
				 var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
				 ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
				 var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
				 })();
				</script>';
	}
	public function getAnimateGrayBigLinkOneCell($link, $mainText, $dopText="", $img="", $strelka=false, $last=false, $bg="#666" ){
		if($dopText != "")
			$dopText = '<div class=div_dop_text>' . $dopText . '</div>';
		if($img != "")
			$img = '<img width=200 height=200 vspace=0 hspace=0 border=0 src="img.php?f=' . $img . '&w=200&h=200">';
		if($strelka)
			$strelka = '<div mytag=strelka style="display:none; height:5px; width:100%; background:url(/img/strelka.png) top center no-repeat; position:absolute"></div>';
		else $strelka = "";

		if($last) $right_border = '';
		else $right_border = 'border-right:#cccccc solid 1px;';

		$html = '<a class=a_float_vert_text href="' . $link . '">
					<div align=center mytag=parent style="background-color:' . $bg . '; ' . $right_border . '">
						<div class=div_hidden_black_bg mytag=bg></div>
						<div class=valign_mid>
							<div align=center mytag=text>
								' . $dopText . '
								<div>
									<h7>' . $mainText . '</h7>
								</div>
								' . $strelka . '			
							</div>
						</div>
					</div>
					' . $img . '                
				</a>';
		return $html;
	}
	public function getBr($height=10){
		return '<div style="height:' . $height . 'px; display:table; width:100%"></div>';
	}
	public function getSocial(){
				return '<div style="clear:both">
							<div style="float:left">
								Добавить в закладки:<br><br>
								' . $this->getOneCellSocial("vk") . '
								' . $this->getOneCellSocial("linkedin") . '
								' . $this->getOneCellSocial("mail") . '
								' . $this->getOneCellSocial("ya") . '
								' . $this->getOneCellSocial("memori") . '
								' . $this->getOneCellSocial("livejournal") . '
								' . $this->getOneCellSocial("facebook") . '
								' . $this->getOneCellSocial("twitter") . '
								' . $this->getOneCellSocial("friendfeed") . '
								' . $this->getOneCellSocial("blogger") . '
								' . $this->getOneCellSocial("delicious") . '
								' . $this->getOneCellSocial("google") . '
								' . $this->getOneCellSocial("myspace") . '
							</div>
						</div>';
			}
	private function getOneCellSocial($param){
		$full_adress = $this->host . $_SERVER['REQUEST_URI'];
		$adress = "";
		$desc = "";
		$pic = "";
		$flag_title = false;

		switch($param){
			case("vk"): 
				$adress = "http://vk.com/share.php?url=" . $full_adress;
				$desc = "Вконтакте";
				$pic = "vkontakte.png";
				$flag_title = false;
			break;
			case("linkedin"):
				$adress = "http://www.linkedin.com/shareArticle?mini=true&url=" . $full_adress . "&summary=&source=AddToAny&title=";
				$desc = "Linkedin";
				$pic = "linkedin.png";
				$flag_title = true;
			break;
			case("mail"):
				$adress = "http://connect.mail.ru/share?share_url=" . $full_adress;
				$desc = "Mail.ru";
				$pic = "mailru.png";
				$flag_title = false;
			break;
			case("ya"):
				$adress = "http://my.ya.ru/posts_add_link.xml?URL=" . $full_adress . "&title=";
				$desc = "Я.ру";
				$pic = "yandex.png";
				$flag_title = true;
			break;
			case("memori"):
				$adress = "http://memori.ru/link/?sm=1&u_data[url]=" . $full_adress . "&u_data[name]=";
				$desc = "memori.ru";
				$pic = "memori.png";
				$flag_title = true;
			break;
			case("livejournal"):
				$adress = "http://www.livejournal.com/update.bml?event=" . $full_adress . "&subject=";
				$desc = "Live Journal";
				$pic = "livejournal.png";
				$flag_title = true;
			break;
			case("facebook"):
				$adress = "http://www.facebook.com/sharer.php?u=" . $full_adress;
				$desc = "Facebook";
				$pic = "facebook.png";
				$flag_title = false;
			break;
			case("twitter"):
				$adress = "http://twitter.com/share?url=" . $full_adress . "&text=";
				$desc = "Twitter";
				$pic = "twitter.png";
				$flag_title = true;
			break;
			case("friendfeed"):
				$adress = "http://www.friendfeed.com/share?link=" . $full_adress . "&title=";
				$desc = "Friendfeed";
				$pic = "friendfeed.png";
				$flag_title = true;
			break;
			case("blogger"):
				$adress = "http://www.blogger.com/blog_this.pyra?t&u=" . $full_adress . "&n=";
				$desc = "Blogger";
				$pic = "blogger.png";
				$flag_title = true;
			break;
			case("delicious"):
				$adress = "http://delicious.com/save?url=" . $full_adress . "&title=";
				$desc = "Delicious";
				$pic = "delicious.png";
				$flag_title = true;
			break;
			case("google"):
				$adress = "https://plus.google.com/share?url=" . $full_adress . "&gpsrc=frameless&hl=ru";
				$desc = "Google";
				$pic = "google.png";
				$flag_title = false;
			break;
			case("myspace"):
				$adress = "http://www.myspace.com/Modules/PostTo/Pages/?u=" . $full_adress;
				$desc = "Myspace";
				$pic = "myspace.png";
				$flag_title = false;
			break;
		}

		if($flag_title) $title = '+ $("title").text()';
		else $title = "";

		$a_id = mt_rand();
		return '<a id=a_' . $a_id . ' class=a_social target="_blank">
					<img align=absmiddle alt="' . $desc . '" title="' . $desc . '" height=30 width=30 src="/img/social/' . $pic . '" />
				</a>
				<script>
					$("#a_' . $a_id . '").attr("href", "' . $adress . '"' . $title . ');
				</script>';
	}
	public function getTopLinkDefault(){
		return '<div style="margin-left:10px">
					<a class=a_under_top_link href="/faq.php">вопросы и ответы</a>
					<a class=a_under_top_link href="/deliver/">куда мы доставляем</a>
					<a class=a_under_top_link href="/pressa/">пресса о нас</a>
					<a class=a_under_top_link href="/contact.php">контакты</a>
				</div>';
	}	
	
	public function getPage($id){
		$q = mysql_query('SELECT * FROM pages WHERE id=' . $id);
		$data = mysql_fetch_array($q);
		return $data;
	}	
	public function getOptionsCountry(){
		$q = mysql_query('SELECT * FROM country ORDER BY name ASC');
		$str = "";
		while($row = mysql_fetch_array($q)){
			$str .= '<option value=' . $row['id'] . ' >' . $row['name'] . '</option>';
		}
		return $str;
	}
	public function getOptionsMosMetro(){
		$q = mysql_query('SELECT * FROM mosmetro ORDER BY name ASC');
		$str = "";
		while($row = mysql_fetch_array($q)){
			$str .= '<option value=' . $row['id'] . ' >' . $row['name'] . '</option>';
		}
		return $str;
	}
	public function getUserData(){
		if(empty($_SESSION["user"]))
			return false;
			
		$q = mysql_query('SELECT * FROM users WHERE id=' . $_SESSION["user"]);
		$data = mysql_fetch_array($q);
		return !empty($data['id'])? $data: false;	
	}
	public function countCart($opt=0){
		$total_el = 0; // количество элементов в корзине (int)
		$sum = 0; // кол-во денег без доставки
		
		foreach($_SESSION["cart"] as $key=>$val){
			$temp = getOneSqlQuery('SELECT cost FROM gifts WHERE id=' . $key);
			$sum += ($val * $temp);
			$total_el += $val;
		}

		if(!empty($_SESSION["user"])) $sum -= round(($this->discount * $sum)/100);

		switch($opt){
			case(1): return $sum; 				break;
			case(2): return $sum_with_dostavka; break;
			default: return $total_el; 
		}
	}
	public function blockCart($gift_id, $total_el, $cost, $title="", $desc=""){
		return '<div class=div_one_cell_cart mytag=' . $gift_id .'>
					<div mytag=true style="margin:0px 10px">
						<div class=valign_mid>
							<a mytag=true class="btn btn_black" href="?gift=' . $gift_id . '&count=0" 
							   style="width:80px; position:relative; z-index:1">удалить</a>
						</div>
					</div>
					<img alt="" title="" width=100 height=100 src="img.php?f=gifts/' . $gift_id . '.jpg&w=100&h=100" style="margin-right:10px">
					<div mytag=true style="width:290px; overflow:hidden; vertical-align:top">
						<strong style="font-size:14px; text-transform:capitalize">' . $title . '</strong>
						<div>' . $desc . '</div>
					</div>
					<div mytag=true align=right class=floatRight style="width:110px">
						<strong>' . ($cost * $total_el) . ' руб.</strong>
					</div>
					<div mytag=true align=right class=floatRight style="margin:0px 10px; width:60px">
						<input type=text value=' . $total_el . ' maxlength=3 style="width:40px; text-align:right;">
					</div>
					 <div align=right class=floatRight style="margin-right:0px; width:90px">
						' . $cost . ' руб.
					</div>
				</div>';
	}
	public function getSmallCalendar(){
		return '<script>
					var myD = myGetDate();
					var month_hidden = myD["month"];
					var year_hidden  = myD["year"];
				</script>
				<div class=calendar style="width:175px; display:none; position:absolute; margin-left:170px">
					<table style="margin-bottom:5px; background-color:#88b759; width:100%">
						<tr>
							<td mytag=btn onClick="myGetDate(0);">
								<
							</td>
							<td mytag=month_year></td>
							<td mytag=btn onClick="myGetDate(1);">
								>
							</td>
						</tr>
					</table>
					<div mytag=dayName>пн</div>
					<div mytag=dayName>вт</div>
					<div mytag=dayName>ср</div>
					<div mytag=dayName>чт</div>
					<div mytag=dayName>пт</div>
					<div mytag=dayName>сб</div>
					<div mytag=dayName>вс</div>                
					<div mytag=place_for_day></div>
				</div>
				<input id=cart_last_date type=text maxlength=10 onfocus="$(\'div[class=calendar]\').fadeIn()" style="width:160px">
				<script>
					$("td[mytag=month_year]").text(myD["monthName"] + " " + myD["year"]);
					$("div[mytag=place_for_day]").html(myD["days"]);
					$("div[selected=true]").addClass("dayNumSel");
				</script>';	
	}
	public function getDopGift($id, $name, $cost){
		$key = array_key_exists($id, $_SESSION["cart"])? true: false;
		$btn_color = $key? "gray": "black";
		
		return '
			<div class=dop_gift>
				<a mytag=img href="/gifts/?id=' . $id . '" target="_blank">
					<img width=200 height=150 src="/img.php?f=gifts/' . $id . '.jpg&w=200&h=150" />
				</a>
				<h5>' . $name . '</h5>
				<span>' . $cost . ' руб.</span>
				<a class="btn btn_' . $btn_color . '" style="width:60px" onClick="addDopGiftInCart($(this), ' . $id . ')">купить</a>
			</div>
		';	
	}
	public function getPaginator($query, $limit=20, $text=""){
		$q = mysql_query($query) or die("Ошибка mysql запроса!");
		$q = mysql_fetch_array($q);
		
		$total = $q[0];
		
		$offset = 0;
		$cur_page = 1;
		if( !empty($_GET["page"]) && ($_GET["page"]>1) && is_numeric($_GET["page"]) ){
			$max_page = ceil($total/$limit);
			
			if($_GET["page"] > $max_page) $cur_page = $max_page;
			else $cur_page = $_GET["page"];
			
			$offset = ($limit * ($cur_page-1));
		}
		
		$page = "";
		
		$max_page = ceil($total/$limit);
		$max_page_temp = $max_page;
		$dots = '<a mytag=selected style="border-width:0px;" >...</a>';
		
		if($max_page > ($cur_page+5)) $max_page = ($cur_page+5);
		
		if( substr_count($_SERVER['REQUEST_URI'], "?") ){
			if(substr_count($_SERVER['REQUEST_URI'], "page=")){
				$pos_start = strpos($_SERVER['REQUEST_URI'], "page=");
				$url = substr($_SERVER['REQUEST_URI'], 0, $pos_start); 
			}else
				$url = $_SERVER['REQUEST_URI'] . "&";	
		} 
		else $url = "?";
		
		$i=1;
		if($cur_page>6){
			$page .= '<a href="' . $url . 'page=1">1</a>' . $dots;
			$i=($cur_page - 5);
		} 
		
		for(; $i<=$max_page; $i++){
			if($i==$cur_page) $page .= '<a mytag=selected >' . $i . '</a>';
			else $page .= '<a href="' . $url . 'page=' . $i . '">' . $i . '</a>';
		}
		
		if(($cur_page + 5)<$max_page_temp) $page .= $dots . '<a href="' . $url . 'page=' . $max_page_temp . '">' . $max_page_temp . '</a>';
		
		$temp_1 = $cur_page * $limit - $limit + 1;
		$temp_2 = ($temp_1-1) + $limit;
		if($temp_2 > $total) $temp_2 = $total;
		
		$desc = !$total? $text . " 0 из 0": $text." ".$temp_1."-".$temp_2." из ".$total;
		
		$ar = array(
						"pages" => $page,
						"total" => $total,
						"offset"=> $offset,
						"desc"	=> $desc
					);
		return $ar;
	}
}

?>