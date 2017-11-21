

<?php $this->set("breadcrumbs",
	'<a href="/cajas" class="breadcrumb">Cajas</a>
	<a class="breadcrumb">Ver Cortes</a>'
) ?>


<div class="row">
	<div class="col s12">
		<h4>Cortes de Caja</h4>
	</div>
</div>

<div class="row">
	
	<div class="input-field margin_nada col s12 m4">
		<?php if (in_array($user_tipo, array("Cajero", "Director"))): ?>

			<?php foreach ($colegios as $key => $colegio): ?>
				<?php if ($colegio["Colegio"]["id"] == $this->Session->read("Auth.User.colegio_id")): ?>
					<input readonly="" id="colegio" type="text" value="<?php echo $colegio["Colegio"]["nombre"] ?>">
					<?php $colegio_id = $colegio["Colegio"]["id"] ?>
				<?php endif ?>
			<?php endforeach ?>

			<label for="colegio">Colegio</label>

		<?php else: ?>

			<select id="select_colegios">
				<option value="todos" disabled selected>Colegio</option>
				<?php foreach ($colegios as $key => $colegio): ?>
					<option value="<?php echo $colegio["Colegio"]["id"] ?>"><?php echo $colegio["Colegio"]["nombre"] ?></option>
				<?php endforeach ?>
			</select>

		<?php endif ?>
	</div>

</div>


<div class="row" id="todos_los_cortes">
</div>


<div id="spinner" class="preloader-wrapper active hide">
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



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
	});


	$(document).on("change", "#select_colegios", function() {
		$("#todos_los_cortes").addClass("hide");
		$("#spinner").removeClass("hide");
		filtrarTabla();
	});


	function filtrarTabla()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/corte_cajas/filtrar_cortes',
	        success: function(response)
	        {
	            $('#todos_los_cortes').replaceWith(response);
	            $("#spinner").addClass("hide");
	            $('.collapsible').collapsible();
	        },
	        data: {
	        	cajero_id: <?php echo $this->Session->read("Auth.User.id") ?>,
	        	<?php if (in_array($user_tipo, array("Cajero", "Director"))): ?>
	        		colegio: <?php echo $colegio_id ?>
	        	<?php else: ?>
	        		colegio: $('#select_colegios').val()
	        	<?php endif ?>
	        }
	    });
	}

	<?php if (in_array($user_tipo, array("Cajero", "Director"))): ?>
		filtrarTabla();
	<?php endif ?>

<?php $this->Html->scriptEnd(); ?>