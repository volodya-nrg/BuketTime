<?php
session_start();

if( isset($_GET["pass"]) && ($_GET["pass"] == "test") ){
	$_SESSION["admin"] = true;
	header("Location:/");
}
?>
<form action="" method=get>
	Pass: <input type=password name=pass maxlength=15 />
	<input type=submit />
</form>