


<div class="row">

	<div class="col s12 right">

		<table class="bordered highlight">
			<thead class="top-tabla">
				<tr>
					<th>DESCRIPCIÓN</th>
					<th class="center">CANTIDAD</th>
					<th class="decimal">IMPORTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				</tr>
			</thead>

			<tbody>
				<?php $importe_total = 0 ?>
				<?php foreach ($paquetes as $key => $paquete): ?>
					<tr id="tr_<?php echo $paquete["Paquete"]["id"] ?>">
						<td>
							<?php echo $paquete["Paquete"]["descripcion"] ?>
						</td>
						<td id="cantidad_<?php echo $paquete["Paquete"]["id"] ?>" class="center">
							<?php echo $mismos[$paquete["Paquete"]["id"]] ?>
						</td>
						<td>
							<?php $importe_subtotal = $mismos[$paquete["Paquete"]["id"]] * $paquete["Precios"]["precio_publico"] ?>
							<?php $importe_total+= $importe_subtotal ?>
							<span id="importe_<?php echo $paquete["Paquete"]["id"] ?>" class="decimal">
								<?php echo number_format($importe_subtotal, 2) ?>
							</span>
						</td>
					</tr>
				<?php endforeach ?>
				<tr class="bottom-tabla">
					<td></td>
					<td class="center">
						<b>TOTAL:</b>
					</td>
					<td>
						<b>
						<span id="importe_total" class="decimal">
							<?php echo number_format($importe_total, 2) ?>
						</span>
					</b>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

</div>


<input type="checkbox" id="check_facturacion" />
<label for="check_facturacion">
	<span class="red-text">
		Si requiere factura, seleccione este campo y complete los datos solicitados. <br>
		Usted podrá obtener su factura después de haber realizado su pago dentro del mismo mes.
	</span>
</label>

<form id="CarritoPagarForm" class="hide" action="/facturacion_datos/actualizar" method="post" accept-charset="utf-8">
	<br><br>

	<div class="row margin_nada">

		<h5>
			<b>Datos de Facturación</b>
		</h5>
	</div>

	<input type="hidden" name="data[asociado_id]" value="<?php echo $this->Session->read("Auth.User.id"); ?>">

	<div class="row margin_nada margensito-superior">
		<div class="input-field col s12">
			<input class="col s4" readonly="" id="alumno" type="text" value="<?php echo $hijo["nombre"]." ".$hijo["a_paterno"]." ".$hijo["a_materno"] ?>">
			<label for="alumno">
				Alumno
			</label>
		</div>
		<div class="input-field col s12 m6">
			<input id="razon_social" name="data[FacturacionDato][razon_social]" type="text" class="validate" value="<?php echo @$d_fac["razon_social"] ?>">
			<label for="razon_social">
				Razón Social
				<label id="razon_social-error" class="error validation_label" for="razon_social"></label>
			</label>
		</div>
		<div class="input-field col s12 m6">
			<input id="rfc" name="data[FacturacionDato][rfc]" type="text" class="validate" value="<?php echo @$d_fac["rfc"] ?>">
			<label for="rfc">
				RFC
				<label id="rfc-error" class="error validation_label" for="rfc"></label>
			</label>
		</div>
	</div>

	<div class="row margensito-superior">
		<div class="domicilio-fiscal">Domicilio Fiscal</div>

		<div class="input-field col s12 m6">
			<input id="calle" name="data[FacturacionDato][calle]" type="text" class="validate" value="<?php echo @$d_fac["calle"] ?>">
			<label for="calle">
				Calle
				<label id="calle-error" class="error validation_label" for="calle"></label>
			</label>
		</div>
		<div class="input-field col s12 m3">
			<input id="numero" name="data[FacturacionDato][numero]" type="text" class="validate" value="<?php echo @$d_fac["numero"] ?>">
			<label for="numero">
				Número
				<label id="numero-error" class="error validation_label" for="numero"></label>
			</label>
		</div>
		<div class="input-field col s12 m3">
			<input id="numero_interior" name="data[FacturacionDato][numero_interior]" type="text" class="validate" value="<?php echo @$d_fac["numero_interior"] ?>">
			<label for="numero_interior">Número
				Interior
				<label id="numero_interior-error" class="error validation_label" for="numero_interior"></label>
			</label>
		</div>
		<div class="input-field col s12 m6">
			<input id="colonia" name="data[FacturacionDato][colonia]" type="text" class="validate" value="<?php echo @$d_fac["colonia"] ?>">
			<label for="colonia">
				Colonia
				<label id="colonia-error" class="error validation_label" for="colonia"></label>
			</label>
		</div>
		<div class="input-field col s12 m3">
			<input id="localidad" name="data[FacturacionDato][localidad]" type="text" class="validate" value="<?php echo @$d_fac["localidad"] ?>">
			<label for="localidad">
				Localidad
				<label id="localidad-error" class="error validation_label" for="localidad"></label>
			</label>
		</div>
		<div class="input-field col s12 m3">
			<input id="ciudad" name="data[FacturacionDato][ciudad]" type="text" class="validate" value="<?php echo @$d_fac["ciudad"] ?>">
			<label for="ciudad">
				Ciudad
				<label id="ciudad-error" class="error validation_label" for="ciudad"></label>
			</label>
		</div>
		<div class="input-field col s12 m6">
			<input id="estado" name="data[FacturacionDato][estado]" type="text" class="validate" value="<?php echo @$d_fac["estado"] ?>">
			<label for="estado">
				Estado
				<label id="estado-error" class="error validation_label" for="estado"></label>
			</label>
		</div>
		<div class="input-field col s12 m3">
			<input id="pais" name="data[FacturacionDato][pais]" type="text" class="validate"  value="México">
			<label for="pais">
				País
				<label id="pais-error" class="error validation_label" for="pais"></label>
			</label>
		</div>
		<div class="input-field col s12 m3">
			<input id="codigo_postal" name="data[FacturacionDato][codigo_postal]" type="text" class="validate" value="<?php echo @$d_fac["codigo_postal"] ?>">
			<label for="codigo_postal">
				CP
				<label id="codigo_postal-error" class="error validation_label" for="codigo_postal"></label>
			</label>
		</div>

		<div class="col s12">
			<br>
			<?php include $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER["HTTP_HOST"].'/php/privacidad.php' ?>
		</div>

		<div class="col s12 margensito-superior">
			<div class="right">
				<button id="btn_continuar" class="btn waves-effect waves-light disabled" type="submit">
					Guardar
				</button>
			</div>
		</div>

	</div>

