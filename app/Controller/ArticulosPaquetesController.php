<?php
App::uses('AppController', 'Controller');
/**
 * ArticulosPaquetes Controller
 *
 */
class ArticulosPaquetesController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function descargar_excel($nombre = null)
    {
    	if (!in_array($nombre, array("plantillaPaquetesDetalle")))
    		$this->redirect("/articulos_paquetes/subir_excel");

        $filename = $nombre.".xls";
        $name = explode('.',$filename);
        $this->viewClass = 'Media';

        $params = array(
            'id'        => $filename,
            'name'      => $name[0],
            'download'  => 1,
            'extension' => $name[1],
            'path'      => APP . 'Plantillas' . DS
        );

        $this->set($params);
    }
	

//=========================================================================


	public function subir_excel()
	{
		$this->loadModel("Articulo");
		$this->loadModel("Paquete");

		$this->layout = 'admin';

		if (!empty($this->request->data))
		{
			$data = $this->request->data['archivo'];

        	$name = explode('.',$data['name']);

        	if ($name[1] != "xls") 
        	{
        		$this->Session->setFlash('Solo archivos "xls".');
        		$this->redirect("/articulos_paquetes/subir_excel");
        	}

        	if (substr($name[0], 0, 24) != "plantillaPaquetesDetalle") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/articulos_paquetes/subir_excel");
        	}

			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$artPaqAgregados = 0;
			$artPaqActualizados = 0;
			$errores_filas = array();

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador de articulo y paquete
				if (!empty($celdas[1]) && !empty($celdas[2]))
				{
					$paquete = $this->Paquete->find('first', array(
						'conditions' => array('identificador' => $celdas[1]),
						'fields' => array('id')
					));
					if ($paquete)
						$paquete_id = $paquete["Paquete"]["id"];
					else
						$paquete_id = 0;
					
					$articulo = $this->Articulo->find('first', array(
						'conditions' => array('identificador' => $celdas[2]),
						'fields' => array('id')
					));
					if ($articulo)
						$articulo_id = $articulo["Articulo"]["id"];
					else
						$articulo_id = 0;

					if ($articulo && $paquete)
					{	
						$art_paq = $this->ArticulosPaquete->find('first', array(
							'conditions' => array(
								'paquete_id' => $paquete_id,
								'articulo_id' => $articulo_id
							),
							'fields' => array(
								'id', 'cantidad', 'precio_publico', 'articulo_id', 'paquete_id'
							)
						));

						//Significa que no existe y se dara de alta
						if (empty($art_paq['ArticulosPaquete']['id']))
						{
							//Si tiene los campos obligatorios llenos
							if (!empty($celdas[1]) &&
								!empty($celdas[2]) &&
								!empty($celdas[3]) &&
								!empty($celdas[4]))
							{
								$agregarFila = $this->agregarFila($celdas, "nuevo", $articulo_id, $paquete_id);
								if ($agregarFila == 1)
									$artPaqAgregados++;
								else
									$errores_filas[$fila] = $agregarFila;
							}
							else
								$errores_filas[$fila] = "Necesita los campos obligatorios.";
						}
						else
						{	//Significa que ya existe y se actualizara

							//Si tiene los campos obligatorios llenos
							if (!empty($celdas[1]) &&
								!empty($celdas[2]) &&
								!empty($celdas[3]) &&
								!empty($celdas[4]))
							{
								$agregarFila = $this->agregarFila($celdas, $art_paq['ArticulosPaquete'], $articulo_id, $paquete_id);
								if ($agregarFila == 1)
									$artPaqActualizados++;
								else
									$errores_filas[$fila] = $agregarFila;
							}
							else
								$errores_filas[$fila] = "Necesita los campos obligatorios.";
						}
					}
					else
						$errores_filas[$fila] = "El artículo o el paquete no existe.";
				}
				else
					$errores_filas[$fila] = "No hay identificador de artículo o paquete.";
			}
			
			$errores_filas = base64_encode(json_encode($errores_filas));

			$fila = $fila - 2;
			$this->redirect("/dashboard/resultados/$fila/$artPaqAgregados/$artPaqActualizados/$errores_filas");
		}
	}


	function agregarFila($celdas, $art_paq, $articulo_id, $paquete_id)
    {
		$accion = "agregar";

		if ($art_paq != "nuevo")
		{
			$datos_art_paq = $art_paq;
			$accion = "editar";
		}

		//Datos del precio		
		if (!empty($celdas[3]))
			$datos_art_paq['cantidad'] = $celdas[3];
		
		if (!empty($celdas[4]))
			$datos_art_paq['precio_publico'] = $celdas[4];

		$datos_art_paq['articulo_id'] = $articulo_id;
		$datos_art_paq['paquete_id'] = $paquete_id;

		$agregado = $this->ArticulosPaquete->guardarEnBDD($datos_art_paq, $accion);	
		
		return $agregado;
    }

}
