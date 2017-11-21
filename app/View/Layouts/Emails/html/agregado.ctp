<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $this->fetch('title'); ?></title>
</head>
<body>

	<p>Hola <?php echo $nombre; ?> <?php echo $a_paterno; ?> <?php echo $a_materno; ?></p>

	<p>
		Tu usuario ha sido agregado exitosamente, al portal del CCM.
	</p>
	<p>
		Usuario: <b><?php echo $usu_username; ?></b><br>
		Contraseña: <b><?php echo $contra; ?></b>
	</p>
	<p>
		<b>Para validar tu cuenta ingresa a esta dirección:</b>
	</p>

	<a href="<?php echo $url; ?>"><?php echo $url; ?></a>
	<br><br>

	<span class="red-text">Forma de Pago</span>

	<ul>
		<li>
			<span class="blue-text">
				<b>Pago en línea </b>
			</span>
			con tarjetas de crédito o débito <b>VISA</b> y <b>MASTERCARD</b> y sólo con <b>BANAMEX</b> tres meses sin intereses en compras mayores a $1,000.00 (mil pesos 00/100 MN).
			<br><br>
			Al obtener su confirmación de pago en línea, ya no es necesario que acuda a las instalaciones del colegio a notificarlo. con dicho comprobante usted se presentará el día señalado a recoger su mercancía en las instalaciones del colegio.
			<br><br>
		</li>
		<li>
			<span class="blue-text">
				<b>Pago en banco BANAMEX </b>
			</span>
			a nombre de <b>COMERCIALIZADORA COLEGIOS MÉXICO, S.A. DE C.V.</b> sucursal: 205 No. de Cuenta: 992902.
			<br><br>
			Presentar su comprobante de pago junto con su pedido en la Administración del Colegio en los días y horas señalados para que validen su pago.
			<br><br>
		</li>
		<li>
			<span class="blue-text">
				<b>Pago en Ventanilla de la Aministración del Colegio </b>
			</span>
			con tarjetas de crédito o débito <b>VISA</b> y <b>MASTERCARD</b> y sólo con <b>BANAMEX</b> tres meses sin intereses en compras mayores a $1,000.00 (mil pesos 00/100 MN).
			<br><br>
			Presentar el comprobante de su pedido en la Administración del Colegio en los días y horas señaladas para realizar su pago.
		</li>
	</ul>

</body>
</html>