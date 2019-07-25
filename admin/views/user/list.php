<div class="container">
	<h1 class="title-simple">
		Lista de usuarios
	</h1>

	<hr>

	<?php 

	foreach(User::getAll() as $idx => $user):

		$user = new User(intval($user["id"]));

		if($user->hasError()) {
			continue;
		}

	?>

	<div class="card list">
		<span>
			Nick: <?php echo texto($user->get("nick")["data"]);?>
		</span>
		<br>
		<span>
			Estatus: <?php echo texto($user->get("estatus")["data"]["name"]);?>
		</span>
		<br>
		<span>
			Pertenece a: <?php 
				echo texto($user->get("person")["data"]->get("cedula")["data"]);
			?>
		</span>
		<br>
		<br>

		<a href="<?php 
			echo PADRE."/".PRINCIPAL."/user/modificar/".texto($user->get("nick")["data"])
		?>">Modificar</a>
	</div>

	<?php
	endforeach;
	?>
</div>