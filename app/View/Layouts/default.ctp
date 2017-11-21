

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
	<?php echo $this->Html->css('style-iconos.css'); ?>


	<?php
		echo $this->Html->meta('icon');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

	<nav class="nav-asociados">
		<div class="nav-wrapper">
			<?php if ($this->Session->read("Auth.User.tipo") != "Padre"): ?>
				<a href="/dashboard"><img width="175" class="left" src="/img/ccm.png"></a>
			<?php else: ?>
				<a href=""><img width="175" class="left" src="/img/ccm.png"></a>
			<?php endif ?>
			<a href="#" data-activates="mobile-demo" class="button-collapse right">
				<i class="material-icons">menu</i>
			</a>
			<ul class="hide-on-med-and-down">

				<li>
					<div class="seccion">
						<a id="menu_sub_1" class="href_desactivado">
							<span class="icon-hijos"></span>
							Hijos
						</a>
					</div>
				</li>
				<li>
					<div class="seccion">
						<a id="menu_sub_2" class="href_desactivado">
							<span class="icon-materiales"></span>
							Materiales
						</a>
					</div>
				</li>
				<li>
					<div class="seccion">
						<a id="menu_sub_3" class="href_desactivado">
							<span class="icon-pago"></span>
							Pago
						</a>
					</div>
				</li>
				<li>
					<div class="seccion">
						<a id="menu_sub_4" class="href_desactivado">
							<span class="icon-comprobante"></span>
							Comprobante
						</a>
					</div>
				</li>
				<li class="right">
					<div class="seccion">
						<a href="/carrito/ver" id="carrito_de_compra" class="waves-effect waves-light right menu-carrito-salir">
							<span class="icon-carrito"></span>
							<?php echo count($this->Session->read("Carrito")) ?>
						</a>
					</div>
				</li>

				<li class="right">
						<a class="dropdown-button azul" href="#!" data-activates="menu_opciones">
							<i class="material-icons left azul hideclick">keyboard_arrow_down</i>
							<?php echo $this->Session->read('Auth.User.nombre'); ?>
							<?php echo $this->Session->read('Auth.User.a_paterno'); ?>
						</a>

					<ul id="menu_opciones" class="dropdown-content">
						<li><a class="opciones" href="/asociados/cambiar_contrasena">Contraseña</a></li>
						<li class="divider"></li>
						<li><a class="opciones" href="/asociados/logout">SALIR</a></li>
					</ul>
				</li>

			</ul>



			<ul class="side-nav right" id="mobile-demo">
			</ul>
		</div>
	</nav>

	<div class="container">

		<div class="row margin_nada boton-regresar">
			<div class="col s12">
				<a class="waves-effect waves-light btn pointer fixwidthbtn" id="menu_back" onclick="window.history.back();">
					<i class="material-icons left white-text">keyboard_arrow_left</i>REGRESAR
				</a>
			</div>
		</div>

		<?php echo $this->fetch('content'); ?>
	</div>


	<a href="#hasta_arriba" class="hasta_arriba">Hasta Arriba</a>


	<?php echo $this->Html->script('jquery-2.1.1.min.js'); ?>
	<?php echo $this->Html->script('materialize.min.js'); ?>
	<?php echo $this->Html->script('jquery.validate.min.js'); ?>
	<?php echo $this->Html->script('equalTo.js'); ?>
	<?php echo $this->Html->script('alphanumeric.js'); ?>
	<?php echo $this->Html->script('lettersonly.js'); ?>
	<?php echo $this->Html->script('donetyping.js'); ?>
	<?php echo $this->Html->script('dropdown.min.js'); ?>
	<?php echo $this->Html->script('number_format.min.js'); ?>
	<?php echo $this->fetch('script'); ?>

	<script type="text/javascript">

		$.validator.messages.required = '*Requerido';
		$.validator.messages.number = '*Número inválido';
		$.validator.messages.equalTo = '*Mismo valor';
		$.validator.messages.alphanumeric = '*Omitir ("), '+"(')"+', ([), (]) y enters';
		$.validator.messages.lettersonly = '*Solo letras y números';
		$.validator.messages.email = '*Correo electrónico invalido';
		$.validator.messages.min = '*Igual o mayor a 0';
		$.validator.messages.minlength = "*Mínimo {0} caracteres";
		$.validator.messages.maxlength = "*Máximo {0} caracteres";

		$(document).ready(function(){
			$('.collapsible').collapsible();
			$('.button-collapse').sideNav({
				menuWidth: 300,
				edge: 'right'
			});
			$('.dropdown-button').dropdown({
				belowOrigin: true
			});
		});

		//Función para regresar el parse Float
		function regresarFloat(numero){
			return parseFloat(String(numero).replace(/[^\d\.]/g,''));
		}

		//Boton de hasta arriba
		$(window).scroll(function() {
			if ( $(window).scrollTop() > $(window).height() ) {
				$('a.hasta_arriba').fadeIn('slow');
			} else {
				$('a.hasta_arriba').fadeOut('slow');
			}
		});

		$('a.hasta_arriba').click(function() {
			$('html, body').animate({
				scrollTop: 0
			}, 700);
			return false;
		});

		//Poner focus
		function ponerFocus(input)
		{
			var length_texto = input.val().length;
			input.focus();
			input[0].setSelectionRange(length_texto, length_texto);
		}

		<?php echo $this->Session->flash('flash', array(
		    'element' => 'toast'
		)); ?>

	</script>
</body>
</html>
