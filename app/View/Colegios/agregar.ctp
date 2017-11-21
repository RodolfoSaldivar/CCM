

<?php $this->set("breadcrumbs",
	'<a href="/colegios" class="breadcrumb">Colegios</a>
	<a class="breadcrumb">Agregar colegio</a>'
) ?>



<?php echo $this->Form->create(array('type' => 'file')); ?>


	<div class="row">
		<div class="instrucciones col s12 l3">Selecciona un director o <a href="/asociados/agregar_director">agrega uno nuevo.</a></div>
		<div class="input-field col s12 l9">
			<select id="select_director" name="data[Colegio][asociado_id]">
				<option value="" disabled selected>Director</option>
				<?php foreach ($asociados as $key => $director): ?>

					<option value="<?php echo $director["Asociado"]["id"] ?>"><?php echo
							$director["Asociado"]["a_paterno"]." ".
							$director["Asociado"]["a_materno"].", ".
							$director["Asociado"]["nombre"]
						?></option>

				<?php endforeach ?>
			</select>
			<label id="select_director-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>
	</div>



	<div class="row">
		<div class="instrucciones col s12 l3">Sube un logo en .jpg o .png.</div>
		<div class="col s12 l9">
			<div class="file-field input-field">
				<div class="btn">
					<span>Subir logo</span>
					<input type="file" name="data[Logo]">
				</div>
				<div class="file-path-wrapper">
					<input class="file-path" type="text">
				</div>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="instrucciones col s12 l3">Elige una ID para identificar al colegio, el nombre, un mensaje de bienvenida, y los niveles de estudios que abarca.</div>
		<div class="input-field col s12 m6 l3" id="cambiar_id">
			<input id="identificador" name="data[Colegio][identificador]" type="text">
			<label for="identificador">
				ID
				<label id="identificador-error" class="error validation_label" for="identificador"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l6">
			<input id="nombre" name="data[Colegio][nombre]" type="text">
			<label for="nombre">
				Nombre
				<label id="nombre-error" class="error validation_label" for="nombre"></label>
			</label>
		</div>

		<div class="input-field col s12 l9">
			<input id="mensaje" name="data[Colegio][mensaje]" type="text">
			<label for="mensaje">
				Mensaje
				<label id="mensaje-error" class="error validation_label" for="mensaje"></label>
			</label>
		</div>
	</div>



	<?php foreach ($catalogo_niveles as $keyN => $nivel): ?>

		<div class="row">
			<div class="col s12 l3">
			<h5>
				<?php echo $nivel["CatalogoNivele"]["nombre"] ?>
			</h5>
		</div>
			<div class="col s12 l9 niveles-estudios">

				<?php foreach ($niv_gra as $keyG => $grado): ?>

					<?php if ($grado["CatalogoGrado"]["cat_niv_id"] == $nivel["CatalogoNivele"]["id"]): ?>

						<input type="checkbox" id="check_<?php echo $keyG ?>" name="data[Niveles][<?php echo $nivel["CatalogoNivele"]["id"] ?>][<?php echo $grado["CatalogoGrado"]["id"] ?>]" value="<?php echo $grado["CatalogoGrado"]["id"] ?>" />
						<label for="check_<?php echo $keyG ?>"><?php echo $grado["CatalogoGrado"]["nombre"] ?></label>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<?php endif ?>

				<?php endforeach ?>

			</div>
		</div>


	<?php endforeach ?>



	<button class="btn waves-effect waves-light right btn-bottom" type="submit" name="action">
		Agregar colegio
	</button>

<?php echo $this->Form->end(); ?>



<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_cole").addClass("activado");


	$(document).ready(function() {
		$('select').material_select();
	});


	$('#ColegioAgregarForm').validate({
		rules: {
			'data[Colegio][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][identificador]': {
				required: true,
				alphanumeric: true
			},
			'data[Colegio][mensaje]': {
				required: true,
				alphanumeric: true
			}
		}
	});

	$('#ColegioAgregarForm').submit(function(event)
	{
		var correcto = true;

		if(!$('#select_director').val()){
			$("#select_director-error").css("display", "initial");
			correcto = false;
		}

		if (!correcto)
			event.preventDefault();
	});

	$(document).on("change", "#select_director", function()
	{
		$("#select_director-error").css("display", "none");
	});




	$('#identificador').donetyping(function()
	{ revisarIdentificador(); });

	function revisarIdentificador()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/colegios/revisar_identificador',
	        success: function(response)
	        {
	            $('#cambiar_id').replaceWith(response);

	            ponerFocus($("#identificador"));

	            $('#identificador').donetyping(function()
				{ revisarIdentificador(); });
	        },
	        data: {
	        	nuevo_id: $('#identificador').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
