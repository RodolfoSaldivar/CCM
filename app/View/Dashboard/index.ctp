<div class="row center">
	<h5>
		<b>Dashboard</b>
	</h5>
</div>

<table class="bordered highlight">
	<thead class="top-tabla">
		<tr>
			<th>Colegios</th>
			<?php foreach ($catalogo_niveles as $key => $catalogo): ?>
				<th class="center"><?php echo $catalogo["CatalogoNivele"]["nombre"] ?></th>
			<?php endforeach ?>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($colegios as $keyC => $colegio): ?>

			<tr>
				<td>
					<?php echo $colegio["Colegio"]["nombre"]; ?>
				</td>
				<?php foreach ($catalogo_niveles as $keyC => $catalogo): ?>
					<?php if (@in_array($catalogo["CatalogoNivele"]["id"], $colegio["Niveles"])): ?>
						<td class="center">
							<i class="material-icons">check</i>
						</td>
					<?php else: ?>
						<td></td>
					<?php endif ?>
				<?php endforeach ?>
			</tr>

		<?php endforeach ?>
	</tbody>
</table>

<br><br>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_dash").addClass("activado");

<?php $this->Html->scriptEnd(); ?>