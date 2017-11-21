


<tbody id="cambiar_tbody">
	<?php foreach ($paquetes as $key => $paquete): ?>
		<tr>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Colegio]" value="<?php echo $paquete["colegio"] ?>">
				<?php echo $paquete["colegio"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Nivel]" value="<?php echo $paquete["nivele"] ?>">
				<?php echo $paquete["nivele"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Grado]" value="<?php echo $paquete["grado"] ?>">
				<?php echo $paquete["grado"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Nombre]" value="<?php echo $paquete["alumno"] ?>">
				<?php echo $paquete["alumno"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][A Paterno]" value="<?php echo $paquete["a_paterno"] ?>">
				<?php echo $paquete["a_paterno"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][A Materno]" value="<?php echo $paquete["a_materno"] ?>">
				<?php echo $paquete["a_materno"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Pedido]" value="<?php echo $paquete["pedido"] ?>">
				<?php echo $paquete["pedido"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][ID Paquete]" value="<?php echo $paquete["paquete_id"] ?>">
				<?php echo $paquete["paquete_id"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Descripcion]" value="<?php echo $paquete["descripcion"] ?>">
				<?php echo $paquete["descripcion"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Factura]" value="<?php echo $paquete["factura"] ?>">
				<?php echo $paquete["factura"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Estatus]" value="<?php echo $paquete["estatus"] ?>">
				<?php echo $paquete["estatus"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Importe]" value="<?php echo $paquete["importe"] ?>">
				<?php echo $paquete["importe"] ?>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>