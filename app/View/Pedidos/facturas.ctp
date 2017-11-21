


<?php $this->set("breadcrumbs",
	'<a href="" class="breadcrumb">Cierre</a>
	<a class="breadcrumb">Reporte Facturas</a>'
) ?>


<div class="row">


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
				<option value="todos">Todos</option>
				<?php foreach ($colegios as $key => $colegio): ?>

					<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>

				<?php endforeach ?>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		<?php endif ?>
	
	</div>


	<div class="input-field col s12 m6 l8">
		
		<h5>
			Ventas facturadas en el periodo <?php echo $ciclo ?>
		</h5>
	
	</div>
</div>


<form action="/dashboard/descargar_excel" method="post" accept-charset="utf-8">

	<button id="btn_submit" class="btn waves-effect waves-light disabled" type="submit">
		Excel
	</button>

	<input type="hidden" name="data[nombre_archivo]" value="Cierre_Facturas_<?php echo $ciclo_actual ?>">

	<table class="bordered">
		<thead class="top-tabla">
			<tr>
				<th>Colegio</th>
				<th># Pedido</th>
				<th># Factura</th>
				<th>Razón Social</th>
				<th>RFC</th>
				<th>Fecha</th>
				<th>Alumno</th>
				<th>Importe</th>
				<th>IVA</th>
				<th>TOTAL</th>
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



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
		$("#menu_cierre").addClass("activado");
	});



	$(document).on("change", "#select_colegios", function()
	{
		sintesisActualizar();
	});




	function sintesisActualizar()
	{
		$("#aparece_loading").removeClass("hide");
		$('#cambiar_tbody').addClass("hide");

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/pedidos/facturas_actualizar',
	        success: function(response)
	        {
	            $('#cambiar_tbody').replaceWith(response);

	            $("#aparece_loading").addClass("hide");
				$("#btn_submit").removeClass("disabled");
	        },
	        data: {
	        	colegio_id: $('#select_colegios').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>