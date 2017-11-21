<div class="row center">
	<h5>
		<b>Artículos</b>
	</h5>
</div>



<div class="row right">
	<a href="/articulos/agregar" class="waves-effect waves-light btn">
		Agregar artículo
	</a>
	<?php if (!in_array($this->Session->read("Auth.User.tipo"), array("Director"))): ?>
		<a href="/articulos/subir_excel" class="waves-effect waves-light btn">
			Importar artículos
		</a>
		<a href="/articulos_precios/subir_excel" class="waves-effect waves-light btn">
			Importar precios
		</a>
	<?php endif ?>
</div>



<ul class="collapsible acordeon" data-collapsible="accordion">
	<?php foreach ($catalogo_familias as $keyCF => $familia): ?>
		<li>
			<div class="collapsible-header">
				<i class="material-icons flecha-acordeon">keyboard_arrow_down</i><h5><?php echo $familia["CatalogoFamilia"]["nombre"] ?></h5>
			</div>
			<div class="collapsible-body">
				<table class="bordered highlight">
					<thead class="top-tabla">
						<tr>
							<th>ID</th>
							<th>Descripción</th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($articulos as $keyA => $articulo): ?>

							<?php if ($familia["CatalogoFamilia"]["id"] == $articulo["Articulo"]["cat_fam_id"]): ?>
								<tr class="pointer">
									<td>
										<?php echo $articulo["Articulo"]["identificador"]; ?>
									</td>
									<td>
										<?php echo $articulo["Articulo"]["descripcion"]; ?>
									</td>
									<td class="center ver_editar_acciones">
										<a href="articulos/ver/<?php echo $articulo["Articulo"]["id"] ?>">
											<span class="icon-ver dash-icon"></span>
										</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<a class="hide-on-small-only" href="articulos/editar/<?php echo $articulo["Articulo"]["id"] ?>">
											<span class="icon-editar dash-icon"></span>
										</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<a class="hide-on-small-only" href="articulos/borrar/<?php echo $articulo["Articulo"]["id"] ?>">
											<span class="icon-borrar dash-icon"></span>
										</a>
									</td>
								</tr>
							<?php endif ?>

						<?php endforeach ?>
					</tbody>
				</table>
			</br>
			</div>
		</li>
	<?php endforeach ?>
</ul>


<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_arti").addClass("activado");

	$(document).ready(function() {
		$('.materialboxed').materialbox();
	});

<?php $this->Html->scriptEnd(); ?>
