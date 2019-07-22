<?php 
foreach ($return["data"] as $idx => $data):

$articulo = new Articulo(intval($data["item"]));
$estado = new EstatusObj($data["estatus"]);
$departamento = new Departamento($data["departamento"]);
$person = empty($data["persona"]) ? NULL : new Person($data["persona"]);
$asignado = empty($data["asignado"]) ? "Removido" : "Asignado";
$puesto = empty($data["puesto"]) ? NULL : $data["puesto"];

?>

<div class="card list lower_line" style="page-break-inside:avoid; page-break-after:auto">	
	<span style="float:left;position:relative;"><b>Fecha de la accion: <?php echo texto($data["fecha"])?></b></span>
	<br>
	<span><b>Id: <?php echo texto($data["id"])?></b></span>
	<br>
	<span>Articulo</span>
	<br>
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
			Estado actual: <?php echo texto($articulo->get("estatus")->get("nombre")["data"]);?>
		</span>

		<hr>

		<span>
			Descripcion: <?php echo texto(empty($articulo->get("descripcion")) ? "-" : $articulo->get("descripcion"));?>
		</span>

	</div>

	<br>

	<?php if ($person != NULL) {?>
	
	<span>Persona</span>

	<div class="card">
		<span>Nombre: <?php echo texto($person->get("name")["data"])?></span>
		<br>
		<span>Apellido: <?php echo texto($person->get("lname")["data"])?></span>
		<br>
		<span>Cedula: <?php echo texto($person->get("cedula")["data"])?></span>
	</div>

	<br>	

	<?php } ?>

	<?php if ($puesto != NULL) {?>
	
	<span>Puesto: <?php echo texto($puesto)?></span>

	<br>	

	<?php } ?>


	<span><b>Estado del articulo al momento de la accion</b>: <?php echo texto($estado->get("nombre")["data"])?></span>

	<br>

	<span><b>Departamento</b>: <?php echo texto($departamento->get("nombre")["data"])?></span>

	<br>

	<span><b>El articulo fue</b> <?php echo texto($asignado)?></span>

	<br>

	<span><b>Accion</b>: <?php echo texto(empty($data["accion"]) ? "-" : $data["accion"])?></span>
</div>

<?php
endforeach;
?>