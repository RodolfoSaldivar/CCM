


<tbody id="filtro_cambiar">
	<?php foreach ($paquetes as $keyP => $paquete): ?>

		<tr class="pointer">
			<td>
				<?php echo $paquete["Paquete"]["identificador"]; ?>
			</td>
			<td>
				<?php echo $paquete["Paquete"]["descripcion"]; ?>
			</td>
			<td>
				<?php echo $paquete["Paquete"]["colegio_nombre"]; ?>
			</td>
			<td>
				<?php echo $paquete["Paquete"]["nivele_nombre"]; ?>
			</td>
			<td>
				<?php echo $paquete["Paquete"]["grado_nombre"]; ?>
			</td>
			<td>
				<span class="decimal">
					$ <?php echo number_format($paquete["Precios"]["precio_publico"], 2); ?>
				</span>
			</td>
			<td>
				<span class="decimal">
					$ <?php echo number_format($paquete["Precios"]["precio_venta"], 2); ?>
				</span>
			</td>
			<td class="center" id="poner_switch_<?php echo $paquete["Paquete"]["id"] ?>">
				<div class="switch">
					<label>
						<input <?php if ($paquete["Paquete"]["estatus"] == 1) echo "checked"; ?> type="checkbox" name='data[Paquete][<?php echo $paquete["Paquete"]["id"] ?>]' id='paquete_<?php echo $paquete["Paquete"]["id"] ?>' value='<?php echo $paquete["Paquete"]["estatus"] ?>' onchange="activoActualizar(<?php echo $paquete["Paquete"]["id"] ?>)">
						<span class="lever"></span>
					</label>
				</div>
			</td>
			<td class="center ver_editar_acciones">
				<a href="/paquetes/ver/<?php echo $paquete["Paquete"]["id"] ?>">
					<span class="icon-ver dash-icon"></span>
				</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="hide-on-small-only" href="/paquetes/editar/<?php echo $paquete["Paquete"]["id"] ?>">
					<span class="icon-editar dash-icon"></span>
			</td>
		</tr>

	<?php endforeach ?>
</tbody>
