<div>
	<form class="modificar-user">
		<input type="hidden" name="nick" data-id="<?php echo texto($user["data"]["nick"]);?>">

		<br>

		<span>Estatus:</span>
		<select name="estatus">
			<?php 
			foreach(User::getAllStatus() as $idx => $estatus):
			?>
			<option value="<?php echo texto($estatus["id"])?>" <?php 
				echo $user["data"]["idStatus"] == $estatus["id"] ? "selected" : "";			
			?>><?php echo texto($estatus["nombre"])?></option>
			<?php 
			endforeach;
			?>
		</select>
		<br>
		<p>
			Lista de accesos 
		</p>
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
				<td><input type="checkbox" name="access[]" <?php 

				echo array_search($idx, $user["data"]["access"]) !== false ? "checked": "";

				?> value="<?php echo texto($value)?>"></td>
			</tr>

			<?php
			endforeach;
			?>
		</table>

		<input type="button" class="reset-password" value="Reiniciar contraseña">
   		<input type="submit" value="Modificar usuario">
	</form>
</div>