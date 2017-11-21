


<div class="row" id="mis_paquetes">

	<br><br>
	<?php foreach ($paquetes as $key => $paquete): ?>

		<div class="col s12 m6 l4 center paquete">

			<div class="imagen-paquete">
				<?php if (!empty($paquete["Paquete"]["imagen"])): ?>
					<img width="200" class="responsive-img" src="data:image/png;base64,<?php echo $paquete["Paquete"]["imagen"] ?>" />
				<?php else: ?>
					<img width="200" class="responsive-img" src="/img/paquete_default.png">
				<?php endif ?>
			</div>

			<div class="descripcion-paquete" style="height: auto;">
				<?php echo $paquete["Paquete"]["descripcion"] ?>
			</div>

			<div class="precio-paquete">
				$ <?php echo number_format($paquete["Precios"]["precio_publico"], 2) ?>
			</div>

			<input name="data[Paquete][<?php echo $paquete["Paquete"]["id"] ?>]" class="check_grande" type="checkbox" id="<?php echo $paquete["Paquete"]["id"] ?>" />
			<label for="<?php echo $paquete["Paquete"]["id"] ?>" style="padding-left: 18px;" ></label>

		</div>
	<?php endforeach ?>

	<div class="col s12">
		<button id="btn_comprar" class="btn waves-effect waves-light right" type="submit" name="action">
			Comprar
		</button>
	</div>

</div>