<div>
	<form class="create-user">
		<h1 class="title">
			Crear usuario
		</h1>

		<span>Nick: </span> <input type="text" name="nick" placeholder="Nick" required>
		<br>
		<br>
		<span>Asignar a: </span> <input type="number" name="cedula" placeholder="Cedula" required>
		<br>

		<table>
			<tr>
				<th>
					Nombre
				</th>
				<th>
					Conceder
				</th>
			</tr>
			<?php 

			foreach(Router::printAccess() as $idx => $value):
			?>

			<tr>
				<td><span><?php echo texto($value)?></span></td>
				<td><input type="checkbox" name="access[]" value="<?php echo texto($value)?>"></td>
			</tr>

			<?php
			endforeach;
			?>
		</table>
   		<input type="submit" value="Crear usuario">
	</form>
</div>