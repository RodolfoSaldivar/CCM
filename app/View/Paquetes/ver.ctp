

<?php $this->set("breadcrumbs",
	'<a href="/paquetes" class="breadcrumb">Paquetes</a>
	<a class="breadcrumb">Ver paquete</a>'
) ?>



<?php if (!empty($paquete["Paquete"]["imagen"])): ?>

	<div class="col s12 l3 pull-l3">
		<img class="materialboxed imgpreview" src="data:image/png;base64,<?php echo $paquete["Paquete"]["imagen"] ?>" />
	</div>

<?php endif ?>



<div class="row">
	<div class="input-field col s12 m6 l3 push-l3" id="cambiar_id">
		<input readonly="" id="identificador" name="data[Paquete][identificador]" type="text" value="<?php echo $paquete["Paquete"]["identificador"] ?>">
		<label for="identificador">
			ID
			<label id="identificador-error" class="error validation_label" for="identificador"></label>
		</label>
	</div>

	<div class="input-field col s12 m6 l6 push-l3">
		<input readonly="" id="descripcion" name="data[Paquete][descripcion]" type="text" value="<?php echo $paquete["Paquete"]["descripcion"] ?>">
		<label for="descripcion">
			Descripción
			<label id="descripcion-error" class="error validation_label" for="descripcion"></label>
		</label>
	</div>
</div>



<div class="row">
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="colegio_id" name="data[Paquete][colegio_id]" type="text" value="<?php echo $paquete["Paquete"]["colegio_nombre"] ?>">
		<label for="colegio_id">
			Colegio
			<label id="colegio_id-error" class="error validation_label" for="colegio_id"></label>
		</label>
	</div>
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="nivele_id" name="data[Paquete][nivele_id]" type="text" value="<?php echo $paquete["Paquete"]["nivele_nombre"]  ?>">
		<label for="nivele_id">
			Nivel
			<label id="nivele_id-error" class="error validation_label" for="nivele_id"></label>
		</label>
	</div>
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="grado_id" name="data[Paquete][grado_id]" type="text" value="<?php echo $paquete["Paquete"]["grado_nombre"] ?>">
		<label for="grado_id">
			Grado
			<label id="grado_id-error" class="error validation_label" for="grado_id"></label>
		</label>
	</div>
</div>



<div class="row">
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="colegio_id" name="data[Paquete][colegio_id]" type="text" value="<?php echo "$ ".number_format($paquete["Precios"]["precio_publico"], 2) ?>">
		<label for="colegio_id">
			Precio Publico
			<label id="colegio_id-error" class="error validation_label" for="colegio_id"></label>
		</label>
	</div>
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="nivele_id" name="data[Paquete][nivele_id]" type="text" value="<?php echo "$ ".number_format($paquete["Precios"]["precio_venta"], 2)  ?>">
		<label for="nivele_id">
			Precio Colegio
			<label id="nivele_id-error" class="error validation_label" for="nivele_id"></label>
		</label>
	</div>
	<div class="input-field col s12 m6 l3 push-l3">
		<input readonly="" id="grado_id" name="data[Paquete][grado_id]" type="text" value="<?php echo "$ ".number_format($paquete["Precios"]["costo_ccm"], 2) ?>">
		<label for="grado_id">
			Costo CCM
			<label id="grado_id-error" class="error validation_label" for="grado_id"></label>
		</label>
	</div>
</div>

<br><br>
<h5><b>Artículos</b></h5>
<table class="bordered highlight">
	<thead class="top-tabla">
		<tr>
			<th>ID</th>
			<th>Descripción</th>
			<th>Familia</th>
			<th>Cantidad</th>
			<th>Precio Colegio<br>p/u</th>
			<th>PrecioPúblico<br>p/u</th>
			<th>PrecioPúblico<br>p/u SIN IVA</th>
			<th>IVA</th>
		</tr>
	</thead>

	<tbody id="poner_tr">

		<?php foreach ($art_paq as $key => $articulo): ?>
			<tr id="tr_1">
				<td>
					<?php echo $articulo["Articulo"]["identificador"] ?>
				</td>
				<td>
					<?php echo $articulo["Articulo"]["descripcion"] ?>
				</td>
				<td>
					<?php echo $articulo["Articulo"]["familia_nombre"] ?>
				</td>
				<td>
					<?php echo $articulo["ArticulosPaquete"]["cantidad"] ?>
				</td>
				<td>
					<?php echo "$ ".number_format($articulo["Articulo"]["precio_venta"], 2) ?>
				</td>
				<td>
					<?php echo "$ ".number_format($articulo["ArticulosPaquete"]["precio_publico"], 2) ?>
				</td>
				<td>
					<?php $publico_siva = $articulo["ArticulosPaquete"]["precio_publico"] /
							(1 + $articulo["Articulo"]["iva"] / 100) ?>
					<?php echo "$ ".number_format($publico_siva, 2) ?>
				</td>
				<td>
					<?php echo number_format($articulo["Articulo"]["iva"], 2)."%" ?>
				</td>
			</tr>
		<?php endforeach ?>

	</tbody>
</table>

<br><br><br><br><br>




<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_paqu").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
	});

<?php $this->Html->scriptEnd(); ?>
