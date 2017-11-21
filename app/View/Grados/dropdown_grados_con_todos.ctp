


<div class="input-field col s12 m6 l4" id="div_grados">
	<select id="select_grados" <?php if ($nivele_id == "todos") echo "disabled" ?>>

		<option value="nada" disabled selected>Grado</option>

		<option value="todos">Todos</option>

		<?php foreach ($grados as $key => $grado): ?>

			<option value="<?php echo $grado["Grado"]["id"] ?>"><?php echo $grado["CatalogoGrado"]["nombre"] ?></option>

		<?php endforeach ?>

	</select>
	<label id="select_grados-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
</div>