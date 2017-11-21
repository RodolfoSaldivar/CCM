


<form id="CarritoPagarForm" action="/facturacion_datos/actualizar" method="post" accept-charset="utf-8">

	<div class="row margin_nada">

		<h5>
			<b>Datos de Facturación</b>
		</h5>
	</div>

	<input type="hidden" name="data[asociado_id]" value="<?php echo $asociado_id; ?>">

	<div class="row margin_nada margensito-superior">
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

		<div class="col s12 margensito-superior">

			<div class="right">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button id="btn_continuar" class="btn waves-effect waves-light" type="submit">
					Facturar
				</button>
			</div>

			<div id="el_preloader" class="preloader-wrapper small active right hide">
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

</form>



<?php $this->Html->script('no_espacios', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on("click", "#btn_continuar", function()
	{
		if ($('#CarritoPagarForm').valid())	
		{
			$("#el_preloader").removeClass("hide");
			$(this).addClass("disabled");
		}	
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

<?php $this->Html->scriptEnd(); ?>