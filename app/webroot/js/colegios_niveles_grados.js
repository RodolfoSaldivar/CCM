



$(document).on("change", "#select_colegios", function(){
	$("#select_colegios-error").css("display", "none");
	dropdownNiveles();
});

function dropdownNiveles(nivele_id = 0, grado_id = 0)
{
	$.ajax({
        type:'POST',
        cache: false,
        url: '/niveles/dropdown_niveles',
        success: function(response)
        {
            $('#niveles_grados').replaceWith(response);

            if (nivele_id)
                dropdownGrados(grado_id);

			$('select').material_select();
        },
        data: {
        	colegio: $('#select_colegios').val(),
        	tabla: tabla_nombre,
            nivele_id: nivele_id
        }
    });
}


$(document).on("change", "#select_niveles", function(){
	$("#select_niveles-error").css("display", "none");
	dropdownGrados();
});

function dropdownGrados(grado_id = 0)
{
	$.ajax({
        type:'POST',
        cache: false,
        url: '/grados/dropdown_grados',
        success: function(response)
        {
            $('#div_grados').replaceWith(response);

			$('select').material_select();
        },
        data: {
        	nivel: $('#select_niveles').val(),
        	tabla: tabla_nombre,
            grado_id: grado_id
        }
    });
}