


<?php $this->set("breadcrumbs",
	'<a href="/paquetes" class="breadcrumb">Paquetes</a>
	<a class="breadcrumb">Editar paquete</a>'
) ?>



<?php echo $this->Form->create(array('type' => 'file')); ?>

	<?php echo $this->Form->hidden('id', array('value' => $paquete["Paquete"]["id"], 'name' => 'data[Paquete][id]')); ?>



	<div class="row">
		<div class="instrucciones col s12 l3">Selecciona una imagen para el artículo.</div>
		<div class="col s12 l9">

		<?php if (!empty($paquete["Paquete"]["imagen"])): ?>

			<input type="hidden" name="data[Paquete][imagen]" value="<?php echo $paquete["Paquete"]["imagen"] ?>">

				<img class="materialboxed imgpreview" src="data:image/png;base64,<?php echo $paquete["Paquete"]["imagen"] ?>" />

		<?php endif ?>
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
		<div class="instrucciones col s12 l3">Elige una ID y descripción para identificarlo.</div>
		<div class="input-field col s12 m6 l3" id="cambiar_id">
			<input id="identificador" name="data[Paquete][identificador]" type="text" value="<?php echo $paquete["Paquete"]["identificador"] ?>">
			<label for="identificador">
				ID
				<label id="identificador-error" class="error validation_label" for="identificador"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l6">
			<input id="descripcion" name="data[Paquete][descripcion]" type="text" value="<?php echo $paquete["Paquete"]["descripcion"] ?>">
			<label for="descripcion">
				Descripción
				<label id="descripcion-error" class="error validation_label" for="descripcion"></label>
			</label>
		</div>
	</div>



	<div class="row">
		<div class="instrucciones col s12 l3">Especifica el nivel de estudios al que corresponde.</div>
		<div class="input-field col s12 m6 l3">
			<select id="select_colegios" name="data[Paquete][colegio_id]">
				<option value="nada" selected>Todos</option>
				<?php foreach ($colegios as $key => $colegio): ?>

					<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>

				<?php endforeach ?>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>

		<div id="niveles_grados">

			<div class="input-field col s12 m6 l3">
				<select id="select_niveles" disabled="" name="data[Paquete][nivele_id]">
					<option value="nada" disabled selected>Nivel</option>
				</select>
			</div>

			<div class="input-field col s12 m6 l3" id="div_grados">
				<select id="select_grados" disabled="" name="data[Paquete][grado_id]">
					<option value="nada" disabled selected>Grado</option>
				</select>
			</div>

		</div>
	</div>



	<table class="bordered highlight margen-superior">
		<thead class="top-tabla">
			<tr>
				<th>ID</th>
				<th>Descripción</th>
				<th>Familia</th>
				<th>Cantidad</th>
				<th>Precio Colegio<br>p/u</th>
				<th>PrecioPúblico<br>p/u</th>
				<th>PrecioPúblico<br>p/u SIN IVA</th>
				<th>IVA</th>
				<th></th>
			</tr>
		</thead>

		<tbody id="poner_tr">
			<?php foreach ($art_paq as $key => $articulo): ?>

				<tr id="tr_<?php echo $key ?>">
					<input type="hidden" name="data[ArticulosPaquete][<?php echo $key ?>][id]" value="<?php echo $articulo["Articulo"]["id"] ?>">
					<td>
						<input readonly="" id="articulo_<?php echo $key ?>" name="data[ArticulosPaquete][<?php echo $key ?>][identificador]" type="text" value="<?php echo $articulo["Articulo"]["identificador"] ?>">
						<label id="articulo_<?php echo $key ?>-error" class="error validation_label" for="articulo_<?php echo $key ?>">* Requerido</label>
					</td>
					<td>
						<?php echo $articulo["Articulo"]["descripcion"] ?>
					</td>
					<td>
						<?php echo $articulo["Articulo"]["familia_nombre"] ?>
					</td>
					<td>
						<input id="cantidad_<?php echo $key ?>" name="data[ArticulosPaquete][<?php echo $key ?>][cantidad]" type="number" min="0" step="any" class="todas_cantidad" value="<?php echo $articulo["ArticulosPaquete"]["cantidad"] ?>">
						<label id="cantidad_<?php echo $key ?>-error" class="error validation_label" for="cantidad_<?php echo $key ?>">* Requerido</label>
					</td>
					<td>
						<?php echo $articulo["Articulo"]["precio_venta"] ?>
					</td>
					<td>
						<input id="precio_publico_<?php echo $key ?>" name="data[ArticulosPaquete][<?php echo $key ?>][precio_publico]" type="number" min="0" step="any" value="<?php echo $articulo["ArticulosPaquete"]["precio_publico"] ?>" class="todos_precio_publico">
						<label id="precio_publico_<?php echo $key ?>-error" class="error validation_label" for="precio_publico_<?php echo $key ?>">* Requerido</label>
					</td>
					<td>
						<div id="publico_siva_<?php echo $key ?>">
							<?php echo $articulo["ArticulosPaquete"]["precio_publico"] / (1 + $articulo["Articulo"]["iva"] / 100) ?>
						</div>
					</td>
					<td>
						<div id="iva_<?php echo $key ?>">
							<?php echo $articulo["Articulo"]["iva"] ?>
						</div>
					</td>
					<td>
						<a id="remover_<?php echo $key; ?>" class="btn-floating btn-small waves-effect waves-black"><i class="material-icons">remove</i></a>
					</td>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>


	<br>
	<a id="btn_agregar_articulo" class="waves-effect waves-light btn btn-bottom">
		Agregar Artículo
	</a>
	<button class="btn waves-effect waves-light right btn-bottom" type="submit" name="action">
		Guardar
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->script('colegios_niveles_grados', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_paqu").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
		Materialize.toast('No olvide seleccionar el colegio.', 5000);
	});


	$('#PaqueteEditarForm').validate({
		rules: {
			'data[Paquete][descripcion]': {
				required: true,
				alphanumeric: true
			},
			'data[Paquete][identificador]': {
				required: true,
				alphanumeric: true
			},
			'data[ArticulosPaquete][1][identificador]': {
				required: true,
				alphanumeric: true
			}

		}
	});

	var tabla_nombre = "Paquete";

	<?php if (!empty($colegio_id)): ?>

		$('#select_colegios').val(<?php echo $colegio_id ?>);
		dropdownNiveles(<?php echo $nivele_id ?>, <?php echo $grado_id ?>);

	<?php endif ?>



	<?php $cont_articulo = 1; ?>

	<?php
	if (!empty($art_paq))
	foreach ($art_paq as $key => $articulo): ?>

		$("#remover_<?php echo $key ?>").click(function(){
			removerTr(<?php echo $key ?>);
		});

		actualizarCosto(<?php echo $key ?>);

        $('#precio_publico_<?php echo $key ?>').change(function(){
			actualizarCosto(<?php echo $key ?>);
		});

		<?php $cont_articulo = $key + 1; ?>

	<?php endforeach ?>

	var cont_articulo = <?php echo $cont_articulo ?>;




	$('#identificador').donetyping(function()
	{ revisarIdentificador(); });

	function revisarIdentificador()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/paquetes/revisar_identificador',
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




	$(document).on("click", "#btn_agregar_articulo", function()
	{
		agregarArticulo();
	});

	function agregarArticulo()
	{
		cont_articulo++;

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/paquetes/agregar_articulo',
	        success: function(response)
	        {
	            $("#poner_tr").append(response);

	            $('#articulo_'+cont_articulo).donetyping(function(){
					traer_info(cont_articulo);
				});

				$("#remover_"+cont_articulo).click(function(){
					removerTr(cont_articulo);
				});
	        },
	        data: {
	        	cont_articulo: cont_articulo
	        }
	    });
	}





	$('#articulo_1').donetyping(function(){
		traer_info(1);
	});

	function traer_info(num_articulo)
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/articulos/traer_info',
	        success: function(response)
	        {
	            $("#tr_"+num_articulo).replaceWith(response);

	            $('#articulo_'+num_articulo).donetyping(function(){
					traer_info(num_articulo);
				});

	            $('#precio_publico_'+num_articulo).change(function(){
					actualizarCosto(num_articulo);
				});

				$("#remover_"+num_articulo).click(function(){
					removerTr(num_articulo);
				});

				ponerFocus($('#articulo_'+num_articulo));
	        },
	        data: {
	        	identificador: $('#articulo_'+num_articulo).val(),
	        	cont_articulo: num_articulo,
	        	colegio_id: $("#select_colegios").val(),
	        	nivele_id: $("#select_niveles").val(),
	        	grado_id: $("#select_grados").val()
	        }
	    });
	}



	function removerTr(num_articulo)
	{
		$("#tr_"+num_articulo).remove();
	}


	function actualizarCosto(num_articulo)
	{
		var precio_publico = $("#precio_publico_"+num_articulo).val();
		var iva = $("#iva_"+num_articulo).text();
		var publico_siva = precio_publico / (1 + iva / 100);
		publico_siva = (publico_siva).toFixed(2);

		$("#publico_siva_"+num_articulo).text(publico_siva);
	}

<?php $this->Html->scriptEnd(); ?>
