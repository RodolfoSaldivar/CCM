<div class="row center">
	<h5>
		<b>Asociados</b>
	</h5>
</div>


<div class="row right">
	<a href="/asociados/agregar" class="waves-effect waves-light btn">
		Agregar asociado
	</a>
	<a href="/asociados/subir_excel" class="waves-effect waves-light btn hide">
		Importar asociados
	</a>
</div>



<table class="bordered highlight responsive-table">
	<thead class="top-tabla">
		<tr>
			<th>Nombre</th>
			<th>Tipo</th>
			<th>Mail</th>
			<th>Celular</th>
			<th class="center">Activo</th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($asociados as $key => $asociado): ?>

			<tr class="pointer">
				<td>
					<?php echo $asociado["Asociado"]["a_paterno"]." ".
								$asociado["Asociado"]["a_materno"].", ".
								$asociado["Asociado"]["nombre"] ?>
				</td>
				<td>
					<?php echo $asociado["Asociado"]["tipo"]; ?>
				</td>
				<td>
					<?php echo $asociado["Asociado"]["mail"]; ?>
				</td>
				<td>
					<?php echo $asociado["Asociado"]["celular"]; ?>
				</td>
				<td class="center" id="poner_switch_<?php echo $asociado["Asociado"]["id"] ?>">
					<div class="switch">
						<label>
							<input <?php if ($asociado["Asociado"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Asociado][<?php echo $asociado["Asociado"]["id"] ?>]' id='asociado_<?php echo $asociado["Asociado"]["id"] ?>' value='<?php echo $asociado["Asociado"]["activo"] ?>' onchange="activoActualizar(<?php echo $asociado["Asociado"]["id"] ?>)">
							<span class="lever"></span>
						</label>
					</div>
				</td>
				<td class="center ver_editar_acciones">
					<a class="hide-on-small-only" href="/asociados/editar/<?php echo $asociado["Asociado"]["id"] ?>">
						<span class="icon-editar dash-icon"></span>
				</td>
			</tr>

		<?php endforeach ?>
	</tbody>
</table>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_asoc").addClass("activado");

	function activoActualizar(asociado_id)
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/asociados/activo_actualizar',
	        success: function(response)
	        {
	            $('#poner_switch_'+asociado_id).children().replaceWith(response);
	        },
	        data: {
	        	asociado_id: asociado_id,
	        	activo: $('#asociado_'+asociado_id).val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
