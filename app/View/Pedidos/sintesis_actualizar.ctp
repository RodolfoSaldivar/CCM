


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
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Cobrados]" value="<?php echo $pedido["cobrados"] ?>">
				<?php echo $pedido["cobrados"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][No Cobrados]" value="<?php echo $pedido["no_cobrados"] ?>">
				<?php echo $pedido["no_cobrados"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Cancelados]" value="<?php echo $pedido["cancelados"] ?>">
				<?php echo $pedido["cancelados"] ?>
			</td>
			<td>
				<input type="hidden" name="data[Filas][<?php echo $key ?>][Todos]" value="<?php echo $pedido["todos"] ?>">
				<?php echo $pedido["todos"] ?>
			</td>
		</tr>
	<?php endforeach ?>
</tbody>