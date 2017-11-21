


<?php $this->set("breadcrumbs",
	'<a href="/asociados" class="breadcrumb">Asociados</a>
	<a class="breadcrumb">Editar</a>'
) ?>



<?php echo $this->Form->create(); ?>

	<?php echo $this->Form->hidden('id', array('value' => $asociado["id"], 'name' => 'data[Asociado][id]')); ?>

	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="nombre" name="data[Asociado][nombre]" type="text" value="<?php echo $asociado["nombre"] ?>">
			<label for="nombre">
				Nombre
				<label id="nombre-error" class="error validation_label" for="nombre"></label>
			</label>
		</div>
		
		<div class="input-field col s12 m6 l4">
			<input id="a_paterno" name="data[Asociado][a_paterno]" type="text" value="<?php echo $asociado["a_paterno"] ?>">
			<label for="a_paterno">
				Apellido Paterno
				<label id="a_paterno-error" class="error validation_label" for="a_paterno"></label>
			</label>
		</div>
		
		<div class="input-field col s12 m6 l4">
			<input id="a_materno" name="data[Asociado][a_materno]" type="text" value="<?php echo $asociado["a_materno"] ?>">
			<label for="a_materno">
				Apellido Materno
				<label id="a_materno-error" class="error validation_label" for="a_materno"></label>
			</label>
		</div>
	</div>


	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input readonly="" id="mail" name="data[Asociado][mail]" type="text" value="<?php echo $asociado["mail"] ?>">
			<label for="mail">
				Correo Electrónico
				<label id="mail-error" class="error validation_label" for="mail"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="password" name="data[Asociado][password]" type="text" value="<?php echo $asociado["password"] ?>">
			<label for="password">
				Contraseña
				<label id="password-error" class="error validation_label" for="password"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="celular" name="data[Asociado][celular]" type="text" value="<?php echo $asociado["celular"] ?>">
			<label for="celular">
				Celular
				<label id="celular-error" class="error validation_label" for="celular"></label>
			</label>
		</div>
	</div>


	<div class="row">
		<div class="input-field col s12 m6 l4">
			<select name="data[Asociado][tipo]" id="tipo">
				<option <?php if ($asociado["tipo"] == "CCM") echo "selected" ?> value="CCM">CCM</option>
				<option <?php if ($asociado["tipo"] == "Cajero") echo "selected" ?> value="Cajero">Cajero</option>
				<option <?php if ($asociado["tipo"] == "Director") echo "selected" ?> value="Director">Director</option>
				<option <?php if ($asociado["tipo"] == "Padre") echo "selected" ?> value="Padre">Padre</option>
			</select>
			<label>Tipo <label id="tipo-error" class="validation_label" for="tipo">*Requerido</label></label>
		</div>

		<div id="div_colegio" class="input-field col s12 m6 l4 <?php if ($asociado["tipo"] != "Cajero" && $asociado["tipo"] != "Director") echo "hide" ?>">
			<select name="data[Asociado][colegio_id]" id="colegio_id">
				<option value="nada" disabled selected>Colegio</option>
				<?php foreach ($colegios as $key => $colegio): ?>
					<option <?php if ($asociado["colegio_id"] == $colegio["Colegio"]["id"]) echo "selected" ?> value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
				<?php endforeach ?>
			</select>
			<label><label id="colegio_id-error" class="validation_label" for="colegio_id">*Requerido</label></label>
		</div>
	</div>

	<button class="btn waves-effect waves-light" type="submit" name="action">
		Guardar<i class="material-icons right">send</i>
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
	});

	$('#AsociadoEditarForm').validate({
		rules: {
			'data[Asociado][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[Asociado][a_paterno]': {
				required: true,
				alphanumeric: true
			},
			'data[Asociado][a_materno]': {
				required: true,
				alphanumeric: true
			},
			'data[Asociado][mail]': {
				required: true,
				alphanumeric: true,
				email: true	
			},
			'data[Asociado][password]': {
				required: true,
				alphanumeric: true,
      			minlength: 8
			},
			'data[Asociado][celular]': {
				alphanumeric: true
			}
		}
	});

	$('#AsociadoEditarForm').submit(function()
	{
		if($('#tipo option:selected').val() == "nada")
		{
			$("#tipo-error").css("display", "initial");
			event.preventDefault();
		}
		if($('#tipo option:selected').val() == "Cajero" && $('#tipo option:selected').val() == "Director")
			if($('#colegio_id option:selected').val() == "nada")
			{
				$("#colegio_id-error").css("display", "initial");
				event.preventDefault();
			}
	});

	$(document).on("change", "#tipo", function()
	{
		$("#tipo-error").css("display", "none");
		if ($(this).val() != "Cajero" && $(this).val() != "Director")
		{
			$("#div_colegio").addClass("hide");
			$('select').material_select();
		}
		else
		{
			$("#div_colegio").removeClass("hide");
			$('select').material_select();
		}
	});

	$(document).on("change", "#colegio_id", function()
	{
		$("#colegio_id-error").css("display", "none");
	});

<?php $this->Html->scriptEnd(); ?>