<div class="container">
	<form class="search-person">
		<h1 class="title">
			Busqueda de Personas
		</h1>
		<input type="text" name="cedula" placeholder="Cedula" value="<?php echo isset($address[1]) ? texto((string)$address[1]) : ""?>">
		<input type="submit" value="Buscar">
	</form>
</div>

<hr>