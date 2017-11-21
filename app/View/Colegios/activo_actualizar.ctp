


<div class="switch">
	<label>
		No
		<input <?php if ($colegio["Colegio"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Colegio][<?php echo $colegio["Colegio"]["id"] ?>]' id='colegio_<?php echo $colegio["Colegio"]["id"] ?>' value='<?php echo $colegio["Colegio"]["activo"] ?>' onchange="activoActualizar(<?php echo $colegio["Colegio"]["id"] ?>)">
		<span class="lever"></span>
		Si
	</label>
</div>