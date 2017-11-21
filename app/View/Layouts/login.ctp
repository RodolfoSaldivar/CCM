

<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>

	<?php echo $this->Html->css('google_icons.css'); ?>
	<?php echo $this->Html->css('materialize.min.css'); ?>
	<?php echo $this->Html->css('style.css'); ?>
	<?php echo $this->Html->css('style-diego.css'); ?>

	<?php
		echo $this->Html->meta('icon');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body class="fondo-de-imagen-en-home">


	<!--div class="login_layout container"-->
		<?php echo $this->fetch('content'); ?>


	<?php echo $this->Html->script('jquery-2.1.1.min.js'); ?>
	<?php echo $this->Html->script('materialize.min.js'); ?>
	<?php echo $this->Html->script('jquery.validate.min.js'); ?>
	<?php echo $this->Html->script('alphanumeric.js'); ?>
	<?php echo $this->fetch('script'); ?>

	<script type="text/javascript">

		$.validator.messages.required = '*Requerido';
		$.validator.messages.number = '*Número inválido';
		$.validator.messages.equalTo = '*Mismo valor';
		$.validator.messages.alphanumeric = '*Omitir ("), '+"(')"+', ([), (]) y enters';
		$.validator.messages.email = '*Correo electrónico invalido';
		$.validator.messages.min = '*Igual o mayor a 0';
		$.validator.messages.minlength = "*Mínimo {0} caracteres";
		$.validator.messages.maxlength = "*Máximo {0} caracteres";

		<?php echo $this->Session->flash('flash', array(
		    'element' => 'toast'
		)); ?>

	</script>
</body>
</html>
