<div class="card list lower_line">

	<?php if (!empty($data["fecha_min"])) {?>
		<p><b>Desde <?php echo texto($data["fecha_min"])?></b></p>
	<?php }?>
	<?php if (!empty($data["fecha_max"])) {?>
		<p><b>Hasta <?php echo texto($data["fecha_max"])?></b></p>
	<?php }?>
	<hr>
	<p>
		Articulos asignados: <?php echo texto($return["data"]["asignados"])?>
	</p>

	<p>
		Articulos removidos: <?php echo texto($return["data"]["noAsignados"])?>
	</p>

	<hr>

	<?php foreach($return["data"]["estatus"] as $idx => $estatus): ?>
	<p>Estatus <?php echo texto($estatus["estado"])?>: <?php echo texto($estatus["cantidad"])?></p>
	<?php endforeach;?>
</div>