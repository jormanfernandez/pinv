<!DOCTYPE html>
<html>
<head>
	<title>PINV</title>
	<meta charset="utf8">
	<link rel="stylesheet" type="text/css" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/mdl-jquery-modal-dialog.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/material.deep_orange-amber.min.css">
	<link rel="stylesheet" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/css.css">
	<link rel="stylesheet" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/sheet.css?<?php echo md5(RAIZ."admin".SEP."assets".SEP."style".SEP."sheet.css")?>">
	<link rel="stylesheet" type="text/css" href="<?php echo PADRE."/".PRINCIPAL;?>/assets/style/foopicker.css">
</head>

<script type="text/javascript" src="<?php echo PADRE."/".PRINCIPAL;?>/assets/js/jquery-3.4.1.min.js"></script>
<script src="https://storage.googleapis.com/code.getmdl.io/1.2.1/material.min.js"></script>
<script type="text/javascript" src="<?php echo PADRE."/".PRINCIPAL;?>/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo PADRE."/".PRINCIPAL;?>/assets/js/mdl-jquery-modal-dialog.js"></script>
<script type="text/javascript" src="<?php echo PADRE."/".PRINCIPAL;?>/assets/js/foopicker.js"></script>
<script type="text/javascript" src="<?php echo PADRE."/".PRINCIPAL;?>/assets/js/app.js?<?php echo md5(RAIZ."admin".SEP."assets".SEP."js".SEP."app.js")?>"></script>

<body>

	<div class="loading" style="display: none"><img alt="loading" src="<?php echo PADRE."/".PRINCIPAL;?>/assets/img/loading.gif"/></div>

	<?php 
		require "menu.php";
	?>

	<div class="wrapper">
		<?php
			if (isset($content)) {
				echo $content;
			}
		?>
	</div>

	<?php 
		require "footer.php";
	?>
</body>
</html>