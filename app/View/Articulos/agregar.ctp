


<?php $this->set("breadcrumbs",
	'<a href="/articulos" class="breadcrumb">Artículos</a>
	<a class="breadcrumb">Agregar artículo</a>'
) ?>



<?php echo $this->Form->create(array('type' => 'file')); ?>



	<div class="row">
		<div class="instrucciones col s12 l3">Selecciona una imagen para el artículo.</div>
		<div class="col s12 l9">
			<div class="file-field input-field">
				<div class="btn">
					<span>Seleccionar imagen</span>
					<input type="file" name="data[Imagen]">
				</div>
				<div class="file-path-wrapper">
					<input class="file-path" type="text">
				</div>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="instrucciones col s12 l3">Elige el tipo de artículo, una ID para identificarlo y su descripción.</div>
		<div class="input-field col s12 l9">
			<select id="select_familia" name="data[Articulo][cat_fam_id]">
				<option value="" disabled selected>Tipo de artículo</option>
				<?php foreach ($catalogo_familias as $key => $familia): ?>

					<option value="<?php echo $familia["CatalogoFamilia"]["id"] ?>"><?php echo $familia["CatalogoFamilia"]["nombre"] ?></option>

				<?php endforeach ?>
			</select>
			<label id="select_familia-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>

		<div class="input-field col s12 m6 l3 push-l3" id="cambiar_id">
			<input id="identificador" name="data[Articulo][identificador]" type="text">
			<label for="identificador">
				ID
				<label id="identificador-error" class="error validation_label" for="identificador"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l6 push-l3">
			<input id="descripcion" name="data[Articulo][descripcion]" type="text">
			<label for="descripcion">
				Descripción
				<label id="descripcion-error" class="error validation_label" for="descripcion"></label>
			</label>
		</div>

	</div>



	<div class="row">
		<div class="instrucciones col s12 l3">Especifica el nivel de estudios a los que corresponde.</div>
		<div class="input-field col s12 m6 l3">
			<select id="select_colegios" name="data[Articulo][colegio_id]">
				<?php if (!in_array($this->Session->read("Auth.User.tipo"), array("Director"))): ?>
					<option value="nada" disabled selected>Colegio</option>
				<?php endif ?>

				<?php foreach ($colegios as $key => $colegio): ?>

					<?php if (in_array($this->Session->read("Auth.User.tipo"), array("Director"))): ?>
						<?php if ($colegio["Colegio"]["id"] == $this->Session->read("Auth.User.colegio_id")): ?>
							<option selected value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
						<?php endif ?>
					<?php else: ?>
						<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
					<?php endif ?>

				<?php endforeach ?>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>

		<div id="niveles_grados">
			<div class="input-field col s12 m6 l6">
				<select id="select_niveles" disabled="" name="data[Articulo][nivele_id]">
					<option value="nada" disabled selected>Nivel</option>
				</select>
			</div>

			<div class="input-field col s12 m6 l3 push-l3" id="div_grados">
				<select id="select_grados" disabled="" name="data[Articulo][grado_id]">
					<option value="nada" disabled selected>Grado</option>
				</select>
			</div>
		</div>

		<div class="input-field col s12 m6 l6 push-l3">
			<input readonly="" id="ciclo" name="data[ArticulosPrecio][ciclo]" type="text" value="<?php echo $cicloActual ?>">
			<label for="ciclo">
				Ciclo
				<label id="ciclo-error" class="error validation_label" for="ciclo"></label>
			</label>
		</div>

	</div>








	<div class="row">
		<div class="instrucciones col s12 l3">Determina todos los aspectos relativos al precio.</div>
		<div class="input-field col s12 m6 l3" id="cambiar_id">
			<input id="costo_ccm" name="data[ArticulosPrecio][costo_ccm]" type="number" min="0">
			<label for="costo_ccm">
				Costo
				<label id="costo_ccm-error" class="error validation_label" for="costo_ccm"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l3">
			<input id="precio_venta" name="data[ArticulosPrecio][precio_venta]" type="number" min="0">
			<label for="precio_venta">
				Precio Mayorista
				<label id="precio_venta-error" class="error validation_label" for="precio_venta"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l3" id="cambiar_id">
			<input id="iva" name="data[ArticulosPrecio][iva]" type="number" min="0">
			<label for="iva">
				IVA
				<label id="iva-error" class="error validation_label" for="iva"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l3 push-l3" id="cambiar_id">
			<input readonly="" id="costo_ccm_siva" type="text" value="0">
			<label for="costo_ccm_siva">
				Costo SIN IVA
				<label id="costo_ccm_siva-error" class="error validation_label" for="costo_ccm_siva"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l3 push-l3">
			<input readonly="" id="precio_venta_siva" type="text" value="0">
			<label for="precio_venta_siva">
				Precio Mayorista SIN IVA
				<label id="precio_venta_siva-error" class="error validation_label" for="precio_venta_siva"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l3 push-l3" id="cambiar_id">
			<input id="precio_publico" name="data[ArticulosPrecio][precio_publico]" type="number" min="0">
			<label for="precio_publico">
				Precio Público (sugerencia)
				<label id="precio_publico-error" class="error validation_label" for="precio_publico"></label>
			</label>
		</div>
	</div>




	<button class="btn waves-effect waves-light right btn-bottom" type="submit" name="action">
		Agregar artículo
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->script('colegios_niveles_grados', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_arti").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
	});


	$('#ArticuloAgregarForm').validate({
		rules: {
			'data[Articulo][descripcion]': {
				required: true,
				alphanumeric: true
			},
			'data[Articulo][identificador]': {
				required: true,
				alphanumeric: true
			},
			'data[ArticulosPrecio][costo_ccm]': {
				required: true,
				alphanumeric: true
			},
			'data[ArticulosPrecio][precio_venta]': {
				required: true,
				alphanumeric: true
			},
			'data[ArticulosPrecio][precio_publico]': {
				required: true,
				alphanumeric: true
			},
			'data[ArticulosPrecio][iva]': {
				required: true,
				alphanumeric: true
			}

		}
	});

	$('#ArticuloAgregarForm').submit(function(event)
	{
		var correcto = true;

		if(!$('#select_familia').val()){
			$("#select_familia-error").css("display", "initial");
			correcto = false;
		}

		if (!correcto)
			event.preventDefault();
	});

	$(document).on("change", "#select_familia", function()
	{
		$("#select_familia-error").css("display", "none");
	});




	$('#identificador').donetyping(function()
	{ revisarIdentificador(); });

	function revisarIdentificador()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/articulos/revisar_identificador',
	        success: function(response)
	        {
	            $('#cambiar_id').replaceWith(response);

	            ponerFocus($("#identificador"));

	            $('#identificador').donetyping(function()
				{ revisarIdentificador(); });
	        },
	        data: {
	        	nuevo_id: $('#identificador').val()
	        }
	    });
	}

	var tabla_nombre = "Articulo";


	$(document).on("keyup paste", "#costo_ccm", function(){
		actualizarCostos();
	});
	$(document).on("keyup paste", "#precio_venta", function(){
		actualizarCostos();
	});
	$(document).on("keyup paste", "#iva", function(){
		actualizarCostos();
	});

	function actualizarCostos()
	{
		var costo_ccm = $("#costo_ccm").val();
		var precio_venta = $("#precio_venta").val();
		var iva = $("#iva").val();

		var costo_ccm_siva = costo_ccm / (1 + iva/100);
		costo_ccm_siva = $.number(costo_ccm_siva, 2);
		$("#costo_ccm_siva").val(costo_ccm_siva);

		var precio_venta_siva = precio_venta / (1 + iva/100);
		precio_venta_siva = $.number(precio_venta_siva, 2);
		$("#precio_venta_siva").val(precio_venta_siva);
	}

<?php $this->Html->scriptEnd(); ?>
