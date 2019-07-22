<div class="container">
	<h1 class="title-simple">Reportes</h1>

	<select class="select-report">
		<option value="" selected>-</option>
		<option value="serial">Por serial</option>
		<option value="general">General</option>
		<option value="estadistica">Estadisticas</option>
	</select>

	<hr>

	<div class="reporte-serial">
		<form>
			<span>Serial: </span>
			<input type="text" name="serial" placeholder="Serial">

			<span>
				Fecha minima de busqueda:
			</span>
			<input type="text" name="fecha-min" id="datepicker1" placeholder="Fecha minima" readonly>
			<p class="remove-before">x</p>

			<span>
				Fecha maxima de busqueda:
			</span>
			<input type="text" name="fecha-max" id="datepicker2" placeholder="Fecha minima" readonly>
			<p class="remove-before">x</p>

			<input type="submit" value="Buscar">
		</form>
	</div>

	<div class="reporte-general">
		<form>
			<span>
				Fecha minima de busqueda:
			</span>
			<input type="text" name="fecha-min" id="datepicker3" placeholder="Fecha minima" readonly>
			<p class="remove-before">x</p>

			<span>
				Fecha maxima de busqueda:
			</span>
			<input type="text" name="fecha-max" id="datepicker4" placeholder="Fecha minima" readonly>
			<p class="remove-before">x</p>

			<input type="submit" value="Buscar">
		</form>
	</div>

	<div class="reporte-estadistica">
		<form>
			<span>
				Fecha minima de busqueda:
			</span>
			<input type="text" name="fecha-min" id="datepicker5" placeholder="Fecha minima" readonly>
			<p class="remove-before">x</p>

			<span>
				Fecha maxima de busqueda:
			</span>
			<input type="text" name="fecha-max" id="datepicker6" placeholder="Fecha minima" readonly>
			<p class="remove-before">x</p>

			<input type="submit" value="Buscar">
		</form>
	</div>
	<hr>
	<br>
	<div class="reporte-data">
	</div>
	<input type="button" class="print-report" value="Imprimir">
</div>