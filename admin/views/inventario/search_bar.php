<div class="container">
	<form class="search-articulos" data-type="<?php echo $type?>">
		<h1 class="title">
			Busqueda de Articulos
		</h1>
		<input type="text" name="pid" placeholder="PID" value="<?php echo isset($address[1]) ? texto((string)$address[1]) : ""?>">
		<input type="submit" value="Buscar">
	</form>
</div>

<hr>