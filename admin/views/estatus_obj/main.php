<div class="container estatus_obj">
	<h1 class="title-simple">Estatus de Objetos</h1>
	<hr>
	<label>
		<span>Crear</span> <input type="radio" name="tab" value="crear" checked>
	</label>
	<label>
		<span>Buscar y Modificar</span> <input type="radio" name="tab" value="buscar">
	</label>

	<div name="estatus-crear" class="box card">
		<form class="crear-estatus">
			<span>
				Nombre del estatus
			</span>
			<input type="text" name="nombre" placeholder="Estatus">

			<input type="submit" value="Guardar">
		</form>
	</div>

	<div name="estatus-buscar" class="box" style="display: none">
	</div>
</div>