


<tr id="tr_<?php echo $cont_articulo ?>">
	<input type="hidden" name="data[ArticulosPaquete][<?php echo $cont_articulo ?>][id]" value="<?php echo $articulo["id"] ?>">
	<td>
		<input id="articulo_<?php echo $cont_articulo ?>" name="data[ArticulosPaquete][<?php echo $cont_articulo ?>][identificador]" type="text" value="<?php echo $identificador ?>">
		<label id="articulo_<?php echo $cont_articulo ?>-error" class="error validation_label" for="articulo_<?php echo $cont_articulo ?>">* Requerido</label>
	</td>
	<td>
		<?php echo $articulo["descripcion"] ?>
	</td>
	<td>
		<?php echo $familia_nombre ?>
	</td>
	<td>
		<input id="cantidad_<?php echo $cont_articulo ?>" name="data[ArticulosPaquete][<?php echo $cont_articulo ?>][cantidad]" type="number" min="0" step="any" class="todas_cantidad">
		<label id="cantidad_<?php echo $cont_articulo ?>-error" class="error validation_label" for="cantidad_<?php echo $cont_articulo ?>">* Requerido</label>
	</td>
	<td>
		<?php echo $precio_venta ?>
	</td>
	<td>
		<input id="precio_publico_<?php echo $cont_articulo ?>" name="data[ArticulosPaquete][<?php echo $cont_articulo ?>][precio_publico]" type="number" min="0" step="any" value="<?php echo $precio_publico_default ?>" class="todos_precio_publico">
		<label id="precio_publico_<?php echo $cont_articulo ?>-error" class="error validation_label" for="precio_publico_<?php echo $cont_articulo ?>">* Requerido</label>
	</td>
	<td>
		<div id="publico_siva_<?php echo $cont_articulo ?>">
			<?php echo number_format($precio_publico_default / (1 + $iva / 100), 2) ?>
		</div>
	</td>
	<td>
		<div id="iva_<?php echo $cont_articulo ?>">
			<?php echo $iva ?>
		</div>
	</td>
	<?php if ($cont_articulo != 1): ?>
		<td>
			<a id="remover_<?php echo $cont_articulo; ?>" class="btn-floating btn-small waves-effect waves-black white"><i class="material-icons">remove</i></a>
		</td>
	<?php else: ?>
		<td></td>
	<?php endif ?>
</tr>