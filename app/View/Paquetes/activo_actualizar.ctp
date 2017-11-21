

<div class="switch">
	<label>
		<input <?php if ($paquete["Paquete"]["estatus"] == 1) echo "checked"; ?> type="checkbox" name='data[Paquete][<?php echo $paquete["Paquete"]["id"] ?>]' id='paquete_<?php echo $paquete["Paquete"]["id"] ?>' value='<?php echo $paquete["Paquete"]["estatus"] ?>' onchange="activoActualizar(<?php echo $paquete["Paquete"]["id"] ?>)">
		<span class="lever"></span>
	</label>
</div>