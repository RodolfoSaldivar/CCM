


<table class="bordered ocultame">
	<thead class="top-tabla">
		<tr>
			<td>ID</td>
			<td>Colegio</td>
			<td>PDF</td>
			<td>Facturar</td>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($ordenes as $key => $orden): ?>
			
			<tr>
				<td>
					<?php echo $orden["Colegio"]["identificador"]; ?>
				</td>
				<td>
					<?php echo $orden["Colegio"]["nombre"]; ?>
				</td>
				<td>
					<a onclick="quitarDisabled(<?php echo $key ?>)" target="_blank" href="/orden_compras/hacer_pdf/<?php echo $orden["OrdenCompra"]["id"]; ?>">
						<span class="icon-comprobante"></span>
					</a>
				</td>
				<td>
					<?php if ($orden["OrdenCompra"]["fecha_facturado"]): ?>
						<?php echo $orden["OrdenCompra"]["fecha_facturado"] ?>
					<?php else: ?>
						<a id="facturar_<?php echo $key ?>" onclick="facturar(<?php echo $orden["OrdenCompra"]["id"]; ?>)" class="waves-effect waves-light btn disabled">Facturar</a>
					<?php endif ?>
				</td>
			</tr>

		<?php endforeach ?>
	</tbody>
</table>


<div id="preloader" class="preloader-wrapper big active hide">
    <div class="spinner-layer spinner-blue-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div><div class="gap-patch">
        <div class="circle"></div>
      </div><div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$(document).ready(function() {
		$('select').material_select();
		$("#menu_cierre").addClass("activado");
	});


	$(document).on("click", "#select_colegios", function()
	{
		$("#select_colegios-error").css("display", "none");
		$(".btn_actualizar").removeClass("disabled");
	});


	function quitarDisabled(key)
	{
		$("#facturar_"+key).removeClass("disabled");
	}


	function facturar(orden_id)
	{
		Materialize.toast(
			'<div>¿Facturar? Una vez facturado no se podran hacer modificaciones.&nbsp;&nbsp;</div><a href="/orden_compras/facturar/'+orden_id+'" class="waves-effect waves-light btn ocultame" onclick="ocultar()"><i class="material-icons white-text">check</i></a>',
			10000
		)
	}

	function ocultar()
	{
		$(".ocultame").addClass("hide");
		$("#preloader").removeClass("hide");
	}

<?php $this->Html->scriptEnd(); ?>