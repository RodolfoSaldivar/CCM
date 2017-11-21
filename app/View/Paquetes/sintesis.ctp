


<?php $this->set("breadcrumbs",
	'<a href="" class="breadcrumb">Reportes</a>
	<a href="" class="breadcrumb">Paquetes</a>
	<a class="breadcrumb">Síntesis</a>'
) ?>


<div class="row">

	<div class="input-field col s12 m6 l4">
		<select id="select_ciclos">
			<?php foreach ($ciclos as $key => $ciclo): ?>

				<option value="<?php echo $ciclo ?>"><?php echo $ciclo ?></option>

			<?php endforeach ?>
		</select>
	</div>


	<div class="input-field col s12 m6 l4">
		
		<?php if ($user_tipo == "Cajero"): ?>

			<?php foreach ($colegios as $key => $colegio): ?>
				<?php if ($colegio["Colegio"]["id"] == $this->Session->read("Auth.User.colegio_id")): ?>
					<input readonly="" id="colegio" type="text" value="<?php echo $colegio["Colegio"]["nombre"] ?>">
				<?php endif ?>
			<?php endforeach ?>

		<?php else: ?>
			<select id="select_colegios">
				<option value="0" disabled selected>Colegio</option>
				<?php foreach ($colegios as $key => $colegio): ?>

					<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>

				<?php endforeach ?>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		<?php endif ?>
	
	</div>
</div>


<form action="/dashboard/descargar_excel" method="post" accept-charset="utf-8">

	<button id="btn_submit" class="btn waves-effect waves-light disabled" type="submit">
		Excel
	</button>

	<input type="hidden" name="data[nombre_archivo]" value="Paquetes_Sintesis">

	<table class="bordered top-tabla">
		<thead class="top-tabla">
			<tr>
				<th>Colegio</th>
				<th>Nivel</th>
				<th>Grado</th>
				<th>Paquete ID</th>
				<th>Descripción</th>
				<th>Cobrado</th>
				<th>No Cobrado</th>
				<th>Cancelados</th>
				<th>Total</th>
			</tr>
		</thead>

		<tbody id="cambiar_tbody">
		</tbody>
	</table>
</form>

<br>
<div class="row padding_bottom margin_nada center hide" id="aparece_loading">
	<div class="preloader-wrapper big active">
		<div class="spinner-layer azul_5">
			<div class="circle-clipper left"><div class="circle"></div></div>
			<div class="gap-patch"><div class="circle"></div></div>
			<div class="circle-clipper right"><div class="circle"></div></div>
		</div>
	</div>
</div>

<br><br><br>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_pedi").addClass("activado");


	$(document).ready(function() {
		$('select').material_select();
	});



	$(document).on("change", "#select_ciclos, #select_colegios", function()
	{
		var valido = 1;

		<?php if ($user_tipo != "Cajero"): ?>
			if (!$("#select_colegios").val())
			{
				$("#select_colegios-error").css("display", "initial");
				valido = 0;
			}
		<?php endif ?>

		if (valido)
			sintesisActualizar();
	});

	$(document).on("change", "#select_colegios", function()
	{
		$("#select_colegios-error").css("display", "none");
	});




	function sintesisActualizar()
	{
		$("#aparece_loading").removeClass("hide");
		$('#cambiar_tbody').addClass("hide");

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/paquetes/sintesis_actualizar',
	        success: function(response)
	        {
	            $('#cambiar_tbody').replaceWith(response);

	            $("#aparece_loading").addClass("hide");
				$("#btn_submit").removeClass("disabled");
	        },
	        data: {
	        	ciclo: $('#select_ciclos').val(),
	        	<?php if ($user_tipo == "Cajero"): ?>
	        		colegio_id: <?php echo $this->Session->read("Auth.User.colegio_id") ?>
        		<?php else: ?>
        			colegio_id: $('#select_colegios').val()
	        	<?php endif ?>
	        }
	    });
	}


	<?php if ($user_tipo == "Cajero"): ?>
		$("#select_ciclos").trigger("change");
	<?php endif ?>

<?php $this->Html->scriptEnd(); ?>