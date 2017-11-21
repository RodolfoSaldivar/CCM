


<div class="contenedor">

	<div class="row">
		<div style="padding:20px 0 20px 20px; font-size:30px;">
			Filas en plantilla:<br>
			<?php echo $fila ?><br><br>
			Agregados al sistema:<br>
			<?php echo $agregados ?><br><br>
			Actualizados:<br>
			<?php echo $actualizados ?><br>
		</div>
	</div>

	<?php if ($errores_filas): ?>
		<div class="row margin_nada" style="padding:20px 0 20px 20px; font-size:30px;">
			Errores: <b><?php echo count($errores_filas) ?></b>
		</div>
		<table class="bordered">
			<thead>
				<tr>
					<th><b>Fila</b></th>
					<th><b>Error</b></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($errores_filas as $fila => $error): ?>
					<tr>
						<td>
							<?php echo $fila ?>
						</td>
						<td>
							<?php echo $error ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php endif ?>

	<br><br>
</div>