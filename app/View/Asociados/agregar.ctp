


<?php $this->set("breadcrumbs",
	'<a href="/asociados" class="breadcrumb">Asociados</a>
	<a class="breadcrumb">Agregar asociado</a>'
) ?>



<?php echo $this->Form->create(); ?>

	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="nombre" name="data[Asociado][nombre]" type="text">
			<label for="nombre">
				Nombre
				<label id="nombre-error" class="error validation_label" for="nombre"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="a_paterno" name="data[Asociado][a_paterno]" type="text">
			<label for="a_paterno">
				Apellido Paterno
				<label id="a_paterno-error" class="error validation_label" for="a_paterno"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="a_materno" name="data[Asociado][a_materno]" type="text">
			<label for="a_materno">
				Apellido Materno
				<label id="a_materno-error" class="error validation_label" for="a_materno"></label>
			</label>
		</div>
	</div>


	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="mail" name="data[Asociado][mail]" type="text">
			<label for="mail">
				Correo Electrónico
				<label id="mail-error" class="error validation_label" for="mail"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="password" name="data[Asociado][password]" type="password">
			<label for="password">
				Contraseña
				<label id="password-error" class="error validation_label" for="password"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="celular" name="data[Asociado][celular]" type="text">
			<label for="celular">
				Celular
				<label id="celular-error" class="error validation_label" for="celular"></label>
			</label>
		</div>
	</div>


	<div class="row">
		<div class="input-field col s12 m6 l4">
			<select name="data[Asociado][tipo]" id="tipo">
				<option value="nada" disabled selected>Tipo</option>
				<option value="CCM">CCM</option>
				<option value="Cajero">Cajero</option>
				<option value="Director">Director</option>
			</select>
			<label><label id="tipo-error" class="validation_label" for="tipo">*Requerido</label></label>
		</div>

		<div id="div_colegio" class="input-field col s12 m6 l4">
			<select name="data[Asociado][colegio_id]" id="colegio_id">
				<option value="nada" disabled selected>Colegio</option>
				<?php foreach ($colegios as $key => $colegio): ?>
					<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
				<?php endforeach ?>
			</select>
			<label><label id="colegio_id-error" class="validation_label" for="colegio_id">*Requerido</label></label>
		</div>
	</div>

	<button class="btn waves-effect waves-light right" type="submit" name="action">
		Guardar
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_asoc").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
	});

	$('#AsociadoAgregarForm').validate({
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
      			minlength: 8,
      			maxlength: 20
			},
			'data[Asociado][celular]': {
				alphanumeric: true
			}
		}
	});

	$('#AsociadoAgregarForm').submit(function()
	{
		if($('#tipo option:selected').val() == "nada")
		{
			$("#tipo-error").css("display", "initial");
			event.preventDefault();
		}
		if($('#tipo option:selected').val() != "CCM")
			if($('#colegio_id option:selected').val() == "nada")
			{
				$("#colegio_id-error").css("display", "initial");
				event.preventDefault();
			}
	});

	$(document).on("change", "#tipo", function()
	{
		$("#tipo-error").css("display", "none");
		if ($(this).val() == "CCM")
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
