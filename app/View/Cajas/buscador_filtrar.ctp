


<tbody id="filtro_cambiar">
	<?php foreach ($pedidos as $key => $pedido): ?>

		<tr>
			<td>
				<?php if ($caja_abierta): ?>
					<a href="/cajas/cobrar/<?php echo $pedido["Pedido"]["id"] ?>"><span class="icon-pago"><span></a>
				<?php else: ?>
					<a class="boton-desactivado"><span class="icon-pago"><span></a>
				<?php endif ?>
			</td>
			<td>
				<?php echo $pedido["Pedido"]["fecha_pedido"] ?>
			</td>
			<td class="center">
				<?php echo $pedido["Pedido"]["id"] ?>
			</td>
			<td class="right">
				$ <?php echo number_format($pedido["Pedido"]["importe"], 2) ?>
			</td>
			<td>
				<?php echo $pedido["CicloHijo"]["padre_nombre"] ?>
			</td>
			<td>
				<?php echo $pedido["CicloHijo"]["hijo_nombre"] ?>
			</td>
		</tr>

	<?php endforeach ?>
</tbody>