</form>


<div class="col s12 right">
	<div id="el_preloader" class="preloader-wrapper small active hide">
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
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<?php if ($ya_pagado && @$habilita_comprobante): ?>
		<a href="/carrito/terminar_pedido/<?php echo $id_url ?>" class="btn_comprobante waves-effect waves-light btn boton-tabla">
			Facturar
		</a>
	<?php else: ?>
		<a href="/carrito/terminar_pedido/<?php echo $id_url ?>" class="btn_comprobante waves-effect waves-light btn boton-tabla">
			Comprobante
		</a>
	<?php endif ?>	

</div>


<br><br>


<?php $this->Html->script('no_espacios', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_sub_1").addClass("menu-pasado");
	$("#menu_sub_2").addClass("menu-pasado");
	$("#menu_sub_3").addClass("menu-pasado");
	$("#menu_sub_4").removeClass("href_desactivado");



	$(document).ready(function(){
		$('.modal').modal({
			dismissible: false
		});
	});

	$(document).on('click', '#acepto', function (e) {
		$("#privacidad").prop("checked", true);
		$("#btn_continuar").removeClass("disabled");
	});

	$(document).on('click', '#no_acepto', function (e) {
		$("#privacidad").prop("checked", false);
		$("#btn_continuar").addClass("disabled");
	});

	$(document).on("change", "#privacidad", function() {
		if ($(this).prop("checked"))
			$('#el_modal').modal('open');
		else
			$("#btn_continuar").addClass("disabled");
	});



	$('#CarritoPagarForm').validate({
		rules: {
			'data[FacturacionDato][razon_social]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][rfc]': {
				required: true,
				lettersonly: true,
				nowhitespace: true,
      			minlength: 12,
      			maxlength: 13
			},
			'data[FacturacionDato][calle]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][numero]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][colonia]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][ciudad]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][estado]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][pais]': {
				required: true,
				alphanumeric: true
			},
			'data[FacturacionDato][codigo_postal]': {
				required: true,
				alphanumeric: true
			},
		}
	});

	$(document).on("change", "#check_facturacion", function(){
		if ($(this).prop("checked"))
		{
			$(".btn_comprobante").addClass("hide");
			$("#CarritoPagarForm").removeClass("hide");
		}
		else
		{
			$(".btn_comprobante").removeClass("hide");
			$("#CarritoPagarForm").addClass("hide");
		}
	});


	$(document).on("click", ".btn_comprobante", function()
	{
		$("#el_preloader").removeClass("hide");
		$(this).addClass("disabled");
	});

	<?php if (@$habilita_comprobante): ?>
		$("#check_facturacion").prop("checked", true);
	<?php endif ?>

<?php $this->Html->scriptEnd(); ?>
