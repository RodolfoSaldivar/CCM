


<div class="input-field col s12 m6 l4" id="div_grados">
	<select id="select_grados" name="data[<?php echo $tabla ?>][grado_id]">

		<?php if ($grado_id): ?>
			<option value="nada" disabled>Grado</option>
		<?php else: ?>
			<option value="nada" disabled selected>Grado</option>
		<?php endif ?>

		<?php foreach ($grados as $key => $grado): ?>

			<?php if ($grado_id == $grado["Grado"]["id"]): ?>
				<option selected value="<?php echo $grado["Grado"]["id"] ?>"><?php echo $grado["CatalogoGrado"]["nombre"] ?></option>
			<?php else: ?>
				<option value="<?php echo $grado["Grado"]["id"] ?>"><?php echo $grado["CatalogoGrado"]["nombre"] ?></option>
			<?php endif ?>

		<?php endforeach ?>

	</select>
	<label id="select_grados-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
</div>