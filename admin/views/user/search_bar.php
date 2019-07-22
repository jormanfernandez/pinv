<div class="container">
	<form class="search-user">
		<h1 class="title">
			Busqueda de usuarios
		</h1>
		<input type="text" name="nick" placeholder="Nick" value="<?php echo isset($address[1]) ? texto((string)$address[1]) : ""?>">
		<input type="submit" value="Buscar">
	</form>
</div>

<hr>