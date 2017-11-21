<?php
	$filename = $nombre_archivo.".xls";

	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=$filename");

	$flag = false;
	foreach ($filas as $key => $valores)
	{
		if(!$flag)
		{
			echo implode("\t", array_keys($valores)) . "\n";
			$flag = true;
		}

		$vals = array_values($valores);

		foreach ($vals as $keyV => $value)
		{
			$quitar = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
			$poner = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N');

			echo str_replace($quitar, $poner, $value)."\t";
		}

		echo "\n";
	}
 ?>