


<div class="row">

	<div class="col s12">

		<table class="bordered highlight">
			<thead class="top-tabla">
				<tr>
					<th>DESCRIPCIÓN</th>
					<th class="center">CANTIDAD</th>
					<th class="decimal">IMPORTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				<?php $importe_total = 0 ?>
				<?php foreach ($paquetes as $key => $paquete): ?>
					<tr id="tr_<?php echo $paquete["Paquete"]["id"] ?>">
						<td>
							<?php echo $paquete["Paquete"]["descripcion"] ?>
						</td>
						<td id="cantidad_<?php echo $paquete["Paquete"]["id"] ?>" class="center">
							<?php echo $mismos[$paquete["Paquete"]["id"]] ?>
						</td>
						<td>
							<?php $importe_subtotal = $mismos[$paquete["Paquete"]["id"]] * $paquete["Precios"]["precio_publico"] ?>
							<?php $importe_total+= $importe_subtotal ?>
							<span id="importe_<?php echo $paquete["Paquete"]["id"] ?>" class="decimal">
								<?php echo number_format($importe_subtotal, 2) ?>
							</span>
						</td>
						<td>
							<a onclick='agregarCarrito("<?php echo $paquete["Paquete"]["id"] ?>", "<?php echo $paquete["Precios"]["precio_publico"] ?>")' class="waves-effect waves-light right">
								Aumentar
								<i class="material-icons boton-azul left">add</i>
							</a>
							<br><br>
							<a onclick='eliminarCarrito("<?php echo $paquete["Paquete"]["id"] ?>", "<?php echo $paquete["Precios"]["precio_publico"] ?>")' class="waves-effect waves-light right">
								Disminuir
								<i class="material-icons boton-azul left">remove</i>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
				<tr class="bottom-tabla">
					<td></td>
					<td class="center">
						<b>TOTAL:</b>
					</td>
					<td class="center">
						<b>
						<span id="importe_total" class="decimal">
							<?php echo number_format($importe_total, 2) ?>
						</span>
						<b>
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>


	<form action="/carrito/pagar" method="post" accept-charset="utf-8">

		<div class="col s12 margen-superior">
			<h5>Formas de Pago</h5>
			<?php if (!in_array($colegio_id, array(0))): ?>
				<p>
					<input class="with-gap" name="forma_pago" type="radio" id="linea" value="linea" />
					<label for="linea">
						Pago en línea, Crédito o Débito
						<img class="visa_master" src="/img/visaMaster.png">
						<br>
						3 meses sin intereses solo con
						<img class="citibanamex" src="/img/citibanamex.jpg">
						, Compra mínima $ 1,000.00 <br>
						<span class="red-text">
							Con el pago en línea ya no es necesario acudir al colegio a presentar el comprobante,
							<br>
							solo deberá presentarse con dicho pago el día de la entrega señalada por el colegio.
						</span>
					</label>
				</p>
			<?php endif ?>
			<p>
				<input class="with-gap" name="forma_pago" type="radio" id="impresion" value="impresion" />
				<label for="impresion">
					Imprimir comprobante para pago en Colegio o Banco
				</label>
			</p>
		</div>



		<div class="row">
			<div class="col s12 right">
				<button id="continuar" class="disabled btn waves-effect waves-light boton-tabla right" type="submit">
					Continuar
				</button>
			</div>
		</div>

	</form>

</div>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_sub_1").addClass("menu-pasado");
	$("#menu_sub_2").addClass("menu-pasado");
	$("#menu_sub_3").removeClass("href_desactivado");

	<?php if ($paquetes): ?>
		if ($("input[name='forma_pago']").is(':checked'))
			$("#continuar").removeClass("disabled");

		$(document).on("change", "input[name=forma_pago]", function()
		{
			if ($(this).val() == "linea")
				$("#continuar").text("Pagar");

			if ($(this).val() == "impresion")
				$("#continuar").text("Continuar");

			$("#continuar").removeClass("disabled");
		})
	<?php endif ?>


	function agregarCarrito(paquete_id, precio)
	{
		$.ajax({
			type:'POST',
			cache: false,
			url: '/carrito/agregar_al_carrito',
			success: function(response)
			{
				$('#carrito_de_compra').replaceWith(response);
				Materialize.toast('Paquete agregado.', 4000);

				var cantidad = regresarFloat($("#cantidad_"+paquete_id).text());
				cantidad++;
				var importe_nuevo = cantidad * precio;
				importe_nuevo = $.number(importe_nuevo, 2);
				$("#importe_"+paquete_id).text(""+importe_nuevo);
				$("#cantidad_"+paquete_id).text(cantidad);

				var importe_total = regresarFloat($("#importe_total").text());
				importe_total+= regresarFloat(precio);
				importe_total = $.number(importe_total, 2);
				$("#importe_total").text(importe_total);
			},
			data: {
				paquete_id: paquete_id
			}
		});
	}


	function eliminarCarrito(paquete_id, precio)
	{
		$.ajax({
			type:'POST',
			cache: false,
			url: '/carrito/remover_del_carrito',
			success: function(response)
			{
			$('#carrito_de_compra').replaceWith(response);
			Materialize.toast('Paquete eliminado.', 4000);

			var cantidad = regresarFloat($("#cantidad_"+paquete_id).text());

			cantidad--;
			var importe_nuevo = cantidad * precio;
			importe_nuevo = $.number(importe_nuevo, 2);
			$("#importe_"+paquete_id).text(importe_nuevo);
			$("#cantidad_"+paquete_id).text(cantidad);

			var importe_total = regresarFloat($("#importe_total").text());
			importe_total-= regresarFloat(precio);
			importe_total = $.number(importe_total, 2);
			$("#importe_total").text(importe_total);

			if (cantidad == 0)
				$("#tr_"+paquete_id).remove();
			},
			data: {
				paquete_id: paquete_id
			}
		});
	}

<?php $this->Html->scriptEnd(); ?>
