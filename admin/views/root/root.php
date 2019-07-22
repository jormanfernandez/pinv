<div class="container">
	
	<h1 class="title">
		<?php echo Person::sayHi(USER["nick"])?>
	</h1>

	<?php 

	foreach (USER["access"] as $access => $url) {
		require RAIZ.SEP."admin".SEP."views".SEP."root".SEP."accesos.php";
	}

	?>
	
</div>