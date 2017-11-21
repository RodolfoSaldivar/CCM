


<?php ob_start(); ?>
	
<!DOCTYPE html>
<html>
<head>
	<title></title>

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
							<?php echo $fecha ?>
						</td>
					</tr>
					<tr>
						<td class="border">
							Orden Compra <?php echo $orden_id ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>

	</table>
	<br>




	<table class="width_100">
		<tr class="back_gris">
			<th class="border">Colegio ID</th>
			<th class="border">Nombre</th>
			<th class="border">Moneda</th>
			<th class="border">Fecha de Pedido</th>
		</tr>
		<tr class="center">
			<td class="border">
				<?php echo $orden["Colegio"]["identificador"] ?>
			</td>
			<td class="border">
				<?php echo $orden["Colegio"]["nombre"] ?>
			</td>
			<td class="border">MXN</td>
			<td class="border">
				<?php echo $fecha ?>
			</td>
		</tr>
	</table>
	<br>



	<table class="width_100">
		<tr class="back_gris">
			<th class="border">ID</th>
			<th class="border">Descripción</th>
			<th class="border">Cantidad</th>
			<th class="border">P.U.</th>
			<th class="border">Importe</th>
		</tr>

		<?php foreach ($articulos as $key => $articulo): ?>
			
			<?php if ($articulo["f_cantidad"]): ?>
				
				<tr>
					<td class="border">
						<?php echo $articulo["identificador"] ?>
					</td>
					<td class="border">
						<?php echo $articulo["descripcion"] ?>
					</td>
					<td class="border center">
						<?php echo $articulo["f_cantidad"] ?>
					</td>

					<?php if ($articulo["resultado"]): ?>
						<td class="border right">
							<?php echo $articulo["individual"] ?>
						</td>
					<?php else: ?>
						<td class="border right">
							<?php echo number_format($articulo["pu_siva"], 2) ?>
						</td>
					<?php endif ?>

					<?php if ($articulo["resultado"]): ?>
						<td class="border right">
							<?php echo $articulo["resultado"] ?>
						</td>
					<?php else: ?>
						<td class="border right">
							<?php echo $articulo["fp_importe"] ?>
						</td>
					<?php endif ?>
				</tr>
				
			<?php endif ?>

		<?php endforeach ?>

		<tr>
			<td></td><td></td><td></td>
			<td class="border right bold">Subtotal:</td>
			<td class="border right bold"><?php echo number_format($totales["importe_pdf"], 2) ?></td>
		</tr>

		<tr>
			<td></td><td></td><td></td>
			<td class="border right bold">IVA:</td>
			
			<?php if ($ajuste_iva > 0): ?>
				<td class="border right bold"><?php echo number_format($ajuste_iva, 2) ?></td>
			<?php else: ?>
				<td class="border right bold"><?php echo number_format($totales["fp_iva_total"], 2) ?></td>
			<?php endif ?>
		</tr>

		<tr>
			<td></td><td></td><td></td>
			<td class="border right bold">TOTAL:</td>
			
			<?php if ($ajuste_iva > 0): ?>
				<td class="border right bold">
					<?php echo number_format(
						$totales["fp_total_total"] - $totales["fp_iva_total"] + $ajuste_iva,
						2)
					?>
				</td>
			<?php else: ?>
				<td class="border right bold"><?php echo number_format($totales["fp_total_total"], 2) ?></td>
			<?php endif ?>
		</tr>

	</table>
	<br>

</body>
</html>


<?php 
	$codigo_html = ob_get_contents();
	ob_end_flush();
	SessionComponent::write('codigo_html', $codigo_html);
	header("Location: /orden_compras/ver_pdf/$orden_id");
	exit();
?>