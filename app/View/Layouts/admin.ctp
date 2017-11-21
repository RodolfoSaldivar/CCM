

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

	<nav id="menu_admin">
		<div class="nav-wrapper">
			<a href="/hijos/seleccionar_hijo"><img width="175" class="left" src="/img/ccm.png"></a>
			<a href="#" data-activates="mobile-demo" class="button-collapse right">
				<i class="material-icons">menu</i>
			</a>
			<ul class="hide-on-med-and-down">
				<?php $tipo = $this->Session->read("Auth.User.tipo"); ?>

				<?php if (in_array($tipo, array('CCM', 'Cajero'))): ?>
					<li>
						<div class="seccion">
							<a id="menu_pedi" class="dropdown-button" href="#" data-activates="reportes_drop">Reportes</a>
						</div>
						<ul id='reportes_drop' class='dropdown-content'>
							<li><a href="/pedidos/sintesis">Pedidos Síntesis</a></li>
							<li><a href="/pedidos/detalle">Pedidos Detalle</a></li>
							<li class="divider"></li>
							<li><a href="/paquetes/sintesis">Paquetes Síntesis</a></li>
							<li><a href="/paquetes/detalle">Paquetes Detalle</a></li>
						</ul>
					</li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM'))): ?>
					<li><div class="seccion"><a id="menu_sett" href="/settings">Settings</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM'))): ?>
					<li><div class="seccion"><a id="menu_cole" href="/colegios">Colegios</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM', 'Director'))): ?>
					<li><div class="seccion"><a id="menu_arti" href="/articulos">Artículos</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM'))): ?>
					<li><div class="seccion"><a id="menu_paqu" href="/paquetes">Paquetes</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM'))): ?>
					<li><div class="seccion"><a id="menu_asoc" href="/asociados">Asociados</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM', 'Cajero'))): ?>
					<li><div class="seccion"><a id="menu_caja" href="/cajas">Caja</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('Director'))): ?>
					<li><div class="seccion"><a id="menu_caja" href="/corte_cajas">Cortes de Caja</a></div></li>
				<?php endif ?>

				<?php if (in_array($tipo, array('CCM'))): ?>
					<li>
						<div class="seccion">
							<a id="menu_cierre" class="dropdown-button" href="#" data-activates="cierre_drop">Cierre</a>
						</div>
						<ul id='cierre_drop' class='dropdown-content'>
							<li><a href="/pedidos/facturas">Reporte de Facturas</a></li>
							<li class="divider"></li>
							<li><a href="/orden_compras/sintesis">Síntesis venta temporada</a></li>
							<li class="divider"></li>
							<li><a href="/orden_compras/detalle">Detalle venta temporada</a></li>
							<li class="divider"></li>
							<li><a href="/orden_compras/facturas">Facturas Pendientes</a></li>
						</ul>
					</li>
				<?php endif ?>

				<li class="right">
					<a id="contra_y_salir" class="dropdown-button azul" href="#!" data-activates="menu_opciones">
						<i class="material-icons left blanco hideclick">keyboard_arrow_down</i>
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

	<div class="container container_admin">

		<div class="row breadcrumbs">
			<?php echo @$breadcrumbs; ?>
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
			$('#contra_y_salir, #menu_pedi, #btn_buscador, #menu_cierre').dropdown( {
				belowOrigin: true,
				hover: true,
				stopPropagation: true
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
