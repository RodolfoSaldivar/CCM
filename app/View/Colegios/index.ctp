<div class="row center">
	<h5>
		<b>Colegios</b>
	</h5>
</div>

<div class="row right">
	<a href="/colegios/agregar" class="waves-effect waves-light btn">
		Agregar colegio
	</a>
	<a href="/colegios/subir_excel" class="waves-effect waves-light btn">
		Importar colegios
	</a>
</div>



<table class="bordered highlight responsive-table">
	<thead class="top-tabla">
		<tr>
			<th>ID</th>
			<th>Colegios</th>
			<?php foreach ($catalogo_niveles as $key => $catalogo): ?>
				<th class="center"><?php echo $catalogo["CatalogoNivele"]["nombre"] ?></th>
			<?php endforeach ?>
			<th class="center">Activo</th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($colegios as $keyC => $colegio): ?>

			<tr class="pointer">
				<td>
					<?php echo $colegio["Colegio"]["identificador"]; ?>
				</td>
				<td>
					<?php echo $colegio["Colegio"]["nombre"]; ?>
				</td>
				<?php foreach ($catalogo_niveles as $keyC => $catalogo): ?>
					<?php if (@in_array($catalogo["CatalogoNivele"]["id"], $colegio["Niveles"])): ?>
						<td class="center">
							<i class="material-icons">check</i>
						</td>
					<?php else: ?>
						<td></td>
					<?php endif ?>
				<?php endforeach ?>
				<td class="center" id="poner_switch_<?php echo $colegio["Colegio"]["id"] ?>">
					<div class="switch">
						<label>
							<input <?php if ($colegio["Colegio"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Colegio][<?php echo $colegio["Colegio"]["id"] ?>]' id='colegio_<?php echo $colegio["Colegio"]["id"] ?>' value='<?php echo $colegio["Colegio"]["activo"] ?>' onchange="activoActualizar(<?php echo $colegio["Colegio"]["id"] ?>)">
							<span class="lever"></span>
						</label>
					</div>
				</td>

				<td class="center ver_editar_acciones">
					<a class="hide-on-small-only" href="/colegios/editar/<?php echo $colegio["Colegio"]["id"] ?>">
						<span class="icon-editar dash-icon"></span>
					</a>
				</td>
			</tr>

		<?php endforeach ?>
	</tbody>
</table>
<br><br>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_cole").addClass("activado");

	function activoActualizar(colegio_id)
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/colegios/activo_actualizar',
	        success: function(response)
	        {
	            $('#poner_switch_'+colegio_id).children().replaceWith(response);
	        },
	        data: {
	        	colegio_id: colegio_id,
	        	activo: $('#colegio_'+colegio_id).val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
