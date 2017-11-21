


<?php ob_start(); ?>


<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!-- <link rel="stylesheet" type="text/css" href="http://www.ccm.mx/css/materialize.min.css"> -->
	<link rel="stylesheet" type="text/css" href="css/pdf_style.css">
</head>
<body class="font_14">

	
	<table>

		<tr>
			<td width="400">
				<img src="img/ccm_logo.png">
			</td>
			<td>
				<table id="table_absolute" class="border center">
					<tr class="back_gris">
						<th>Fecha</th>
					</tr>
					<tr>
						<td class="border">
							<?php echo $comprobante["pedido"]["fecha_pedido"] ?>
						</td>
					</tr>
					<tr>
						<td class="border">
							Pedido <?php echo $comprobante["pedido"]["id"] ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>

	</table>
	<br>



	<table class="width_100 border">
		<tr class="back_gris border">
			<td class="back_gris border" colspan="3">
				<b>Datos del colegio y estudiante</b>
			</td>
		</tr>


		<tr>
			<td>
				<b>Colegio:</b> <?php echo $comprobante["colegio_nombre"] ?>
			</td>
			<td></td>
			<td></td>
		</tr>

		<tr>
			<td style="width: 90%;">
				<b>Alumno:</b> <?php echo $comprobante["hijo_nombre"] ?>
			</td>
			<td style="width: 25%;">
				<b>Nivel:</b> <?php echo $comprobante["nivel_nombre"] ?>
			</td>
			<td style="width: 25%;">
				<b>Grado:</b> <?php echo $comprobante["grado_nombre"] ?>
			</td>
		</tr>

		<?php if (!$cajero): ?>
			<tr>
				<td>
					<b>Padre:</b> <?php echo $comprobante["padre"]["nombre"]." ".$comprobante["padre"]["a_paterno"]." ".$comprobante["padre"]["a_materno"] ?>
				</td>
				<td></td>
				<td></td>
			</tr>
		<?php endif ?>
			
	</table>
	<br>



	<table class="width_100 border">
		<tr class="back_gris border">
			<td class="back_gris border">
				<b>Datos de facturación</b>
			</td>
		</tr>


		<tr>
			<td>
				<b>Razón Social:</b> <?php echo @$comprobante["datos_facturacion"]["razon_social"] ?>
			</td>
		</tr>

		<tr>
			<td>
				<b>Domicilio:</b>
				<?php if ($comprobante["datos_facturacion"])
					echo $comprobante["datos_facturacion"]["calle"]." ".
						$comprobante["datos_facturacion"]["numero"].", ".
						$comprobante["datos_facturacion"]["colonia"]." ".
						$comprobante["datos_facturacion"]["ciudad"].", ".
						$comprobante["datos_facturacion"]["estado"].", ".
						$comprobante["datos_facturacion"]["pais"].", ".
						$comprobante["datos_facturacion"]["codigo_postal"]
				?>
			</td>
		</tr>

		<tr>
			<td>
				<b>RFC:</b> <?php echo @$comprobante["datos_facturacion"]["rfc"] ?>
			</td>
		</tr>
	</table>
	<br>




	<table class="width_100">
		<tr class="back_gris">
			<th class="border">Moneda</th>
			<th class="border">Fecha de Pedido</th>
			<th class="border">Correo de contacto</th>
			<th class="border">Celular</th>
		</tr>
		<tr class="center">
			<td class="border">MXN</td>
			<td class="border"><?php echo $comprobante["pedido"]["fecha_pedido"] ?></td>
			<td class="border"><?php echo $comprobante["padre"]["mail"] ?></td>
			<td class="border"><?php echo $comprobante["padre"]["celular"] ?></td>
		</tr>
	</table>
	<br>



	<table class="width_100">
		<tr class="back_gris">
			<th class="border">Descripción</th>
			<th class="border">Cantidad</th>
		</tr>
		<?php $total = 0; ?>
		<?php foreach ($articulos as $key => $articulo): ?>
			<tr>
				<td class="border"><?php echo $articulo["descripcion"] ?></td>
				<td class="border center"><?php echo $articulo["cantidad"] ?></td>
				<?php $total+= $articulo["total"]; ?>
			</tr>
		<?php endforeach ?>			
	</table>
	<br>




	<table class="width_menos" align="right">
		<tr class="center">
			<th class="border right">Importe a Pagar:</th>
			<th class="border right">$ <?php echo number_format($total, 2) ?></th>
		</tr>
	</table>
	<br>
	

	<?php if (@$this->Session->read("Pago.ya_pagado")): ?>
		<br><br>
		<h1 class="center">PAGADO</h1>
	<?php else: ?>
		<b>Formas de Pago</b>
		<ul>
			<?php if (!in_array($colegio_id, array(0))): ?>
				<li>
					Pago en línea con tarjetas de crédito o débito VISA y MASTER CARD y sólo con BANAMEX 3 meses sin intereses en compras mayores a $1,000.00 (mil pesos 00/100 MN) <br>
					Al obtener su confirmación de pago en línea, ya no es necesario que acuda a las instalaciones del colegio a notificarlo. Con dicho comprobante usted se presentará el día señalado a recoger su mercancía en las instalaciones del colegio. <br>
				</li>
			<?php endif ?>
			
			<?php if (!in_array($colegio_id, array(0))): ?>
				<li>
					Pago en Banco  BANAMEX a nombre de COMERCIALIZADORA COLEGIOS MEXICO, S. A. DE C. V.  Sucursal: 205 No. de Cuenta: 992902 <br>
					Presentar su comprobante de pago junto con su pedido en la Administración del Colegio en los días y horas señaladas para que validen su pago. <br>
				</li>
			<?php endif ?>
				
			<?php if (!in_array($colegio_id, array(0))): ?>
				<li>
					Pago en Ventanilla de la Administración del Colegio con tarjetas de crédito o débito VISA y MASTER CARD y sólo con BANAMEX 3 meses sin intereses en compras mayores a $1,000.00 (mil pesos 00/100 MN)  (<span class="red-text">SOLO EN COLEGIOS PARTICIPANTES</span>) <br>
					Presentar su pedido realizado en el portal en la Administración del Colegio en los días y horas señaladas para que validen su pago. <br>
				</li>
			<?php endif ?>
				
			<li>
				Después de las fechas indicadas por el colegio <b><u>NO</u></b> se recibirán pedidos ni pagos.
			</li>
		</ul>
	<?php endif ?>

</body>
</html>
	

<?php
	$codigo_html = ob_get_contents();
	ob_end_flush();

	SessionComponent::write('codigo_html', $codigo_html);

	header("Location: /pedidos/comprobante/$pedido_id");
	exit();
?>