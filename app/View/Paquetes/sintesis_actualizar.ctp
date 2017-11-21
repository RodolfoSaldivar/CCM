


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
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Paquete ID]" value="<?php echo $paquete["paquete_id"] ?>">
				<?php echo $paquete["paquete_id"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Descripcion]" value="<?php echo $paquete["descripcion"] ?>">
				<?php echo $paquete["descripcion"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Cobrados]" value="<?php echo $paquete["cobrados"] ?>">
				<?php echo $paquete["cobrados"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][No Cobrados]" value="<?php echo $paquete["no_cobrados"] ?>">
				<?php echo $paquete["no_cobrados"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Cancelados]" value="<?php echo $paquete["cancelados"] ?>">
				<?php echo $paquete["cancelados"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Todos]" value="<?php echo $paquete["todos"] ?>">
				<?php echo $paquete["todos"] ?>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>