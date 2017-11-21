<div class="ocontrasena-square">

		<div class="ocontrasena-wrapper">
			<a class="pointer" onclick="window.history.back();"><img width="175" class="left rounded-corners" src="/img/ccm.png"></a>
			<div class="right ocontrasena-right">Reestablecer contraseña</div>
		</div>

		<div class="ocontrasena-left">
			<h6>
				Escriba su correo electrónico y le llegará un mensaje, </br>siga las instrucciones.
			</h6>
		</div>

		<?php echo $this->Form->create() ?>

		 <div class="row">
			<div class="input-field ocontrasena-center">
				<label for="mail">
					Mail
					<label id="mail-error" class="error validation_label" for="mail"></label>
				</label>
				<input name="data[Asociado][mail]" type="text" id="mail" aria-required="true" class="error" aria-invalid="true">
			</div>
		</div>

		<div class="row">
			<button class="btn waves-effect waves-black right ocontrasena-right" type="submit" name="action">Enviar correo</button>
		</div>

</div>

<?php echo $this->Form->end(); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#AsociadoOlvideContrasenaForm').validate({
		rules: {
			'data[Asociado][mail]': {
				required: true,
				alphanumeric: true,
				email: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>
