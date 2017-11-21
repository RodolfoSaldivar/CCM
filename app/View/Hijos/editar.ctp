

<?php echo $this->Form->create(); ?>

	<?php echo $this->Form->hidden('id', array('value' => $hijo[0]['Hijo']['id'], 'name' => 'data[Hijo][id]')); ?>

	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="nombre" name="data[Hijo][nombre]" type="text" value="<?php echo $hijo[0]['Hijo']['nombre'] ?>">
			<label for="nombre">
				Nombre
				<label id="nombre-error" class="error validation_label" for="nombre"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="a_paterno" name="data[Hijo][a_paterno]" type="text" value="<?php echo $hijo[0]['Hijo']['a_paterno'] ?>">
			<label for="a_paterno">
				Apellido Paterno
				<label id="a_paterno-error" class="error validation_label" for="a_paterno"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="a_materno" name="data[Hijo][a_materno]" type="text" value="<?php echo $hijo[0]['Hijo']['a_materno'] ?>">
			<label for="a_materno">
				Apellido Materno
				<label id="a_materno-error" class="error validation_label" for="a_materno"></label>
			</label>
		</div>
	</div>


	<div class="row margin_nada">
		<div class="col s12 m6 l4 offset-m6 offset-l8 red-text">
			Grado al que cursará
		</div>
	</div>
	<div class="row">
		<div class="input-field col s12 m6 l4">
			<select id="select_colegios" name="data[CicloHijo][colegio_id]">
				<option value="nada" disabled selected>Colegio</option>
				<?php foreach ($colegios as $key => $colegio): ?>

					<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>

				<?php endforeach ?>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>

		<div id="niveles_grados">

			<div class="input-field col s12 m6 l4">
				<select id="select_niveles" disabled="" name="data[CicloHijo][nivele_id]">
					<option value="nada" disabled selected>Nivel</option>
				</select>
			</div>

			<div class="input-field col s12 m6 l4" id="div_grados">
				<select id="select_grados" disabled="" name="data[CicloHijo][grado_id]">
					<option value="nada" disabled selected>Grado</option>
				</select>
			</div>

		</div>
	</div>



	<button class="btn waves-effect waves-light right" type="submit" name="action">
		Guardar
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->script('colegios_niveles_grados', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_sub_1").removeClass("href_desactivado");

	$(document).ready(function() {
		$('select').material_select();
	});

	$('#HijoEditarForm').validate({
		rules: {
			'data[Hijo][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[Hijo][a_paterno]': {
				required: true,
				alphanumeric: true
			},
			'data[Hijo][a_materno]': {
				required: true,
				alphanumeric: true
			}
		}
	});

	$('#HijoEditarForm').submit(function(event)
	{
		var correcto = true;

		if(!$('#select_colegios').val()){
			$("#select_colegios-error").css("display", "initial");
			correcto = false
		}
		if(!$('#select_niveles').val()){
			$("#select_niveles-error").css("display", "initial");
			correcto = false
		}
		if(!$('#select_grados').val()){
			$("#select_grados-error").css("display", "initial");
			correcto = false
		}

		if (!correcto)
			event.preventDefault();
	});

	$(document).on("change", "#select_grados", function()
	{
		$("#select_grados-error").css("display", "none");
	});


	var tabla_nombre = "CicloHijo";

	<?php if (!empty($colegio_id)): ?>

		$('#select_colegios').val(<?php echo $colegio_id ?>);
		dropdownNiveles(<?php echo $nivele_id ?>, <?php echo $grado_id ?>);

	<?php endif ?>


<?php $this->Html->scriptEnd(); ?>
