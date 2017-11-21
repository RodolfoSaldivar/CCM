


<?php

	require_once "php/dompdf/autoload.inc.php";

	use Dompdf\Dompdf;

	$dompdf = new Dompdf();
	 
	$dompdf->load_html($codigo_html);
	$dompdf->render();
	$dompdf->stream("Pedido_$orden_id.pdf", array("Attachment" => 0));
	exit();
	
 ?>