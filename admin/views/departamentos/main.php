<div class="container departamentos">
	<h1 class="title-simple">Departamentos</h1>
	<hr>
	<label>
		<span>Crear</span> <input type="radio" name="tab" value="crear" checked>
	</label>
	<label>
		<span>Buscar y Modificar</span> <input type="radio" name="tab" value="buscar">
	</label>

	<div name="departamento-crear" class="box card">
		<form class="crear-departamento">
			<span>
				Nombre del departamento
			</span>
			<input type="text" name="nombre" placeholder="Departamento">

			<input type="submit" value="Guardar">
		</form>
	</div>

	<div name="departamento-buscar" class="box" style="display: none">
	</div>
</div>