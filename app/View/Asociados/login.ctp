

<?php $this->assign('title', 'Iniciar Sesión'); ?>

<div class="centro texto">
	<h1>Bienvenidos</h1>
</div>

<?php echo $this->Form->create() ?>

	<div class="login-square">
		<div class="login-wrapper">
			<a href="/dashboard"><img width="175" class="left rounded-corners" src="/img/ccm.png"></a>
			<a href="/asociados/agregar_padre" class="right login-right">Registrarme</a>
		</div>

		 <div class="row">
 			<div class="input-field login-center">
 				<input placeholder="Correo electrónico" name="data[Asociado][mail]" type="text" id="AsociadoMail" class="error">
 				<label for="AsociadoMail">
 					Mail
 					<label id="AsociadoMail-error" class="error validation_label" for="AsociadoMail"></label>
 				</label>
 			</div>
 		</div>

 		<div class="row">
			<div class="input-field login-center">
 				<input placeholder="Ingresar contraseña" name="data[Asociado][password]" type="password" id="AsociadoPassword" aria-required="true" class="error" aria-invalid="true">
 				<label for="AsociadoPassword">
 					Contraseña
 					<label id="AsociadoPassword-error" class="error validation_label" for="AsociadoPassword"></label>
 				</label>
 			</div>
 		</div>

 		<div class="row">
			<a href="/asociados/olvide_contrasena" class="waves-effect waves-light login-left">Olvidé contraseña</a>
				<button class="btn waves-effect waves-black right login-right" type="submit" name="action">Iniciar Sesión</button>
 		</div>
	</div>

<?php echo $this->Form->end(); ?>

<div class="navegadores">
	Se recomienda utilizar alguno de los siguientes navegadores:</br>
	</br>
	<a href="https://www.mozilla.org/en-US/firefox/products/" target="blank_"><img src="/img/firefox.png" class="browser"></a>
	<a href="https://www.google.com/chrome/" target="blank_"><img src="/img/chrome.png" class="browser"></a>
	<a href="https://support.apple.com/en-us/HT204416" target="blank_"><img src="/img/safari.png" class="browser"></a>
</div>

<div class="ienot">
	Este sitio no soporta Internet Explorer
	<img src="/img/iew.png" class="browserie">
</div>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$('#AsociadoLoginForm').validate({
		rules: {
			'data[Asociado][mail]': {
				required: true,
				alphanumeric: true
			},
			'data[Asociado][password]': {
				required: true,
				alphanumeric: true
			}
		}
	});

<?php $this->Html->scriptEnd(); ?>