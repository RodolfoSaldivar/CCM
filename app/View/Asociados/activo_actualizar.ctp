

<div class="switch">
	<label>
		No
		<input <?php if ($asociado["Asociado"]["activo"] == 1) echo "checked"; ?> type="checkbox" name='data[Asociado][<?php echo $asociado["Asociado"]["id"] ?>]' id='asociado_<?php echo $asociado["Asociado"]["id"] ?>' value='<?php echo $asociado["Asociado"]["activo"] ?>' onchange="activoActualizar(<?php echo $asociado["Asociado"]["id"] ?>)">
		<span class="lever"></span>
		Si
	</label>
</div>