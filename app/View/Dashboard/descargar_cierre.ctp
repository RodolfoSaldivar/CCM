<?php
	$filename = $nombre_archivo.".xls";

	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=$filename");

	$quitar = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
	$poner = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N');
	$flag = 0;

	foreach ($filas as $key => $value)
	{
		if (!$flag)
		{	
			echo "Informacion del Articulo\t\t\t\t\tPedido Original\t\t\t\t\t\t\t\tVentas Totales\t\t\t\t\t\tVentas Facturadas\t\t\t\t\t\tFacturacion Pendiente\n";
			echo "Clave\tArticulo\tIVA\tFamilia\t\tCant\tDev\tInv\tP.U.\tImporte\tIVA\tTotal\t\tCant\tP.U.\tImporte\tIVA\tTotal\t\tCant\tP.U.\tImporte\tIVA\tTotal\t\tCant\tP.U.\tAjuste\tP.U. Ajustado\tImporte Ajustado\tImporte\tIVA\tTotal\n";
			$flag = 1;
		}

		$valores = array_values($value);

		if ($valores[24])
			$pu = number_format((floatval(str_replace(',', '', $valores[12])) - floatval(str_replace(',', '', $valores[22]))) / floatval(str_replace(',', '', $valores[24])), 2);
		else
			$pu = 0;

		echo $valores[2]."\t".
			str_replace($quitar, $poner, $valores[3])."\t".
			$valores[4]."\t".
			$valores[5]."\t\t".
			$valores[6]."\t".
			$valores[7]."\t".
			$valores[8]."\t".
			$valores[9]."\t".
			$valores[10]."\t".
			$valores[11]."\t".
			$valores[12]."\t\t".
			$valores[13]."\t".
			$valores[14]."\t".
			$valores[15]."\t".
			$valores[16]."\t".
			$valores[17]."\t\t".
			$valores[18]."\t".
			$valores[19]."\t".
			$valores[20]."\t".
			$valores[21]."\t".
			$valores[22]."\t\t".
			$valores[24]."\t".
			$pu."\t".
			$valores[25]."\t".
			$valores[26]."\t".
			$valores[27]."\t".
			$valores[28]."\t".
			$valores[29]."\t".
			$valores[30]."\n";
	}
 ?>