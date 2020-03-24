<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
$Sait = new Sait();
?>
<!DOCTYPE HTML>
<head>
<?
	$blog_id = false;
	if(!empty($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"]>0 ){
		if(getOneSqlQuery('SELECT COUNT(id) FROM blog WHERE id=' . $_GET["id"]))
			$blog_id = $_GET["id"];
	}		
		
	if($blog_id){
		$q = mysql_query('SELECT * FROM blog WHERE id=' . $blog_id);
		$data = mysql_fetch_array($q);
		
		$title 			= $data['title'];
		$description 	= $data['description'];
		$keywords 		= $data['keywords'];
	}else{
		$title 			= "BUKETtime: блог о цветах| Уход за цветами";
		$description 	= "BUKETtime: блог о цветах| Уход за цветами";
		$keywords 		= "Блог о цветах, цветок уход, уход за цветами, уход комнатных цветов, комнатный цветок уход, уход за комнатными цветами, уход  домашних цветов, уход за домашними цветами";
	}
	
	echo $Sait->getHead($title, $keywords, $description);
?>
</head>
<body>
<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait("BUKETtime блог", "/blog/") ?>
    
	<div align=center>
        <div align=justify style="width:800px">
        	<? if($blog_id): ?>
            	<?
					$temp = scandir(".");
					$img = ""; 
					foreach($temp as $value){
						if( is_file($value) && (substr($value, -4)==".jpg") ){
							if( $data['id'] == substr($value, 0, strpos($value, "_")) ){
								$img .='<img mytag=true width=380 height=380 src="/img.php?f=blog/' . $value . '&w=380&h=380" />';
							}
						}
					}
				?>
                	<div class=div_blog_item>
                        <a mytag=false><?= $data['title'] ?></a>
                        <font mytag=true>
                            <?= changeEnMonthToRus(date("n", strtotime($data['time']))) . " " . 
                                date("j, Y (H:i:s)", strtotime($data['time'])) ?>
                        </font>
                        <hr>
                        <div mytag=true>
                            <?= $data['content'] ?>
                            <div></div>
                            <?= $img ?>
                            <div></div>
                            <?= $Sait->getSocial() ?>
                        </div>
                    </div>
                    <div class="fb-comments" data-href="<?= $Sait->host . $_SERVER["PHP_SELF"] ?>" 
                         data-num-posts=2 data-width=800></div>
					<?= $Sait->getBr(50) ?>
            <? else: ?>
				<? if(isset($_SESSION["admin"])): ?>
                	<img class=admin_img src="/img/admin_file_plus.png" width=20 height=20 style="margin-top:0px; margin-left:-30px;" 
                    	 onClick="Admin.getBlogItem()">
                <? endif; ?>
                
				<?
                    $pag = $Sait->getPaginator('SELECT COUNT(id) FROM blog', LIMIT_SMALL, "Результат");
                    $q = mysql_query('SELECT * FROM blog ORDER BY `time` DESC LIMIT ' . $pag["offset"] . ', ' . LIMIT_SMALL);
                    
                    $output = "";
                    while($row = mysql_fetch_array($q)){
                        $output .= '<div class=div_blog_item>';
                        
                            if(isset($_SESSION["admin"]))
                              $output .= '<img class=admin_img width=10 height=10 style="margin-top:-15px"
                                             src="/img/admin_engine.png"
                                             onClick="Admin.getBlogItem(' . $row['id'] . ')" >
                                          <img class=admin_img width=10 height=10 style="margin-top:-15px; margin-left:30px" 
										     src="/img/admin_trash.png" 
                                             onClick="Admin.delBlogItem(' . $row['id'] . ')" >';
                    
                            $output .= '<a mytag=true href="/blog/?id=' . $row['id'] . '">' . $row['title'] . '</a>
                                        <font mytag=true>
                                            ' . changeEnMonthToRus(date("n", strtotime($row['time']))) . ' 
                                            ' . date("j, Y (H:i:s)", strtotime($row['time'])) . '
                                        </font>
                                        <hr>
                                        <div mytag=true>
                                            ' . cutText($row['content'], 300) . '...
                                        </div>
                                    </div>';
                    }
                ?>
                <div class=paginator>
                    <div mytag=total>
                        <?= $pag['desc'] ?>
                    </div>
                    <div mytag=pages>
                        <?= $pag['pages'] ?>
                    </div>
                </div>
                <?= $output ?>
                <div class=paginator>
                    <div mytag=pages>
                        <?= $pag['pages'] ?>
                    </div>
                </div>
            <? endif; ?>
        </div>
    </div>
</div>	
	
<?= $Sait->getFooter(false); ?>
</body>
<?= $Sait->dopCode(); ?>