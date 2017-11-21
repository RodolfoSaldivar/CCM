


<div id="niveles_grados">

	<div class="input-field col s12 m6 l4">
		<select id="select_niveles" name="data[<?php echo $tabla ?>][nivele_id]" <?php if (!$colegio_id) echo "disabled"; ?>>

			<?php if ($nivele_id): ?>
				<option value="nada" disabled>Nivel</option>
			<?php else: ?>
				<option value="nada" disabled selected>Nivel</option>
			<?php endif ?>
			
			<?php foreach ($niveles as $key => $nivel): ?>
				
				<?php if ($nivele_id == $nivel["Nivele"]["id"]): ?>
					<option selected value="<?php echo $nivel["Nivele"]["id"] ?>"><?php echo $nivel["CatalogoNivele"]["nombre"] ?></option>
				<?php else: ?>
					<option value="<?php echo $nivel["Nivele"]["id"] ?>"><?php echo $nivel["CatalogoNivele"]["nombre"] ?></option>
				<?php endif ?>

			<?php endforeach ?>
		</select>
		<label id="select_niveles-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
	</div>

	<div class="input-field col s12 m6 l4" id="div_grados">
		<select id="select_grados" disabled="" name="data[<?php echo $tabla ?>][grado_id]">
			<option value="nada" disabled selected>Grado</option>
		</select>
	</div>

</div>