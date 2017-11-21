


<div class="switch" id="switch_tienda_activo">
	<label>
		No
		<input <?php if ($activo) echo "checked" ?> type="checkbox" name='activo' id='estatus_tienda' value='<?php echo $activo ?>' onchange="activoActualizar()">
		<span class="lever"></span>
		Si
	</label>
</div>