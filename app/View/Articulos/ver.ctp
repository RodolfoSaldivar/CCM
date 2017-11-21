


<?php $this->set("breadcrumbs",
	'<a href="/articulos" class="breadcrumb">Artículos</a>
	<a class="breadcrumb">Ver artículo</a>'
) ?>



<?php if (!empty($articulo["imagen"])): ?>

	<div class="col s12 l3 pull-l3">
		<img class="materialboxed imgpreview" src="data:image/png;base64,<?php echo $articulo["imagen"] ?>" />
	</div>

<?php endif ?>



<div class="row">
	<div class="input-field col s12 l9 push-l3">
		<input readonly="" id="familia" name="data[Articulo][familia]" type="text" value="<?php echo $articulo["familia"] ?>">
		<label for="familia">
			Familia
			<label id="familia-error" class="error validation_label" for="familia"></label>
		</label>
	</div>
</div>



<div class="row">
	<div class="input-field col s12 m6 l3 push-l3" id="cambiar_id">
		<input readonly="" id="identificador" name="data[Articulo][identificador]" type="text" value="<?php echo $articulo["identificador"] ?>">
		<label for="identificador">
			ID
			<label id="identificador-error" class="error validation_label" for="identificador"></label>
		</label>
	</div>

	<div class="input-field col s12 m6 l6 push-l3">
		<input readonly="" id="descripcion" name="data[Articulo][descripcion]" type="text" value="<?php echo $articulo["descripcion"] ?>">
		<label for="descripcion">
			Descripción
			<label id="descripcion-error" class="error validation_label" for="descripcion"></label>
		</label>
	</div>
</div>



<div class="row">
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="colegio_id" name="data[Articulo][colegio_id]" type="text" value="<?php if (empty($articulo["colegio"])) echo "Todos"; else echo $articulo["colegio"] ?>">
		<label for="colegio_id">
			Colegio
			<label id="colegio_id-error" class="error validation_label" for="colegio_id"></label>
		</label>
	</div>
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="nivele_id" name="data[Articulo][nivele_id]" type="text" value="<?php if (empty($articulo["nivel"])) echo "Todos"; else echo $articulo["nivel"] ?>">
		<label for="nivele_id">
			Nivel
			<label id="nivele_id-error" class="error validation_label" for="nivele_id"></label>
		</label>
	</div>
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="grado_id" name="data[Articulo][grado_id]" type="text" value="<?php if (empty($articulo["grado"])) echo "Todos"; else echo $articulo["grado"] ?>">
		<label for="grado_id">
			Grado
			<label id="grado_id-error" class="error validation_label" for="grado_id"></label>
		</label>
	</div>
</div>


<div class="row margen-superior">
	<div class="col s12 l3">
	<h5><b>Historial de Precios</b></h5>
</div>
	<div class="col s12 l9">
		<table class="bordered highlight responsive-table">
			<thead class="top-tabla">
				<tr>
					<th>Ciclo</th>
					<th>Costo<br>CON IVA</th>
					<th>Precio Venta<br>CON IVA</th>
					<th>Precio Público<br>(Sugerencia)</th>
					<th>IVA</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($articulo["Precios"] as $key => $ciclo): ?>

					<tr>
						<td>
							<?php echo $ciclo["ArticulosPrecio"]["ciclo"] ?>
						</td>
						<td>
							$ <?php echo $ciclo["ArticulosPrecio"]["costo_ccm"] ?>
						</td>
						<td>
							$ <?php echo $ciclo["ArticulosPrecio"]["precio_venta"] ?>
						</td>
						<td>
							$ <?php echo $ciclo["ArticulosPrecio"]["precio_publico_default"] ?>
						</td>
						<td>
							<?php echo $ciclo["ArticulosPrecio"]["iva"] ?>%
						</td>
					</tr>

				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<br><br><br><br><br>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_arti").addClass("activado");


	$(document).ready(function() {
		$('select').material_select();
	});

<?php $this->Html->scriptEnd(); ?>
