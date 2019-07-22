<div>
	<form class="inventario-agregar">
		<h1 class="title">
			Agregar articulo al inventario
		</h1>

		<br>

		<span>
			Nombre:
		</span>
		<input type="text" name="nombre" placeholder="Nombre">

		<br>

		<span>
			Categoria:
		</span>

		<select name="categoria">
		<?php 

		foreach(Categoria::getAll() as $idx => $categoria):
		?>
		<option value="<?php echo $categoria["id"];?>"><?php echo texto($categoria["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>
			Marca:
		</span>

		<select name="marca">
		<?php 

		foreach(Marca::getAll() as $idx => $marca):
		?>
		<option value="<?php echo $marca["id"];?>"><?php echo texto($marca["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>
			Serial:
		</span>
		<input type="text" name="serial" placeholder="Serial">

		<br>

		<span>
			Estado:
		</span>

		<select name="estado">
		<?php 

		foreach(EstatusObj::getAll() as $idx => $estatus):
		?>
		<option value="<?php echo $estatus["id"];?>"><?php echo texto($estatus["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>
			Descripcion:
		</span>
		<input type="text" name="descripcion" placeholder="Descripcion">

		<br>

		<input type="submit" value="Guardar">
	</form>
</div>