

<?php if ($colegio["Colegio"]["activo"]): ?>
	<ul class="botones-de-materiales row">
		<li>
			<a href="/carrito/ver" class="waves-effect waves-light btn right">
				Pagar
			</a>
		</li>
	</ul>
<?php endif ?>
	

<div class="row">
	<div class="center">
		<h5>
			Materiales para
			<?php echo $hijo["Hijo"]["nombre"]." ".
						$hijo["Hijo"]["a_paterno"]." ".
						$hijo["Hijo"]["a_materno"]
			?>
			<br>
			<?php echo $hijo["Hijo"]["colegio"]	?>
			<br>
			<?php echo $hijo["Hijo"]["nivel"]." ".
						$hijo["Hijo"]["grado"] ; ?>
		</h5>

	</div>
</div>


<?php if (!empty($colegio["Colegio"]["logo"])): ?>

	<!--div class="row">
		<img width="200" class="materialboxed" src="data:image/png;base64,<?php echo $colegio["Colegio"]["logo"] ?>" />
	</div-->

<?php endif ?>



<div class="row">
	<?php if ($colegio["Colegio"]["activo"]): ?>
		<?php foreach ($paquetes as $key => $paquete): ?>

			<div class="col s12 m6 l4 center paquete">

				<div class="imagen-paquete">
				<?php if (!empty($paquete["Paquete"]["imagen"])): ?>
					<img width="200" class="responsive-img" src="data:image/png;base64,<?php echo $paquete["Paquete"]["imagen"] ?>" />
				<?php else: ?>
					<img width="200" class="responsive-img" src="/img/paquete_default.png">
				<?php endif ?>
			</div>

				<div class="descripcion-paquete"><?php echo $paquete["Paquete"]["descripcion"] ?></div>

				<div class="precio-paquete">$ <?php echo number_format($paquete["Precios"]["precio_publico"], 2) ?></div>

				<a id="btn_<?php echo $paquete["Paquete"]["id"] ?>" onclick='agregarCarrito("<?php echo $paquete["Paquete"]["id"] ?>")' class="btn waves-effect waves-light boton-paquete">
					AGREGAR AL CARRITO
				</a>
			</div>

		<?php endforeach ?>
	<?php else: ?>
		<div class="row">
			<div class="col s12 center">
				<h4>
					<?php echo $colegio["Colegio"]["mensaje"] ?>
				</h4>
			</div>
		</div>
	<?php endif ?>
		
</div>


<?php if ($colegio["Colegio"]["activo"]): ?>
	<ul class="botones-de-materiales">
		<li>
			<a href="/carrito/ver" class="waves-effect waves-light btn right">
				Pagar
			</a>
		</li>
	</ul>
<?php endif ?>
	

<br><br><br>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_sub_1").addClass("menu-pasado");
	$("#menu_sub_2").removeClass("href_desactivado");

	$(document).ready(function() {
		$('select').material_select();
		$('.materialboxed').materialbox();
	});

	function agregarCarrito(paquete_id)
	{
		$("#btn_"+paquete_id).addClass("disabled");

		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/carrito/agregar_al_carrito',
	        success: function(response)
	        {
	            $('#carrito_de_compra').replaceWith(response);
	            Materialize.toast('Paquete agregado.', 4000);
	        },
	        data: {
	        	paquete_id: paquete_id
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
