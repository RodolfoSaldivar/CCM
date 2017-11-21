


<?php $this->set("breadcrumbs",
	'<a href="/colegios" class="breadcrumb">Colegios</a>
	<a href="/colegios/agregar" class="breadcrumb">Agregar colegio</a>
	<a class="breadcrumb">Agregar director</a>'
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
			<input id="confirma_mail" name="data[Asociado][confirma_mail]" type="text">
			<label for="confirma_mail">
				Confirma Correo
				<label id="confirma_mail-error" class="error validation_label" for="confirma_mail"></label>
			</label>
		</div>
	</div>


	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="password" name="data[Asociado][password]" type="password">
			<label for="password">
				Contraseña
				<label id="password-error" class="error validation_label" for="password"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="confirma_pass" name="data[Asociado][confirma_pass]" type="password">
			<label for="confirma_pass">
				Confirma Contraseña
				<label id="confirma_pass-error" class="error validation_label" for="confirma_pass"></label>
			</label>
		</div>
	</div>


	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="celular" name="data[Asociado][celular]" type="text">
			<label for="celular">
				Celular
				<label id="celular-error" class="error validation_label" for="celular"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="confirma_celular" name="data[Asociado][confirma_celular]" type="text">
			<label for="confirma_celular">
				Confirma Celular
				<label id="confirma_celular-error" class="error validation_label" for="confirma_celular"></label>
			</label>
		</div>
	</div>

	<button class="btn waves-effect waves-light right" type="submit" name="action">
		Agregar director
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_cole").addClass("activado");

	$('#AsociadoAgregarDirectorForm').validate({
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
			'data[Asociado][confirma_mail]': {
				required: true,
				alphanumeric: true,
				email: true,
				equalTo: '#mail'
			},
			'data[Asociado][password]': {
				required: true,
				alphanumeric: true,
      			minlength: 8,
      			maxlength: 20
			},
			'data[Asociado][confirma_pass]': {
				required: true,
				alphanumeric: true,
				equalTo: '#password'
			},
			'data[Asociado][celular]': {
				required: true,
				alphanumeric: true
			},
			'data[Asociado][confirma_celular]': {
				required: true,
				alphanumeric: true,
				equalTo: '#celular'
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>
