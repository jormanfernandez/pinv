<div class="container marcas">
	<h1 class="title-simple">Marcas</h1>
	<hr>
	<label>
		<span>Crear</span> <input type="radio" name="tab" value="crear" checked>
	</label>
	<label>
		<span>Buscar y Modificar</span> <input type="radio" name="tab" value="buscar">
	</label>

	<div name="marca-crear" class="box card">
		<form class="crear-marca">
			<span>
				Nombre de la marca
			</span>
			<input type="text" name="nombre" placeholder="Marca">

			<input type="submit" value="Guardar">
		</form>
	</div>

	<div name="marca-buscar" class="box" style="display: none">
	</div>
</div>