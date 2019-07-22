<div class="container categs">
	<h1 class="title-simple">Categorias</h1>
	<hr>
	<label>
		<span>Crear</span> <input type="radio" name="tab" value="crear" checked>
	</label>
	<label>
		<span>Buscar y Modificar</span> <input type="radio" name="tab" value="buscar">
	</label>

	<div name="categ-crear" class="box card">
		<form class="create-categ">
			<span>
				Nombre de categoria
			</span>
			<input type="text" name="nombre" placeholder="Categoria">

			<input type="submit" value="Guardar">
		</form>
	</div>

	<div name="categ-buscar" class="box" style="display: none">
	</div>
</div>