

<div class="row center">
	<h5>
		<b>Hijos registrados en el ciclo escolar <?php echo $ciclo_actual ?></b>
	</h5>
</div>


<table class="bordered highlight">
	<thead class="top-tabla">
		<tr>
			<th>NOMBRE</th>
			<th>COLEGIO</th>
			<th>NIVEL</th>
			<th>GRADO</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>


	<tbody>
		<?php foreach ($hijos as $key => $hijo): ?>

			<tr>
				<td>
					<?php echo
						$hijo["Hijo"]["nombre"]." ".
						$hijo["Hijo"]["a_paterno"]." ".
						$hijo["Hijo"]["a_materno"]
					?>
				</td>
				<td>
					<?php echo $hijo["Hijo"]["colegio"] ?>
				</td>
				<td>
					<?php echo $hijo["Hijo"]["nivel"] ?>
				</td>
				<td>
					<?php echo $hijo["Hijo"]["grado"] ?>
				</td>
				<td>
					<a href="/paquetes/seleccionar/<?php echo $hijo["Hijo"]["id"] ?>" class="waves-effect waves-light btn">
						COMPRAR
					</a>
				</td>
				<td class="center tabla_acciones">
					<a href="/hijos/editar/<?php echo $hijo["Hijo"]["id"] ?>">
						<span class="icon-editar"></span>
					</a>
				</td>
				<td>
					<a onclick="eliminar(<?php echo $hijo["CicloHijo"]["id"] ?>)">
						<span class="icon-borrar"></span>
					</a>
				</td>
			</tr>

		<?php endforeach ?>
		<tr class="bottom-tabla">
			<td class="bottom-tabla">
			<a href="/hijos/agregar" class="">+ Agregar Hijo</a>
		</td>
	</tr>
	</tbody>
</table>

<br><br>
<ul class="mensaje-hijos">
<?php foreach ($colegios as $key => $colegio): ?>

	<li>
		<span class="hijos-colegio">
			<?php echo $colegio["Colegio"]["nombre"] ?>:
		</span>
		<span class="hijos-mensaje">
			<?php echo $colegio["Colegio"]["mensaje"] ?>
		</span>
	</li>

<?php endforeach ?>
</ul>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_sub_1").removeClass("href_desactivado");
	$("#menu_back").addClass("hide");

	function eliminar(ciclo_id)
	{
		Materialize.toast(
			'<div>¿Eliminar hijo?&nbsp;&nbsp;</div><a href="/hijos/eliminar/'+ciclo_id+'" class="waves-effect waves-light btn">ELIMINAR</a>',
			6000
		)
	}

<?php $this->Html->scriptEnd(); ?>
