


<div id="niveles_grados">

	<div class="input-field col s12 m6 l4">
		<select id="select_niveles">

			<option value="nada" disabled selected>Nivel</option>

			<option value="todos">Todos</option>

			<?php foreach ($niveles as $key => $nivel): ?>
				
				<option value="<?php echo $nivel["Nivele"]["id"] ?>"><?php echo $nivel["CatalogoNivele"]["nombre"] ?></option>

			<?php endforeach ?>
		</select>
		<label id="select_niveles-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
	</div>

	<div class="input-field col s12 m6 l4" id="div_grados">
		<select id="select_grados" disabled="">
			<option value="nada" disabled selected>Grado</option>
		</select>
	</div>

</div>