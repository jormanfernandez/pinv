<div class="container">
	<h1 class="title-simple">
		Lista de personas
	</h1>

	<hr>

	<?php 

	foreach(Person::getAll() as $idx => $person):

		$person = new Person(intval($person["id"]));

		if($person->hasError()) {
			continue;
		}

	?>

	<div class="card list">
		<span>
			Nombre completo: <?php echo texto($person->get("fullName")["data"]);?>
		</span>
		<br>
		<span>
			Cedula: <?php echo texto($person->get("cedula")["data"]);?>
		</span>
		<br>
		<span>
			Fecha de creacion: <?php echo texto($person->get("created")["data"]);?>
		</span>
		<br>
		<br>

		<a href="<?php 
			echo PADRE."/".PRINCIPAL."/persona/modificar/".texto($person->get("cedula")["data"])
		?>">Modificar</a>
	</div>

	<?php
	endforeach;
	?>
</div>