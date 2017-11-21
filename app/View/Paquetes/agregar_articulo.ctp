


<tr id="tr_<?php echo $cont_articulo ?>">
	<td>
		<input id="articulo_<?php echo $cont_articulo ?>" name="data[ArticulosPaquete][<?php echo $cont_articulo ?>][identificador]" type="text" placeholder="Artículo ID">
		<label id="articulo_<?php echo $cont_articulo ?>-error" class="error validation_label" for="articulo_<?php echo $cont_articulo ?>">* Requerido</label>
	</td>
	<td>

	</td>
	<td>

	</td>
	<td>

	</td>
	<td>

	</td>
	<td>

	</td>
	<td>

	</td>
	<?php if ($cont_articulo != 1): ?>
		<td>
			<a id="remover_<?php echo $cont_articulo; ?>" class="btn-floating btn-small waves-effect waves-black"><i class="material-icons">remove</i></a>
		</td>
	<?php endif ?>
</tr>
