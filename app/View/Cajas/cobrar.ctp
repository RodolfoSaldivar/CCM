


<?php $this->set("breadcrumbs",
	'<a href="/cajas" class="breadcrumb">Caja</a>'.
	'<a class="breadcrumb">Cobrar</a>'
) ?>

<h5 class="row center">
	<b>CAJA</b>
</h5>

<form id="FinalizarCobroForm" action="/cajas/finalizar_cobro" method="post" accept-charset="utf-8">

<input type="hidden" name="data[pedido_id]" value="<?php echo $pedido["Pedido"]["id"] ?>">

<div class="row">

	<div class="col s12 m3 l3">
			<b>Fecha </b>
			<?php echo $pedido["Pedido"]["fecha_pedido"] ?></br>
			<b>Pedido </b>
			<?php echo $pedido["Pedido"]["id"] ?></br>
			<b>Cliente </b>
			<?php echo $pedido["CicloHijo"]["padre_nombre"] ?></br></br>
			<b>Colegio</b>
			<?php echo $pedido["CicloHijo"]["colegio_nombre"] ?></br>
			<b>Alumno</b>
			<?php echo $pedido["CicloHijo"]["hijo_nombre"] ?></br>
	</div>

	<div class="col s12 m9 l9" id="contenedor_pagos">

		<?php $importe_total = 0 ?>
		<?php foreach ($paquetes as $key => $paquete): ?>
			<?php $importe_subtotal = $paquete["PedidosPaquete"]["cantidad"] * $paquete["PedidosPaquete"]["importe"] ?>
			<?php $importe_total+= $importe_subtotal ?>
		<?php endforeach ?>

		<b>Importe a Pagar</b>
		$ <?php echo number_format($importe_total, 2) ?>
		<br><br>

		<input type="hidden" name="data[importe_pagar]" value="<?php echo $importe_total ?>">

		<div class="row margin_nada" id="row_0">
			<div class="margin_nada input-field col s12 m4 un_select">
				<select id="forma_pago_0" name="data[Pago][0][forma_pago]" onchange="removerRequerido(0)">
					<option value="nada" disabled selected>Forma de Pago</option>
					<option value="efectivo">Efectivo</option>
					<option value="banco">Deposito Banco</option>
					<option value="cheque">Cheque</option>
					<option value="tarjeta">Tarjeta</option>
				</select>
				<label><label id="forma_pago_0-error" class="validation_label" for="forma_pago_0">*Requerido</label></label>
			</div>
			<div class="margin_nada input-field col s12 m4">
				<input id="importe_0" class="un_importe" type="number" name="data[Pago][0][importe]">
          		<label for="importe_0">
          			Importe
          			<label id="importe_0-error" class="error validation_label" for="importe_0"></label>
          		</label>
			</div>
			<div class="margin_nada input-field col s12 m4">
				<input id="referencia_0" type="text" name="data[Pago][0][referencia]">
          		<label for="referencia_0">
          			Referencia
          			<label id="referencia_0-error" class="error validation_label" for="referencia_0"></label>
          		</label>
			</div>
		</div>


	</div>
</div>


<div class="row">

	<div class="col s12 m9 push-m3 l9 push-l3">

			<a class="btn-floating waves-effect waves-light btn_peque right" id="agregar_metodo_pago">
				<i class="material-icons">add</i>
			</a>


			<span id="texto_cobro_cambio" style="color: #939598; font-size: 1.64rem; line-height: 110%;">
				Por cobrar:
			</span>
			</br>
			<span style="font-size: 25px;">
				$ <span id="importe_cobro_cambio">
					<?php echo number_format($importe_total, 2) ?>
				</span>
			</span>


		</div>
</div>


