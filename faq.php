<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/class.Sait.php");
	$Sait = new Sait();
	$data = $Sait->getPage(3);
?>
<!DOCTYPE HTML>
<head>
    <?= $Sait->getHead("BUKETtime: Вопросы о подписке на доставку цветов"); ?>
</head>
<body>
	<?= $Sait->getHeader(); ?>

<div class=div_body>
    <?= $Sait->getNameLogoSait() ?>
    <?= $Sait->getTopLinkDefault() ?>
    <hr>
    <?= $Sait->getBr(10); ?>
    <div align=center>
    	<div align=left style="width:800px">
        	<? if(isset($_SESSION["admin"])): ?>
            	<img class=admin_img src="/img/admin_engine.png" width=20 height=20 style="margin-top:0px; margin-left:-30px" 
                	 onClick="Admin.getFaq()" >
            <? endif; ?>
            <?= $data['content'] ?>
			<?= $Sait->getBr(20); ?>
            <h6 style="text-transform:none">Вы готовы к подписке? <a href="/collections">подписаться.</a></h6>
            <? if(isset($_SESSION["admin"])): ?>
            	<!--<img class=admin_img src="/img/admin_file_plus.png" width=20 height=20 style="margin-left:-30px" 
                 	 onClick="adminPanelFaq('show', 'faq', '', true)">-->
            <? endif; ?>
            
            <? 
				$q = mysql_query('SELECT A.id, A.question, A.answer, B.name AS category FROM faq AS A LEFT OUTER JOIN faq_category AS B ON A.cat=B.id ORDER BY A.id ASC');
				$faq = array();
				$i=0;
				while($row = mysql_fetch_array($q)){
					$faq[$i]["id"] 			= $row["id"];
					$faq[$i]["category"] 	= $row["category"];
					$faq[$i]["question"] 	= $row["question"];
					$faq[$i]["answer"] 		= $row["answer"];
					$i++;
				}
				$category = array();
                foreach($faq as $key=>$val)
					$category[] = $val["category"];
                
                $category = array_unique($category);
				foreach($category as $val){
					echo '<ul class=ul_fag>
                            <h6>' . $val . '</h6>';
                            foreach($faq as $val2){
                                if($val2["category"] == $val){
                                    echo '<li mytag=true>';
                                    
									/*if(isset($_SESSION["admin"]))
									   echo '<img class=admin_img src="/img/admin_engine.png" width=10 height=10 	
                                                  style="margin-left:-40px"
                                                  onClick="adminPanelFaq(\'show\', \'faq/' . $val2["id"] . '\', \'\', false)" >
                                             <img class=admin_img src="/img/admin_trash.png" width=10 height=10 	
                                                  style="margin-left:-60px"
                                                  onClick="delFile(\'faq/' . $val2["id"] . '\')" >';*/
                                       
									   echo '<a mytag=true>' . $val2["question"] . '</a>
                                             <div mytag=true style="width:100%">
                                                ' . $val2["answer"] . '
                                             </div>
                                          </li>';	
                                }
                            }
                    echo '</ul>';	
				}
            ?>
        </div>
    </div>
    
    <?= $Sait->getBr(50) ?>
</div>	
	
<?= $Sait->getFooter(true); ?>
</body>
<?= $Sait->dopCode(); ?>