<div class="container">
	<h1 class="title-simple">
		Lista de Articulos
	</h1>

	<hr>

	<form class="search-inv-list">
		<h1 class="title-simple">
			Busqueda
		</h1>
		<span>
			PID:
		</span>
		<input type="text" name="pid" placeholder="PID">

		<br>

		<span>
			Serial:
		</span>
		<input type="text" name="serial" placeholder="Serial">

		<br>

		<span>
			Categoria:
		</span>

		<select name="categoria">
			<option value="">-</option>
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
			<option value="">-</option>
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
			Estado:
		</span>

		<select name="estado">
			<option value="">-</option>
			<?php 

			foreach(EstatusObj::getAll() as $idx => $estatus):
			?>
			<option value="<?php echo $estatus["id"];?>"><?php echo texto($estatus["nombre"])?></option>
			<?php
			endforeach;
			?>

		</select>

		<input type="button" value="Limpiar">
		<input type="submit" value="Buscar">

	</form>

	<hr>
	<br>

	<?php 
	foreach($articulos["data"] as $idx => $articulo):

		$articulo = new Articulo(intval($articulo["id"]));

		if ($articulo->hasError()) {
			continue;
		}
	?>


		<div class="card list lower_line" 
			data-pid="<?php echo texto($articulo->get("pid"))?>" 
			data-estado="<?php echo texto($articulo->get("estatus")->get("id")["data"])?>" 
			data-categoria="<?php echo texto($articulo->get("categoria")->get("id")["data"])?>" 
			data-marca="<?php echo texto($articulo->get("marca")->get("id")["data"]);?>"
			data-serial="<?php echo texto($articulo->get("serial"));?>">

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

			<hr>
			<br>

			<a href="<?php echo PADRE."/".PRINCIPAL?>/inventario/modificar/<?php echo urlencode($articulo->get("pid"))?>">Modificar</a>
        	<a href="<?php echo PADRE."/".PRINCIPAL?>/inventario/asignar/<?php echo urlencode($articulo->get("pid"))?>">Asignar</a>

		</div>

	<?php
	endforeach;
	?>
</div>