


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

		<div class="col s6 m4">
			<button class="btn waves-effect waves-light <?php if(!$colegio_id) echo "disabled"; ?> btn_actualizar" type="submit" name="action" value="actualizar">
				Actualizar Datos
			</button>
		</div>

	</div>

<?php echo $this->Form->end(); ?>



<form action="/orden_compras/guardar_detalle" id="OrdenCompraDetalleForm" method="post" accept-charset="utf-8">

	<?php if ($articulos): ?>
		<div class="row">
			<div class="col s4">
				<?php if (!$fecha_facturado): ?>
					<button class="btn waves-effect waves-light" type="submit" name="action" value="guardar">
						Guardar
					</button>
				<?php endif ?>
					
			</div>
		</div>

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

	<input type="hidden" name="data[nombre_archivo]" value="Cierre_Detalle_<?php echo $colegio_nombre ?>">
	<input type="hidden" name="data[orden_id]" value="<?php echo $orden_id ?>">
	
	<table id="tbl_cierre" class="bordered responsive-table">
		<thead>
			<tr>
				<th colspan="4">Información del Artículo</th>
				<th colspan="7">Pedido Original</th>
				<th colspan="5">Ventas Totales</th>
				<th colspan="5">Ventas Facturadas</th>
				<th colspan="1">FP Modificable</th>
				<th colspan="8">Facturación Pendiente</th>
			</tr>
			<tr>
				<th>Clave</th>
				<th>Artículo</th>
				<th>IVA</th>
				<th>Familia</th>
				<th>Cant</th>
				<th>Dev</th>
				<th>Inv</th>
				<th>P.U. + IVA</th>
				<th>Importe</th>
				<th>IVA</th>
				<th>TOTAL</th>
				<th>Cant</th>
				<th>P.U. + IVA</th>
				<th>Importe</th>
				<th>IVA</th>
				<th>Total</th>
				<th>Cant</th>
				<th>P.U. + IVA</th>
				<th>Importe</th>
				<th>IVA</th>
				<th>Total</th>
				<th>P.U. + IVA M</th>
				<th>Cant</th>
				<th>P.U. + IVA</th>
				<th class="lime lighten-5 quitar_por_mover">Ajuste Individual</th>
				<th class="lime lighten-5 quitar_por_mover">P.U. Ajustado</th>
				<th class="lime lighten-5 quitar_por_mover">Importe Ajustado</th>
				<th>Importe</th>
				<th>IVA</th>
				<th>Total</th>
			</tr>
		</thead>

		<tbody id="cambiar_tbody">

			<?php foreach ($articulos as $key => $articulo): ?>
				<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][articulo_id]" value="<?php echo $articulo["articulo_id"] ?>">
				<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][orden_compra_id]" value="<?php echo $articulo["orden_compra_id"] ?>">
				<tr>

					<?php 
						// Info Articulo
					?>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][clave]" value="<?php echo $articulo["identificador"] ?>">
						<?php echo $articulo["identificador"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][descripcion]" value="<?php echo $articulo["descripcion"] ?>">
						<?php echo $articulo["descripcion"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][iva]" value="<?php echo $articulo["iva"] ?>">
						<span id="iva_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["iva"] ?>
						</span>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][familia]" value="<?php echo $articulo["familia"] ?>">
						<?php echo $articulo["familia"] ?>
					</td>

					<?php 
						// Pedido Original
					?>
					<td class="b_left">
						<input name="data[Filas][<?php echo $articulo["articulo_id"] ?>][cantidad]" id="po_cant_<?php echo $articulo["articulo_id"] ?>" type="number" min="0" step="any" value="<?php echo $articulo["po_cant"] ?>" onchange="inputChange(<?php echo $articulo["articulo_id"] ?>)">
					</td>
					<td>
						<input name="data[Filas][<?php echo $articulo["articulo_id"] ?>][devueltos]" id="po_dev_<?php echo $articulo["articulo_id"] ?>" type="number" min="0" step="any" value="<?php echo $articulo["po_dev"] ?>" onchange="inputChange(<?php echo $articulo["articulo_id"] ?>)">
					</td>
					<td>
						<input name="data[Filas][<?php echo $articulo["articulo_id"] ?>][inventario]" id="po_inv_<?php echo $articulo["articulo_id"] ?>" type="number" min="0" step="any" value="<?php echo $articulo["po_inv"] ?>" onchange="inputChange(<?php echo $articulo["articulo_id"] ?>)">
					</td>
					<td>
						<input name="data[Filas][<?php echo $articulo["articulo_id"] ?>][pu_colegio]" id="pu_colegio_<?php echo $articulo["articulo_id"] ?>" type="number" min="0" step="any" value="<?php echo $articulo["pu_colegio"] ?>" onchange="inputChange(<?php echo $articulo["articulo_id"] ?>)">
					</td>
					<td>
						<input id="input_po_importe_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][po_importe]" value="<?php echo $articulo["po_importe"] ?>">
						<span id="po_importe_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["po_importe"] ?>
						</span>
					</td>
					<td>
						<input id="input_po_iva_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][po_iva]" value="<?php echo $articulo["po_iva"] ?>">
						<span id="po_iva_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["po_iva"] ?>
						</span>
					</td>
					<td>
						<input id="input_po_total_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][po_total]" value="<?php echo $articulo["po_total"] ?>">
						<span id="po_total_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["po_total"] ?>
						</span>
					</td>
					
					<?php 
						// Ventas Totales
					?>
					<td class="b_left">
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vt_cantidad]" value="<?php echo $articulo["vt_cantidad"] ?>">
						<?php echo $articulo["vt_cantidad"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vt_venta]" value="<?php echo $articulo["vt_venta"] ?>">
						<?php echo $articulo["vt_venta"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vt_importe]" value="<?php echo $articulo["vt_importe"] ?>">
						<?php echo $articulo["vt_importe"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vt_iva]" value="<?php echo $articulo["vt_iva"] ?>">
						<?php echo $articulo["vt_iva"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vt_total]" value="<?php echo $articulo["vt_total"] ?>">
						<?php echo $articulo["vt_total"] ?>
					</td>
					
					<?php 
						// Ventas Facturadas
					?>
					<td class="b_left">
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vf_cantidad]" value="<?php echo $articulo["vf_cantidad"] ?>">
						<span id="vf_cantidad_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["vf_cantidad"] ?>
						</span>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vf_venta]" value="<?php echo $articulo["vf_venta"] ?>">
						<span id="vf_venta_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["vf_venta"] ?>
						</span>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vf_importe]" value="<?php echo $articulo["vf_importe"] ?>">
						<?php echo $articulo["vf_importe"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vf_iva]" value="<?php echo $articulo["vf_iva"] ?>">
						<?php echo $articulo["vf_iva"] ?>
					</td>
					<td>
						<input type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][vf_total]" value="<?php echo $articulo["vf_total"] ?>">
						<span id="vf_total_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["vf_total"] ?>
						</span>
					</td>

					<?php 
						// Facturacion Pendiente modificable
					?>
					<td class="b_left">
						<input id="m_f_pu_venta_<?php echo $articulo["articulo_id"] ?>" type="number" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][f_pu_venta]" min="0" step="any" value="<?php echo $articulo["m_f_pu_venta"] ?>" onchange="fpDatos(<?php echo $articulo["articulo_id"] ?>)">
					</td>
					
					<?php 
						// Facturacion Pendiente
					?>
					<td class="b_left">
						<input name="data[Filas][<?php echo $articulo["articulo_id"] ?>][f_cantidad]" id="input_f_cantidad_<?php echo $articulo["articulo_id"] ?>" type="hidden" value="<?php echo $articulo["f_cantidad"] ?>">
						<span id="f_cantidad_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["f_cantidad"] ?>
						</span>
					</td>
					<td>
						<input id="input_f_pu_venta_<?php echo $articulo["articulo_id"] ?>" type="hidden" value="<?php echo $articulo["f_pu_venta"] ?>">
						<span id="f_pu_venta_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["f_pu_venta"] ?>
						</span>
					</td>
					<td class="lime lighten-5 quitar_por_mover">
						<input id="input_quitar_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][quitar]" value="<?php echo $articulo["quitar"] ?>">
						<span id="quitar_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["quitar"] ?>
						</span>						
					</td>
					<td class="lime lighten-5 quitar_por_mover">
						<input id="input_individual_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][individual]" value="<?php echo $articulo["individual"] ?>">
						<span id="individual_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["individual"] ?>
						</span>						
					</td>
					<td class="lime lighten-5 quitar_por_mover">
						<input id="input_resultado_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][resultado]" value="<?php echo $articulo["resultado"] ?>">
						<span id="resultado_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["resultado"] ?>
						</span>						
					</td>
					<td>
						<input id="input_fp_importe_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][fp_importe]" value="<?php echo $articulo["fp_importe"] ?>">
						<span id="fp_importe_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["fp_importe"] ?>
						</span>
					</td>
					<td>
						<input id="input_fp_iva_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][fp_iva]" value="<?php echo $articulo["fp_iva"] ?>">
						<span id="fp_iva_<?php echo $articulo["articulo_id"] ?>">
							<?php echo $articulo["fp_iva"] ?>
						</span>
					</td>
					<?php if ($articulo["resultado"]): ?>
						
						<td class="lime lighten-5">
							<input id="input_fp_total_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][fp_total]" value="<?php echo $articulo["resultado"] ?>">
							<span id="fp_total_<?php echo $articulo["articulo_id"] ?>">
								<?php echo $articulo["resultado"] ?>
							</span>
						</td>

					<?php else: ?>

						<td>
							<input id="input_fp_total_<?php echo $articulo["articulo_id"] ?>" type="hidden" name="data[Filas][<?php echo $articulo["articulo_id"] ?>][fp_total]" value="<?php echo $articulo["fp_total"] ?>">
							<span id="fp_total_<?php echo $articulo["articulo_id"] ?>">
								<?php echo $articulo["fp_total"] ?>
							</span>
						</td>

					<?php endif ?>
						
				</tr>
			<?php endforeach ?>

			<?php if ($totales): ?>
				
			<tr id="tr_quitar">
				<td></td><td></td><td></td>
				<td>
					<b>TOTAL</b>
				</td>
				<td class="b_left">
					<?php echo number_format($totales["po_cant_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["po_dev_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["po_inv_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["po_venta_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["po_importe_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["po_iva_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["po_total_total"], 2) ?>
				</td>
				<td class="b_left">
					<?php echo number_format($totales["vt_cant_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vt_venta_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vt_importe_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vt_iva_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vt_total_total"], 2) ?>
				</td>
				<td class="b_left">
					<?php echo number_format($totales["vf_cant_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vf_venta_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vf_importe_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vf_iva_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["vf_total_total"], 2) ?>
				</td>
				<td class="b_left"></td>
				<td class="b_left">
					<?php echo number_format($totales["fp_cant_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["fp_venta_total"], 2) ?>
				</td>
				<td class="lime lighten-5 quitar_por_mover"></td>
				<td class="lime lighten-5 quitar_por_mover">
					<?php echo number_format($totales["individual"], 2) ?>
				</td>
				<td class="lime lighten-5 quitar_por_mover">
					<?php echo number_format($totales["resultado"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["fp_importe_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["fp_iva_total"], 2) ?>
				</td>
				<td>
					<?php echo number_format($totales["fp_total_total"], 2) ?>
				</td>
			</tr>

			<?php endif ?>

			<tr style="border-bottom: none;">
				<td colspan="27"></td>
				<td style="font-weight: bold;">IVA M.</td>
				<td>
					<input id="ajuste_iva type="number" name="data[ajuste_iva]" min="0" step="any" value="<?php echo $ajuste_iva ?>">
				</td>
			</tr>
		</tbody>
	</table>
	<br><br><br>

</form>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
		$("#menu_cierre").addClass("activado");
	});

	$('#OrdenCompraDetalleForm').submit(function()
	{
		$("#preloader").removeClass("hide");
		$(".btn_actualizar").addClass("disabled");
	});



	<?php // No permite picarle a ver o actualizar sin antes escoger el colegio ?>

	$(document).on("change", "#select_colegios", function()
	{
		$("#select_colegios-error").css("display", "none");
		$(".btn_actualizar").removeClass("disabled");
	});



	<?php // Modifica el importe del pedido original cada vez que se mueve algun valor del renglon ?>

	var solo_uno = 1;

	function inputChange(articulo_id)
	{
		$("#btn_excel").addClass("disabled");
		$("#tr_quitar").addClass("hide");
		$(".quitar_por_mover").addClass("hide");
		poImporte(articulo_id);
	}

	function poImporte(articulo_id)
	{
		var po_cant = $("#po_cant_"+articulo_id).val();
		po_cant = regresarFloat(po_cant);

		var po_dev = $("#po_dev_"+articulo_id).val();
		po_dev = regresarFloat(po_dev);

		var pu_colegio = $("#pu_colegio_"+articulo_id).val();
		pu_colegio = regresarFloat(pu_colegio);

		var iva = $("#iva_"+articulo_id).text();
		iva = regresarFloat(iva);

		var vf_cantidad = $("#vf_cantidad_"+articulo_id).text();
		vf_cantidad = regresarFloat(vf_cantidad);

		var vf_venta = $("#vf_venta_"+articulo_id).text();
		vf_venta = regresarFloat(vf_venta);

		var vf_total = $("#vf_total_"+articulo_id).text();
		vf_total = regresarFloat(vf_total);

		var f_cantidad = $("#f_cantidad_"+articulo_id).text();
		f_cantidad = regresarFloat(f_cantidad);

		var f_pu_venta = $("#m_f_pu_venta_"+articulo_id).val();
		f_pu_venta = regresarFloat(f_pu_venta);




		var po_importe = (po_cant - po_dev) * (pu_colegio / (1 + iva / 100));
		var po_iva = po_importe * (iva / 100);
		var po_total = po_importe + po_iva;

		if (f_pu_venta == 0)
		{
			f_cantidad = po_cant - po_dev - vf_cantidad;

			if (f_cantidad > 0)
			{
				f_pu_venta = ((po_cant - po_dev) * pu_colegio - vf_cantidad * vf_venta) / f_cantidad;
			}
			else
				f_cantidad = 0;
		}

		var fp_importe = f_cantidad * (f_pu_venta / (1 + iva / 100));
		var fp_iva = fp_importe * (iva / 100);
		var fp_total = fp_importe + fp_iva;




		po_importe = $.number(po_importe, 2);
		$("#po_importe_"+articulo_id).text(po_importe);
		$("#input_po_importe_"+articulo_id).val(po_importe);

		po_iva = $.number(po_iva, 2);
		$("#po_iva_"+articulo_id).text(po_iva);
		$("#input_po_iva_"+articulo_id).val(po_iva);

		po_total = $.number(po_total, 2);
		$("#po_total_"+articulo_id).text(po_total);
		$("#input_po_total_"+articulo_id).val(po_total);

		f_cantidad = $.number(f_cantidad);
		$("#f_cantidad_"+articulo_id).text(f_cantidad);
		$("#input_f_cantidad_"+articulo_id).val(f_cantidad);

		f_pu_venta = $.number(f_pu_venta, 2);
		$("#f_pu_venta_"+articulo_id).text(f_pu_venta);
		$("#input_f_pu_venta_"+articulo_id).val(f_pu_venta);

		fp_importe = $.number(fp_importe, 2);
		$("#fp_importe_"+articulo_id).text(fp_importe);
		$("#input_fp_importe_"+articulo_id).val(fp_importe);

		fp_iva = $.number(fp_iva, 2);
		$("#fp_iva_"+articulo_id).text(fp_iva);
		$("#input_fp_iva_"+articulo_id).val(fp_iva);

		fp_total = $.number(fp_total, 2);
		$("#fp_total_"+articulo_id).text(fp_total);
		$("#input_fp_total_"+articulo_id).val(fp_total);




		if (solo_uno)
		{
			solo_uno = 0;
		}
	}



	<?php // Modifica el importe de facturacion pedniente ?>

	function fpDatos(articulo_id)
	{
		$("#btn_excel").addClass("disabled");
		$("#tr_quitar").addClass("hide");
		$(".quitar_por_mover").addClass("hide");

		var f_cantidad = $("#f_cantidad_"+articulo_id).text();
		f_cantidad = regresarFloat(f_cantidad);

		var f_pu_venta = $("#m_f_pu_venta_"+articulo_id).val();
		f_pu_venta = regresarFloat(f_pu_venta);

		var iva = $("#iva_"+articulo_id).text();
		iva = regresarFloat(iva);

		var fp_importe = f_cantidad * (f_pu_venta / (1 + iva / 100));
		var fp_iva = fp_importe * (iva / 100);
		var fp_total = fp_importe + fp_iva;


		if (f_pu_venta == 0)
		{
			poImporte(articulo_id);
		}
		else
		{
			f_cantidad = $.number(f_cantidad);
			$("#f_cantidad_"+articulo_id).text(f_cantidad);
			$("#input_f_cantidad_"+articulo_id).val(f_cantidad);

			f_pu_venta = $.number(f_pu_venta, 2);
			$("#f_pu_venta_"+articulo_id).text(f_pu_venta);
			$("#input_f_pu_venta_"+articulo_id).val(f_pu_venta);

			fp_importe = $.number(fp_importe, 2);
			$("#fp_importe_"+articulo_id).text(fp_importe);
			$("#input_fp_importe_"+articulo_id).val(fp_importe);

			fp_iva = $.number(fp_iva, 2);
			$("#fp_iva_"+articulo_id).text(fp_iva);
			$("#input_fp_iva_"+articulo_id).val(fp_iva);

			fp_total = $.number(fp_total, 2);
			$("#fp_total_"+articulo_id).text(fp_total);
			$("#input_fp_total_"+articulo_id).val(fp_total);
		}
	}

<?php $this->Html->scriptEnd(); ?>