<div class="row right margen-superior">

	<a class="waves-effect waves-light btn" href="#paquetes_a_llevar">
		Paquetes a Llevar
	</a>

	<!-- Modal Structure -->
	<div id="paquetes_a_llevar" class="modal">
		<h5 class="center margensito-superior"><b>Paquetes a llevar</b></h5>
		<div class="modal-content">
			<table class="bordered highlight">
				<thead class="top-tabla">
					<tr>
						<th>Descripción</th>
						<th class="center">Cantidad</th>
						<th class="decimal">Importe</th>
					</tr>
				</thead>

				<tbody>
					<?php $importe_total = 0 ?>
					<?php foreach ($paquetes as $key => $paquete): ?>
						<tr>
							<td>
								<?php echo $paquete["Paquete"]["descripcion"] ?>
							</td>
							<td class="center">
								<?php echo $paquete["PedidosPaquete"]["cantidad"] ?>
							</td>
							<td class="decimal">
								<?php $importe_subtotal = $paquete["PedidosPaquete"]["cantidad"] * $paquete["PedidosPaquete"]["importe"] ?>
								<?php $importe_total+= $importe_subtotal ?>
								<span class="decimal">
									<?php echo number_format($importe_subtotal, 2) ?>
								</span>
							</td>
						</tr>
					<?php endforeach ?>
					<tr>
						<td></td>
						<td class="center">
							<b>TOTAL:</b>
						</td>
						<td class="decimal">
							<span id="importe_total" class="decimal">
								<b><?php echo number_format($importe_total, 2) ?></b>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="modal-footer">
				<a class="modal-action modal-close waves-effect waves-green btn">Cerrar</a>
			</div>
	</div>


	<a class="waves-effect waves-light btn" href="#datos_facturacion">
		Datos de Facturación
	</a>

	<div class="row margen-superior">
		<?php if ($this->Session->read("Auth.User.tipo") == "CCM"): ?>
			<button class="btn waves-effect waves-light right disabled" style="margin-left: 10px;">
				Comprobante
			</button>

			<button class="btn waves-effect waves-light right disabled">
				Facturar
			</button>

			<h5 class="red-text">*Solo para Cajeros</h5>
		<?php else: ?>
			<button id="btn_terminar" class="btn waves-effect waves-light right disabled" type="submit" name="data[forma]" value="comprobante" style="margin-left: 10px;">
				Comprobante
			</button>

			<button id="btn_facturar" class="btn waves-effect waves-light right disabled" <?php if ($d_fac["rfc"]) echo 'type="submit" name="data[forma]" value="factura"' ?> >
				Facturar
			</button>
		<?php endif ?>
	</div>

</div>

<div id="preloader" class="preloader-wrapper small active right hide">
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

</form>


<div id="datos_facturacion" class="modal modal-fixed-footer">
	<div class="modal-content">
		<form id="CarritoPagarForm" action="/facturacion_datos/actualizar" method="post" accept-charset="utf-8">

			<div class="row">

				<input type="hidden" name="data[asociado_id]" value="<?php echo $pedido["Pedido"]["padre_id"]; ?>">

				<h5 class="center"><b>Datos de Facturación</b></h5>
				<div class="input-field col s12 margensito">
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
				<br>
				<div class="domicilio-fiscal">Domicilio Fiscal</div>
				<br>
				<div class="input-field col s12 m4">
					<input id="calle" name="data[FacturacionDato][calle]" type="text" class="validate" value="<?php echo @$d_fac["calle"] ?>">
					<label for="calle">
						Calle
						<label id="calle-error" class="error validation_label" for="calle"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="numero" name="data[FacturacionDato][numero]" type="text" class="validate" value="<?php echo @$d_fac["numero"] ?>">
					<label for="numero">
						Número
						<label id="numero-error" class="error validation_label" for="numero"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="numero_interior" name="data[FacturacionDato][numero_interior]" type="text" class="validate" value="<?php echo @$d_fac["numero_interior"] ?>">
					<label for="numero_interior">Número
						Interior
						<label id="numero_interior-error" class="error validation_label" for="numero_interior"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="colonia" name="data[FacturacionDato][colonia]" type="text" class="validate" value="<?php echo @$d_fac["colonia"] ?>">
					<label for="colonia">
						Colonia
						<label id="colonia-error" class="error validation_label" for="colonia"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="localidad" name="data[FacturacionDato][localidad]" type="text" class="validate" value="<?php echo @$d_fac["localidad"] ?>">
					<label for="localidad">
						Localidad
						<label id="localidad-error" class="error validation_label" for="localidad"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="ciudad" name="data[FacturacionDato][ciudad]" type="text" class="validate" value="<?php echo @$d_fac["ciudad"] ?>">
					<label for="ciudad">
						Ciudad
						<label id="ciudad-error" class="error validation_label" for="ciudad"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="estado" name="data[FacturacionDato][estado]" type="text" class="validate" value="<?php echo @$d_fac["estado"] ?>">
					<label for="estado">
						Estado
						<label id="estado-error" class="error validation_label" for="estado"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="pais" name="data[FacturacionDato][pais]" type="text" class="validate"  value="México">
					<label for="pais">
						País
						<label id="pais-error" class="error validation_label" for="pais"></label>
					</label>
				</div>
				<div class="input-field col s12 m4">
					<input id="codigo_postal" name="data[FacturacionDato][codigo_postal]" type="text" class="validate" value="<?php echo @$d_fac["codigo_postal"] ?>">
					<label for="codigo_postal">
						CP
						<label id="codigo_postal-error" class="error validation_label" for="codigo_postal"></label>
					</label>
				</div>


				<div class="col s12">
					<div class="right">
						<button id="continuar" class="btn waves-effect waves-light" type="submit">
							Guardar
						</button>
					</div>
				</div>

			</div>

		</form>
	</div>

	<div class="modal-footer">
			<a class="modal-action modal-close waves-effect waves-green btn">Cerrar</a>
	</div>
