<div class="row center">
	<h5>
		<b>Settings</b>
	</h5>
</div>


<div class="row center">
	<h5>Tienda Activa</h5>
	&nbsp;&nbsp;&nbsp;
	<div class="switch" id="switch_tienda_activo">
		<label>
			No
			<input <?php if ($activo) echo "checked" ?> type="checkbox" name='activo' id='estatus_tienda' value='<?php echo $activo ?>' onchange="activoActualizar()">
			<span class="lever"></span>
			Si
		</label>
	</div>
</div>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_sett").addClass("activado");

	function activoActualizar()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/settings/tienda_activo',
	        success: function(response)
	        {
	            $('#switch_tienda_activo').replaceWith(response);
	        },
	        data: {
	        	activo: $('#estatus_tienda').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
