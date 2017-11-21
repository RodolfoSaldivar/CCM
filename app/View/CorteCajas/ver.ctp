


<?php $this->set("breadcrumbs",
	'<a href="/cajas" class="breadcrumb">Cajas</a>
	<a href="/corte_cajas" class="breadcrumb">Ver Cortes</a>
	<a class="breadcrumb">'.$asociado_nombre.'</a>'
) ?>


<div class="row center">
	<div class="">
		Comercializadora Colegios México <br>
		Corte de Caja <br>
		<?php echo $corte["CorteCaja"]["fecha_ap"]." - ".$corte["CorteCaja"]["fecha_cierre"] ?> <br>
		<?php echo $asociado_nombre ?>
	</div>
</div>


<form action="/dashboard/descargar_excel" method="post" accept-charset="utf-8">	
	<div class="row">
		<div class="col s12 m9 l6">

			<button id="btn_submit" class="btn waves-effect waves-light" type="submit">
				Excel
			</button>

			<input type="hidden" name="data[nombre_archivo]" value="Sintesis_<?php echo str_replace(" ", "_", $asociado_nombre)."_".$corte["CorteCaja"]["fecha_ap"]."_".$corte["CorteCaja"]["fecha_cierre"] ?>">

			<table class="bordered">
				<thead class="top-tabla">
					<tr>
						<th>Forma de Pago</th>
						<th>Cobrado</th>
						<th>Facturado</th>
						<th>No Facturado</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($sintesis as $key => $metodo): ?>
						<tr>
							<td>
								<input type="hidden" name="data[Filas][<?php echo $key ?>][Forma]" value="<?php echo $key ?>">
								<?php echo $key ?>
							</td>
							<td class="right-text">
								<input type="hidden" name="data[Filas][<?php echo $key ?>][Cobrado]" value="<?php echo $metodo["f"] + $metodo["nf"] ?>">
								<?php echo number_format($metodo["f"] + $metodo["nf"], 2) ?>
							</td>
							<td class="right-text">
								<input type="hidden" name="data[Filas][<?php echo $key ?>][Facturado]" value="<?php echo $metodo["f"] ?>">
								<?php echo number_format($metodo["f"], 2) ?>
							</td>
							<td class="right-text">
								<input type="hidden" name="data[Filas][<?php echo $key ?>][No Facturado]" value="<?php echo $metodo["nf"] ?>">
								<?php echo number_format($metodo["nf"], 2) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

		</div>
	</div>
</form>

<br><br><br>

<form action="/dashboard/descargar_excel" method="post" accept-charset="utf-8">
	<div class="row">

		<button id="btn_submit" class="btn waves-effect waves-light" type="submit">
			Excel
		</button>

		<input type="hidden" name="data[nombre_archivo]" value="Detalle_<?php echo str_replace(" ", "_", $asociado_nombre)."_".$corte["CorteCaja"]["fecha_ap"]."_".$corte["CorteCaja"]["fecha_cierre"] ?>">

		<table class="bordered">
			<thead class="top-tabla">
				<tr>
					<th>Fecha</th>
					<th>Forma Pago</th>
					<th>Cobrado</th>
					<th>Facturado</th>
					<th>No Facturado</th>
					<th># Pedido</th>
					<th>Factura</th>
					<th>Nivel</th>
					<th>Grado</th>
					<th>Alumno</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($pagos as $key => $pago): ?>
					<tr>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Fecha]" value="<?php echo $pago["Pago"]["fecha_pago"] ?>">
							<?php echo $pago["Pago"]["fecha_pago"] ?>
						</td>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Forma]" value="<?php echo $pago["Pago"]["forma_pago"] ?>">
							<?php echo $pago["Pago"]["forma_pago"] ?>
						</td>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Cobrado]" value="<?php echo $pago["Pago"]["importe"] ?>">
							<?php echo number_format($pago["Pago"]["importe"], 2) ?>
						</td>

						<?php if ($pago["Pedido"]["fecha_facturado"]): ?>
							<td>
								<input type="hidden" name="data[Filas][<?php echo $key ?>][Facturado]" value="<?php echo $pago["Pago"]["importe"] ?>">
								<?php echo number_format($pago["Pago"]["importe"], 2) ?>
							</td>
							<td>
								<input type="hidden" name="data[Filas][<?php echo $key ?>][No Facturado]" value="<?php echo "0" ?>">
								0.00
							</td>
						<?php else: ?>
							<td>
								<input type="hidden" name="data[Filas][<?php echo $key ?>][Facturado]" value="<?php echo "0" ?>">
								0.00
							</td>
							<td>
								<input type="hidden" name="data[Filas][<?php echo $key ?>][No Facturado]" value="<?php echo $pago["Pago"]["importe"] ?>">
								<?php echo number_format($pago["Pago"]["importe"], 2) ?>
							</td>
						<?php endif ?>
							
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Pedido]" value="<?php echo $pago["Pago"]["pedido_id"] ?>">
							<?php echo $pago["Pago"]["pedido_id"] ?>
						</td>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Factura]" value="<?php echo $pago["Pedido"]["fecha_facturado"] ?>">
							<?php echo $pago["Pedido"]["fecha_facturado"] ?>
						</td>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Nivel]" value="<?php echo $pago["Ciclo"]["nivel"] ?>">
							<?php echo $pago["Ciclo"]["nivel"] ?>
						</td>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Grado]" value="<?php echo $pago["Ciclo"]["grado"] ?>">
							<?php echo $pago["Ciclo"]["grado"] ?>
						</td>
						<td>
							<input type="hidden" name="data[Filas][<?php echo $key ?>][Alumno]" value="<?php echo $pago["Ciclo"]["alumno"] ?>">
							<?php echo $pago["Ciclo"]["alumno"] ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</form>

<br><br><br>