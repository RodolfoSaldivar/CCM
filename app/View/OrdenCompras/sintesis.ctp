


<?php echo $this->Form->create(); ?>

	<div class="row">
		
		<div class="input-field margin_nada col s12 m4">
			<select id="select_colegios" name="data[OrdenCompra][colegio_id]">
				<option value="todos" disabled <?php if(!$colegio_id) echo "selected"; ?>>Colegio</option>
				<?php $colegio_nombre = 0; ?>
				<?php foreach ($colegios as $key => $colegio): ?>
					<?php if ($colegio_id == $colegio["Colegio"]["id"]): ?>
						<?php $colegio_nombre = $colegio["Colegio"]["nombre"]; ?>
						<option selected value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
					<?php else: ?>
						<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
					<?php endif ?>
				<?php endforeach ?>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>

		<div class="col s6 m4">
			<button class="btn waves-effect waves-light <?php if(!$colegio_id) echo "disabled"; ?> btn_actualizar left" type="submit" name="action" value="ver">
				Ver Cierre
			</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<div id="preloader" class="preloader-wrapper small active hide">
				<div class="spinner-layer spinner-green-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="gap-patch">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
		</div>

	</div>

<?php echo $this->Form->end(); ?>



<form action="/dashboard/descargar_excel" id="OrdenCompraDetalleForm" method="post" accept-charset="utf-8">

	<?php if ($familias): ?>
		<div class="row">
			<div class="col s4">
				<button id="btn_excel" class="btn waves-effect waves-light" type="submit" name="action" value="excel">
					Excel
				</button>
			</div>

			<div class="col s6 quitar_por_mover">
				<h4>
					Ajuste manual: <?php echo @$totales["ajuste_manual"] ?>
				</h4>
			</div>
		</div>
	<?php endif ?>

	<input type="hidden" name="data[nombre_archivo]" value="Cierre_Sintesis_<?php echo $colegio_nombre ?>">
	
	<table class="bordered responsive-table">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Pedido Original</th>
				<th>Venta Total</th>
				<th>Venta Facturada</th>
				<th>Pendiente Facturar</th>
			</tr>
		</thead>

		<tbody id="cambiar_tbody">
			<?php foreach ($familias as $nombre => $familia): ?>
				
				<tr>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $nombre ?>][Nombre]" value="<?php echo $nombre ?>">
						<?php echo $nombre ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $nombre ?>][Pedido Original]" value="<?php echo $familia["po"] ?>">
						<?php echo $familia["po"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $nombre ?>][Venta Total]" value="<?php echo $familia["vt"] ?>">
						<?php echo $familia["vt"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $nombre ?>][Venta Facturada]" value="<?php echo $familia["vf"] ?>">
						<?php echo $familia["vf"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $nombre ?>][Pendiente Facturar]" value="<?php echo $familia["pf"] ?>">
						<?php echo $familia["pf"] ?>
					</td>
				</tr>

			<?php endforeach ?>

			<?php if ($totales): ?>
				
				<tr id="tr_quitar">
					<td>
						TOTAL
					</td>
					<td>
						<?php echo $totales["po"] ?>
					</td>
					<td>
						<?php echo $totales["vt"] ?>
					</td>
					<td>
						<?php echo $totales["vf"] ?>
					</td>
					<td>
						<?php echo $totales["pf"] ?>
					</td>
				</tr>

			<?php endif ?>
		</tbody>
	</table>

</form>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
		$("#menu_cierre").addClass("activado");
	});

	$('#OrdenCompraSintesisForm').submit(function()
	{
		$("#preloader").removeClass("hide");
		$(".btn_actualizar").addClass("disabled");
	});

	var ajuste = 0;



	<?php // No permite picarle a ver o actualizar sin antes escoger el colegio ?>

	$(document).on("change", "#select_colegios", function()
	{
		$("#select_colegios-error").css("display", "none");
		$(".btn_actualizar").removeClass("disabled");
	});

<?php $this->Html->scriptEnd(); ?>