</div>


<br><br><br><br>


<?php $this->Html->script('no_espacios', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_caja").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
		$('.modal').modal();
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

	$('#FinalizarCobroForm').validate({
		rules: {
			'data[Pago][0][importe]': {
				required: true,
				alphanumeric: true
			},
			'data[Pago][0][referencia]': {
				required: true,
				alphanumeric: true
			}
		}
	});

	$('#FinalizarCobroForm').submit(function()
	{
		var todo_correcto = 1;
		$(".un_select").each(function()
		{
			if ($(this).find('select option:selected').val() == "nada")
			{
				$(this).find('.validation_label').css("display", "initial");
				event.preventDefault();
				todo_correcto = 0;
			}
		})

		if (!$("#FinalizarCobroForm").valid())
			todo_correcto = 0;

		if (todo_correcto)
		{
			$("#preloader").removeClass("hide");
			$("#btn_terminar").addClass("hide");
			$("#btn_facturar").addClass("hide");
			window.open("/pedidos/ver_pdf/<?php echo $pedido["Pedido"]["id"] ?>/1", '_blank')
		}
	});

	function removerRequerido(igual_cont)
	{
		$("#forma_pago_"+igual_cont+"-error").css("display", "none");
	}





	var cont = 0;

	$(document).on("keyup change paste", "#importe_0", function() {
		restarImporte(0);
	});

	function restarImporte(igual_cont)
	{
		var monto = $("#importe_"+igual_cont).val();

		var original = <?php echo $importe_total ?>;

		$(".un_importe").each(function() {
			original-= $(this).val();
		});

		if (original <= 0)
		{
			$("#texto_cobro_cambio").text("Devolver: ");
			$("#btn_terminar").removeClass("disabled");
			<?php if ($d_fac["rfc"]): ?>
				$("#btn_facturar").removeClass("disabled");
			<?php endif ?>
		}
		else
		{
			$("#texto_cobro_cambio").text("Por cobrar: ");
			$("#btn_terminar").addClass("disabled");
			$("#btn_facturar").addClass("disabled");
		}

		$("#importe_cobro_cambio").text(
			$.number(
				Math.abs(original), 2
			)
		);
	}






	$(document).on("click", "#agregar_metodo_pago", function()
	{
		if ($("#forma_pago_"+cont).val() &&
			$("#importe_"+cont).val() &&
			$("#referencia_"+cont).val())
		{
			cont++;

			agregarMetodoPago(cont);
		}
	});

	function agregarMetodoPago(igual_cont)
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/cajas/agregar_metodo_pago',
	        success: function(response)
	        {
	            $('#contenedor_pagos').append(response);

	            $(document).on("keyup change paste", "#importe_"+igual_cont, function() {
					restarImporte(igual_cont);
				});

				$('#importe_'+igual_cont).rules("add", { required:true, alphanumeric:true });
				$('#referencia_'+igual_cont).rules("add", { required:true, alphanumeric:true });

	            $('select').material_select();
	        },
	        data: {
	        	cont: igual_cont
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
