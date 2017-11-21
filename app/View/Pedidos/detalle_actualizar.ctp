


<tbody id="cambiar_tbody">
	<?php foreach ($pedidos as $key => $pedido): ?>
		<tr>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Colegio]" value="<?php echo $pedido["colegio"] ?>">
				<?php echo $pedido["colegio"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Nivel]" value="<?php echo $pedido["nivele"] ?>">
				<?php echo $pedido["nivele"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Grado]" value="<?php echo $pedido["grado"] ?>">
				<?php echo $pedido["grado"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Alumno]" value="<?php echo $pedido["alumno"] ?>">
				<?php echo $pedido["alumno"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Pedido]" value="<?php echo $pedido["pedido"] ?>">
				<?php echo $pedido["pedido"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Factura]" value="<?php echo $pedido["factura"] ?>">
				<?php echo $pedido["factura"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Estatus]" value="<?php echo $pedido["estatus"] ?>">
				<?php echo $pedido["estatus"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Importe]" value="<?php echo $pedido["importe"] ?>">
				<?php echo $pedido["importe"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Forma de Pago]" value="<?php echo $pedido["forma_pago"] ?>">
				<?php echo $pedido["forma_pago"] ?>
			</td>
			<td>
				<a class='dropdown-button btn-floating waves-effect waves-light opciones_detalle btn_peque' href='#' data-activates='dropdown_<?php echo $pedido["pedido"] ?>'>
					<i class="material-icons">more_vert</i>
				</a>

				<ul id='dropdown_<?php echo $pedido["pedido"] ?>' class='dropdown-content'>

					<li><a href="/pedidos/ver_pdf/<?php echo $pedido["pedido"] ?>" target="_blank">Ver PDF</a></li>

					<?php if ($pedido["estatus"] == "No Cobrado"): ?>
						<li><a onclick="pedidoCancelarPedido(<?php echo $pedido["pedido"] ?>)">Cancelar</a></li>
					<?php endif ?>
						
					<?php if ($pedido["estatus"] == "Cobrado"): ?>
						<?php if ($pedido["factura"]): ?>
							<?php if ($this->Session->read("Auth.User.tipo") == "CCM"): ?>
								<li><a onclick="pedidoCancelarFactura(<?php echo $pedido["pedido"] ?>)">Cancelar Factura</a></li>
							<?php endif ?>
						<?php else: ?>
							<li><a href="/facturacion_datos/cambiar/<?php echo $pedido["padre_id"] ?>/<?php echo $pedido["pedido"] ?>/<?php echo $pedido["forma_pago"] ?>">Facturar</a></li>
						<?php endif ?>
					<?php endif ?>

				</ul>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>