<div>
	<form class="inventario-asignar" data-pid="<?php echo texto($articulo->get("pid"))?>">
		<h1 class="title">
			Asignar articulo
		</h1>

		<div class="card">

			<span>
				Serial: <?php echo texto($articulo->get("serial"));?>
			</span>

			<hr>

			<span>
				Categoria: <?php echo texto($articulo->get("categoria")->get("nombre")["data"]);?>
			</span>

			<hr>

			<span>
				Marca: <?php echo texto($articulo->get("marca")->get("nombre")["data"]);?>
			</span>

			<hr>

			<span>
				Estado: <?php echo texto($articulo->get("estatus")->get("nombre")["data"]);?>
			</span>

			<hr>

			<span>
				Descripcion: <?php echo texto(empty($articulo->get("descripcion")) ? "-" : $articulo->get("descripcion"));?>
			</span>

		</div>

		<hr>

		<span>
			Departamento:
		</span>

		<select name="departamento">
		<?php 

		foreach(Departamento::getAll() as $idx => $departamento):
		?>
			<option value="<?php echo $departamento["id"];?>"><?php echo texto($departamento["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>
			Estado:
		</span>

		<select name="estatus">
		<?php 

		foreach(EstatusObj::getAll() as $idx => $estatus):
		?>
			<option value="<?php echo $estatus["id"];?>"><?php echo texto($estatus["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>Asignar a: </span>
		<select class="art-assign-type" name="asignar">
			<option value="nadie">Nadie</option>
			<option value="puesto" selected>Puesto</option>			
			<option value="persona">Persona</option>			
			<option value="puesto-persona">Puesto y persona</option>			
		</select>

		<div class="persona" style="display: none">
			<span>Persona: </span>
			<input type="number" name="persona" placeholder="Cedula">
		</div>


		<div class="puesto">
			<span>Puesto: </span>
			<input type="number" name="puesto" placeholder="Numero de puesto">
		</div>

		<span>Accion a realizar: </span>
		<textarea placeholder="Si quiere indicar algo sobre la accion realizada" name="accion"></textarea>

		<input type="submit" value="Guardar">
	</form>
</div>