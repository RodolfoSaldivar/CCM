


<tbody id="cambiar_tbody">
	<?php foreach ($facturas as $key => $factura): ?>
		<tr>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Colegio]" value="<?php echo $factura["colegio"] ?>">
				<?php echo $factura["colegio"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Pedido]" value="<?php echo $factura["pedido"] ?>">
				<?php echo $factura["pedido"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Factura]" value="<?php echo $factura["factura"] ?>">
				<?php echo $factura["factura"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Razon Social]" value="<?php echo $factura["razon_social"] ?>">
				<?php echo $factura["razon_social"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][RFC]" value="<?php echo $factura["rfc"] ?>">
				<?php echo $factura["rfc"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Fecha]" value="<?php echo $factura["fecha"] ?>">
				<?php echo $factura["fecha"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Alumno]" value="<?php echo $factura["alumno"] ?>">
				<?php echo $factura["alumno"] ?>
			</td>
			<td class="right-align">
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Importe]" value="<?php echo $factura["importe"] ?>">
				<?php echo $factura["importe"] ?>
			</td>
			<td class="right-align">
				<input type="hidden" name="data[Filas][<?php echo $key ?>][IVA]" value="<?php echo $factura["iva"] ?>">
				<?php echo $factura["iva"] ?>
			</td>
			<td class="right-align">
				<input type="hidden" name="data[Filas][<?php echo $key ?>][TOTAL]" value="<?php echo $factura["total"] ?>">
				<?php echo $factura["total"] ?>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>