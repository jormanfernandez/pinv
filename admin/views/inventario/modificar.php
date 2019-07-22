<div>
	<form class="inventario-modificar" data-pid="<?php echo texto($articulo->get("pid"))?>">
		<h1 class="title">
			Modificar el articulo <?php echo texto($articulo->get("serial"));?>
		</h1>

		<br>

		<span>
			Nombre:
		</span>
		<input type="text" name="nombre" placeholder="Nombre" value="<?php echo texto($articulo->get("nombre"))?>">

		<br>

		<span>
			Categoria:
		</span>

		<select name="categoria">
		<?php 

		foreach(Categoria::getAll() as $idx => $categoria):
		?>
		<option value="<?php echo $categoria["id"];?>" <?php 
			echo  $categoria["id"] == $articulo->get("categoria")->get("id")["data"] ? "selected" : "" 
		?>><?php echo texto($categoria["nombre"])?></option>
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
		<option value="<?php echo $marca["id"];?>" <?php 
			echo  $marca["id"] == $articulo->get("marca")->get("id")["data"] ? "selected" : "" 
		?>><?php echo texto($marca["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>
			Serial:
		</span>
		<input type="text" name="serial" placeholder="Serial" value="<?php echo texto($articulo->get("serial"));?>">

		<br>

		<span>
			Estado:
		</span>

		<select name="estado">
		<?php 

		foreach(EstatusObj::getAll() as $idx => $estatus):
		?>
		<option value="<?php echo $estatus["id"];?>" <?php 
			echo  $estatus["id"] == $articulo->get("estatus")->get("id")["data"] ? "selected" : "" 
		?>><?php echo texto($estatus["nombre"])?></option>
		<?php
		endforeach;
		?>

		</select>

		<br>

		<span>
			Descripcion:
		</span>
		<input type="text" name="descripcion" placeholder="Descripcion" value="<?php echo texto($articulo->get("descripcion"));?>">

		<br>

		<input type="submit" value="Guardar">
	</form>
</div>