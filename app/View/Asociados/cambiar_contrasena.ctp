
<div class="ccontrasena-square">

		<div class="ccontrasena-wrapper">
			<a class="pointer" onclick="window.history.back();"><img width="175" class="left rounded-corners" src="/img/ccm.png"></a>
			<div class="right ccontrasena-right">Cambiar contraseña</div>
		</div>

		<div class="ccontrasena-left">
			<h6>
				Escriba la contraseña actual </br>y después a la que quiere cambiar.
			</h6>
		</div>

		<?php echo $this->Form->create() ?>


		 <div class="row">
			<div class="input-field ccontrasena-center">
				<label for="actual">
					Contraseña Actual
					<label id="actual-error" class="error validation_label" for="actual"></label>
				</label>
				<input name="data[Asociado][actual]" type="password" id="actual" aria-required="true" class="error" aria-invalid="true" required>
			</div>
		</div>

		<div class="row">
			<div class="input-field ccontrasena-center">
				<label for="nueva">
					Contraseña Nueva
					<label id="nueva-error" class="error validation_label" for="nueva"></label>
				</label>
				<input name="data[Asociado][nueva]" type="password" id="nueva" aria-required="true" class="error" aria-invalid="true" required>
			</div>
		</div>

		<div class="row">
			<div class="input-field ccontrasena-center">
				<label for="repetida">
					Confirmar Contraseña
					<label id="repetida-error" class="error validation_label" for="repetida"></label>
				</label>
				<input name="data[Asociado][repetida]" type="password" id="repetida" aria-required="true" class="error" aria-invalid="true" required>
			</div>
		</div>

		<div class="row">
			<button class="btn waves-effect waves-black right ccontrasena-right" type="submit" name="action">Cambiar Contraseña</button>
		</div>

</div>

<?php echo $this->Form->end(); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#AsociadoCambiarContrasenaForm').validate({
		rules: {
			'data[Asociado][nueva]': {
				required: true,
				alphanumeric: true,
      			minlength: 8,
      			maxlength: 20
			},
			'data[Asociado][repetida]': {
				required: true,
				equalTo: '#nueva'
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>
