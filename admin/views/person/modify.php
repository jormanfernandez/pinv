<div>
	<form class="modify-person">
		<h1 class="title">
			Modificar persona
		</h1>
		<input type="hidden" name="pid" value="<?php echo texto($person->get("pid")["data"]);?>">
		<span>Nombre: </span> <input type="text" name="nombre" placeholder="Nombre" value="<?php echo texto($person->get("name")["data"])?>" required>
		<br>
		<span>Apellido: </span> <input type="text" name="apellido" placeholder="Apellido" value="<?php echo texto($person->get("lname")["data"])?>" required>
		<br>
		<span>Cedula: </span> <input type="number" name="cedula" placeholder="Cedula" value="<?php echo texto($person->get("cedula")["data"])?>" required>
   		<input type="submit" value="Modificar persona">
	</form>
</div>