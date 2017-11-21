


<?php

	require_once "php/dompdf/autoload.inc.php";

	use Dompdf\Dompdf;

	$dompdf = new Dompdf();
	 
	$dompdf->load_html($codigo_html);
	$dompdf->render();
	//$dompdf->stream("Pedido_$pedido_id.pdf", array("Attachment" => 0));

	file_put_contents("pdf/Pedido_$pedido_id.pdf", $dompdf->output());

	header("Location: /pedidos/finalizar/$pedido_id");
	exit();
	
 ?>