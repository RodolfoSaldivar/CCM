


<a id="btn_buscador" class='dropdown-button btn width_100 buscador' data-activates='buscador'>
	<i class="material-icons left hideclick">search</i>
	<span class="left hideclick">Buscar</span>
</a>

<ul id='buscador' class='dropdown-content'>
	<br>
	<li>
		<div class="input-field col s10 offset-s1">
			<input type="text" id="filtro_pedido">
			<label for="filtro_pedido"># Pedido</label>
		</div>
	</li>
	<li>
		<div class="input-field col s10 offset-s1">
			<input type="text" id="filtro_padre">
			<label for="filtro_padre">Padre</label>
		</div>
	</li>
	<li>
		<div class="input-field col s10 offset-s1">
			<input type="text" id="filtro_hijo">
			<label for="filtro_hijo">Hijo</label>
		</div>
	</li>
</ul>


<?php $this->Html->script('donetyping', array('inline' => false)); ?>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).on('click', '#buscador.dropdown-content', function (e) {
		e.stopPropagation();
	});


	$('#filtro_pedido').donetyping(function()
	{ filtrarResultado(); });
	$('#filtro_padre').donetyping(function()
	{ filtrarResultado(); });
	$('#filtro_hijo').donetyping(function()
	{ filtrarResultado(); });

	function filtrarResultado()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/cajas/buscador_filtrar',
	        success: function(response)
	        {
	            $('#filtro_cambiar').replaceWith(response);
	        },
	        data: {
	        	pedido_id: $('#filtro_pedido').val(),
	        	nombre_padre: $('#filtro_padre').val(),
	        	nombre_hijo: $('#filtro_hijo').val(),
	        	caja_abierta: <?php echo $caja_abierta ?>,
	        	<?php if ($user_tipo == "Cajero"): ?>
	        		colegio_id: <?php echo $colegio_id ?>
	        	<?php else: ?>
	        		colegio_id: $('#select_colegios').val()
	        	<?php endif ?>
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
