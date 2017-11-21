

<?php if ($this->Session->read("Auth.User.tipo") == "CCM"): ?>
	<h2 class="red-text">
		Módulo solo para cajeros.
	</h2>
<?php endif ?>

<?php echo $this->Form->create("Cajas"); ?>

	<h5>Alumno</h5>
	<div class="row">
		<div class="input-field col s12 m6 l4">
			<input id="nombre" name="data[Hijo][nombre]" type="text">
			<label for="nombre">
				Nombre
				<label id="nombre-error" class="error validation_label" for="nombre"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="a_paterno" name="data[Hijo][a_paterno]" type="text">
			<label for="a_paterno">
				Apellido Paterno
				<label id="a_paterno-error" class="error validation_label" for="a_paterno"></label>
			</label>
		</div>

		<div class="input-field col s12 m6 l4">
			<input id="a_materno" name="data[Hijo][a_materno]" type="text">
			<label for="a_materno">
				Apellido Materno
				<label id="a_materno-error" class="error validation_label" for="a_materno"></label>
			</label>
		</div>
	</div>




	<div class="row margin_nada">
		<div class="col s12 m6 l4 offset-m6 offset-l8 red-text">
			Grado al que cursará
		</div>
	</div>
	<div class="row">
		<div class="input-field col s12 m6 l4">
			<select disabled="" id="select_colegios" name="data[CicloHijo][colegio_id]">
				<option selected value="<?php echo $colegio["id"] ?>"><?php echo $colegio["nombre"] ?></option>
			</select>
			<label id="select_colegios-error" class="validation_label" style="position:absolute!important;">*Requerido</label>
		</div>

		<div id="niveles_grados">

			<div class="input-field col s12 m6 l4">
				<select id="select_niveles" disabled="" name="data[CicloHijo][nivele_id]">
					<option value="nada" disabled selected>Nivel</option>
				</select>
			</div>

			<div class="input-field col s12 m6 l4" id="div_grados">
				<select id="select_grados" disabled="" name="data[CicloHijo][grado_id]">
					<option value="nada" disabled selected>Grado</option>
				</select>
			</div>

		</div>
	</div>

	<div class="row" id="mis_paquetes"></div>

	<div id="preloader" class="preloader-wrapper small active right hide">
		<div class="spinner-layer spinner-green-only">
			<div class="circle-clipper left">
				<div class="circle"></div>
			</div>
			<div class="gap-patch">
				<div class="circle"></div>
			</div>
			<div class="circle-clipper right">
				<div class="circle"></div>
			</div>
		</div>
	</div>


<?php echo $this->Form->end(); ?>


<?php $this->Html->script('colegios_niveles_grados', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

$("#menu_caja").addClass("activado");

	$(document).ready(function() {
		$('select').material_select();
	});

	$('#CajasAgregarCobrarForm').validate({
		rules: {
			'data[Hijo][nombre]': {
				required: true,
				alphanumeric: true
			},
			'data[Hijo][a_paterno]': {
				required: true,
				alphanumeric: true
			},
			'data[Hijo][a_materno]': {
				required: true,
				alphanumeric: true
			}
		}
	});

	$('#CajasAgregarCobrarForm').submit(function()
	{
		$("#preloader").removeClass("hide");
		$("#btn_comprar").addClass("hide");
	});

	$(document).on("change", "#select_grados, #nombre, #a_paterno, #a_materno", function()
	{
		$("#select_grados-error").css("display", "none");

		var correcto = true;

		if(!$('#select_grados').val()){
			$("#select_grados-error").css("display", "initial");
			correcto = false
		}

		if (!$("#CajasAgregarCobrarForm").valid())
			correcto = false;

		if (correcto)
			mostrarPaquetes();

	});

	var tabla_nombre = "CicloHijo";
	dropdownNiveles();

	function mostrarPaquetes()
	{
		$.ajax({
	        type:'POST',
	        cache: false,
	        url: '/paquetes/mostrar_paquetes',
	        success: function(response)
	        {
	            $('#mis_paquetes').replaceWith(response);
	        },
	        data: {
	        	nivele_id: $('#select_niveles').val(),
	        	grado_id: $('#select_grados').val()
	        }
	    });
	}

<?php $this->Html->scriptEnd(); ?>
