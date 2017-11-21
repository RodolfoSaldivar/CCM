<div class="row center">
	<h5>
		<b>Paquetes</b>
	</h5>
</div>


<div class="row col s12">
	<a href="/paquetes/agregar" class="waves-effect waves-light btn right">
		Agregar paquete
	</a>
	<a href="/paquetes/subir_excel" class="waves-effect waves-light btn right btn-right-space">
		Importar paquetes
	</a>
	<a href="/articulos_paquetes/subir_excel" class="waves-effect waves-light btn right btn-right-space">
		Importar precios
	</a>
</div>



<div class="row">
	<div class="input-field col s12 m6 l4">
		<select id="select_colegios" name="data[CicloHijo][colegio_id]">
			<option value="nada" disabled selected>Colegio</option>
			<?php foreach ($colegios as $key => $colegio): ?>

				<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>

			<?php endforeach ?>
		</select>
		<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
	</div>

	<div id="niveles_grados">

		<div class="input-field col s12 m6 l4">
			<select id="select_niveles" disabled="" name="data[CicloHijo][nivele_id]">
				<option value="nada" disabled selected>Nivel</option>
			</select>
		</div>

		<div class="input-field col s12 m6 l4" id="div_grados">
			<select id="select_grados" disabled="" name="data[CicloHijo][grado_id]">
				<option value="nada" disabled selected>Grado</option>
			</select>
		</div>

	</div>
</div>



<div class="row padding_bottom margin_nada center hide" id="aparece_loading">
	<div class="preloader-wrapper big active">
		<div class="spinner-layer azul_5">
			<div class="circle-clipper left"><div class="circle"></div></div>
			<div class="gap-patch"><div class="circle"></div></div>
			<div class="circle-clipper right"><div class="circle"></div></div>
		</div>
	</div>
</div>



<table id="la_tabla" class="bordered highlight responsive-table hide">
	<thead class="top-tabla">
		<tr>
			<th>ID</th>
			<th>Descripción</th>
			<th>Colegio</th>
			<th>Nivel</th>
			<th>Grado</th>
			<th>
				<span class="decimal">Precio<br>Público</span>
			</th>
			<th>
				<span class="decimal">Precio<br>Colegio</span>
			</th>
			<th class="center">Activo</th>
			<th></th>
		</tr>
	</thead>


	<tbody id="filtro_cambiar">
	</tbody>
</table>



<?php $this->Html->script('colegios_niveles_grados', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

  $("#menu_paqu").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
	});


	$(document).on("change", "#select_colegios", function(){
		$("#la_tabla").addClass("hide");
		dropdownNiveles();
	});

	function dropdownNiveles()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/niveles/dropdown_niveles_con_todos',
	        success: function(response)
	        {
	            $('#niveles_grados').replaceWith(response);

				$('select').material_select();
	        },
	        data: {
	        	colegio: $('#select_colegios').val()
	        }
	    });
	}


	$(document).on("change", "#select_niveles", function(){
		$("#la_tabla").addClass("hide");
		dropdownGrados();
		if ($(this).val() == "todos")
			mostrarPaquetes();
	});

	function dropdownGrados()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/grados/dropdown_grados_con_todos',
	        success: function(response)
	        {
	            $('#div_grados').replaceWith(response);

				$('select').material_select();
	        },
	        data: {
	        	nivel: $('#select_niveles').val()
	        }
	    });
	}


	$(document).on("change", "#select_grados", function(){
		$("#la_tabla").addClass("hide");
		mostrarPaquetes();
	});

	function mostrarPaquetes()
	{
		$("#aparece_loading").removeClass("hide");


		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/paquetes/index_mostrar_paquetes',
	        success: function(response)
	        {
	            $('#filtro_cambiar').replaceWith(response);

	            $("#aparece_loading").addClass("hide");
				$("#la_tabla").removeClass("hide");
	        },
	        data: {
	        	colegio_id: $('#select_colegios').val(),
	        	nivele_id: $('#select_niveles').val(),
	        	grado_id: $('#select_grados').val()
	        }
	    });
	}

	function activoActualizar(paquete_id)
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/paquetes/activo_actualizar',
	        success: function(response)
	        {
	            $('#poner_switch_'+paquete_id).children().replaceWith(response);
	        },
	        data: {
	        	paquete_id: paquete_id,
	        	activo: $('#paquete_'+paquete_id).val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
