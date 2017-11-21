<div class="row center">
	<h5>
		<b>Caja</b>
	</h5>
</div>

<div class="row">

	<div class="input-field margin_nada col s12 m4">
		<?php if ($user_tipo == "Cajero"): ?>

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

	<div class="col s12 m8 right">

	<?php if ($caja_abierta): ?>
		<a onclick="seguroCerrar()" class="waves-effect waves-light btn right">
			Corte de Caja
		</a>
	<?php else: ?>
		<a href="#modal_abrir_caja" class="waves-effect waves-light btn parpadeando right">
			Abrir Caja
		</a>

		<div id="modal_abrir_caja" class="modal">
			<div class="modal-content">
				<form id="AbrirCajaForm" action="/cajas/abrir_caja" method="post" accept-charset="utf-8">
					<div class="row">
						<div class="input-field col s12">
							<input id="importe_ap" name="data[CorteCaja][importe_ap]" type="number">
							<label for="importe_ap">
								Importe de Apertura
								<label id="importe_ap-error" class="error validation_label" for="importe_ap"></label>
							</label>
						</div>
					</div>
					<button class="btn waves-effect waves-light" type="submit" name="action">
						Abrir Caja
					</button>
				</form>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat rigth">Cerrar</a>
			</div>
		</div>
	<?php endif ?>

	<a href="/corte_cajas" class="waves-effect waves-light btn right btn-right-space">
		Ver Cortes
	</a>

	<?php if ($caja_abierta): ?>
		<a href="/cajas/agregar_cobrar" class="waves-effect waves-light btn right btn-right-space">
			Agregar y Cobrar
		</a>
	<?php else: ?>
		<a class="waves-effect waves-light btn disabled right btn-right-space">
			Agregar y Cobrar
		</a>
	<?php endif ?>

</div>

</div>



<div id="tabla_y_buscador" class="<?php if ($user_tipo == "CCM") echo "hide" ?>">


	<div class="row">
		<div class="col s12 m6">
			<?php include 'buscador.ctp';?>
		</div>
		<div class="col s12 m3 right">
			<a id="mostrar_todos" class="waves-effect waves-light btn right">Mostrar Todos</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<div id="spinner" class="preloader-wrapper small active hide">
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


	<table id="table_hide" class="highlight bordered">
		<thead class="top-tabla">
			<tr>
				<th></th>
				<th>Fecha</th>
				<th class="center"># Pedido</th>
				<th class="right">Importe</th>
				<th>Padre</th>
				<th>Hijo</th>
			</tr>
		</thead>

		<tbody id="filtro_cambiar">
		</tbody>
	</table>

</div>

<br><br><br><br>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_caja").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
		$('.modal').modal();
	});


	function seguroCerrar()
	{
		Materialize.toast(
			'<div>Se cerrará la caja, ¿Seguro?</div><a href="/cajas/cerrar_caja" class="waves-effect waves-light btn white"><i class="material-icons azul_5">done</i></a>',
			3000
		);
	}


	$('#AbrirCajaForm').validate({
		rules: {
			'data[CorteCaja][importe_ap]': {
				required: true,
				alphanumeric: true
			}
		}
	});

	$(document).on("change", "#select_colegios", function() {
		$("#tabla_y_buscador").removeClass("hide");
		$('#filtro_cambiar').addClass("hide");
	});

	$(document).on("click", "#mostrar_todos", function() {
		$("#spinner").removeClass("hide");
		filtrarTabla();
	});

	function filtrarTabla()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/cajas/filtrar_tabla',
	        success: function(response)
	        {
	            $('#filtro_cambiar').replaceWith(response);
	            $("#spinner").addClass("hide");
	        },
	        data: {
	        	caja_abierta: <?php echo $caja_abierta ?>,
	        	<?php if ($user_tipo == "Cajero"): ?>
	        		colegio: <?php echo $colegio_id ?>
	        	<?php else: ?>
	        		colegio: $('#select_colegios').val()
	        	<?php endif ?>
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
