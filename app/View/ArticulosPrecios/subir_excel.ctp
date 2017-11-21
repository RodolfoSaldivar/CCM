


<?php $this->set("breadcrumbs",
	'<a href="/articulos" class="breadcrumb">Artículos</a>
	<a class="breadcrumb">Importar precios</a>'
) ?>



<?php echo $this->Form->create(array('type' => 'file')); ?>

	<div class="bg_blanco">
		<div class="contenedor">

			<div class="row">
				<h5>
					Instrucciones para importar varios precios con plantilla de Excel:
				</h5>
			</div>

			<div class="row margin_nada">
				<div style="padding:10px 0 0 20px; font-size:20px;">
					<ol>
						<li>Descargar el archivo Excel.</li>
							<div style="padding: 0px 0px 30px 0px;" class="input-field">
								<a href="/articulos_precios/descargar_excel/plantillaCatalogoArticulosCostoPrecio" class="waves-effect waves-light btn">Descargar</a>
							</br>
							</div>
					<li>Llenar todos los campos</br>(el archivo cuenta con comentarios de ayuda).</li>
				</br>
					<li>Guardar el archivo llenado y seleccionarlo a continuación.</li>
					<div class="row">

						<div class="col s9 m9">
							<div class="file-field input-field">

								<div style="height: 36px; line-height: 36px; padding: 0 2rem;" class="btn">
									<span>Seleccionar archivo</span>
									<input name="data[archivo]" type="file">
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate" type="text" style="border-bottom: 1px solid #9e9e9e;">
									<label id="data[archivo]-error" class="error validation_label validation_image" for="data[archivo]"></label>
								</div>
							</div>
						</div>

						<div class="row padding_bottom margin_nada hide" id="aparece_loading">
							<div class="col s3 offset-s9 center">
								<div class="preloader-wrapper big active">
									<div class="spinner-layer azul_5">
										<div class="circle-clipper left"><div class="circle"></div></div>
										<div class="gap-patch"><div class="circle"></div></div>
										<div class="circle-clipper right"><div class="circle"></div></div>
									</div>
								</div>
								<br>
								<span class="enviando">
									Guardando artículos
								</span>
							</div>
						</div>

					</div>

					<li>Subir el archivo.</li>
					<div style="padding: 10px 0px 30px 0px;" class="row" id="desaparece_loading">
						<div class="col m12">
							<button class="btn waves-effect waves-light" type="submit" name="action">
								Subir el archivo
							</button>
						</div>
					</div>
				</ol>
				</div>
			</div>

		</div>
	</div>

<?php echo $this->Form->end(); ?>




<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_arti").addClass("activado");

	$.validator.messages.required = '*Requerido';

	$('#ArticulosPrecioSubirExcelForm').validate({
		rules: {
			'data[archivo]': {
				required: true
			}
		}
	});

	$('#ArticulosPrecioSubirExcelForm').submit(function(event)
	{
		if ($('#ArticulosPrecioSubirExcelForm').valid())
		{
			$("#desaparece_loading").addClass("hide");
			$("#aparece_loading").removeClass("hide");
		}
	});

<?php $this->Html->scriptEnd(); ?>
