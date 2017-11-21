<?php
App::uses('AppController', 'Controller');
/**
 * ArticulosPrecios Controller
 *
 */
class ArticulosPreciosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function descargar_excel($nombre = null)
    {
    	if (!in_array($nombre, array("plantillaCatalogoArticulosCostoPrecio")))
    		$this->redirect("/articulos/subir_excel");

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

		$this->layout = 'admin';

		if (!empty($this->request->data))
		{
			$data = $this->request->data['archivo'];

        	$name = explode('.',$data['name']);

        	if ($name[1] != "xls") 
        	{
        		$this->Session->setFlash('Solo archivos "xls".');
        		$this->redirect("/articulos/subir_excel");
        	}

        	if (substr($name[0], 0, 37) != "plantillaCatalogoArticulosCostoPrecio") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/articulos/subir_excel");
        	}

			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$preciosAgregados = 0;
			$preciosActualizados = 0;
			$errores_filas = array();

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador y el ciclo
				if (!empty($celdas[1]) && !empty($celdas[2]))
				{
					$articulo = $this->Articulo->find('first', array(
						'conditions' => array('identificador' => $celdas[1]),
						'fields' => array('id')
					));

					if ($articulo)
					{	
						$precio = $this->ArticulosPrecio->find('first', array(
							'conditions' => array(
								'ciclo' => $celdas[2],
								'articulo_id' => $articulo["Articulo"]["id"]
							),
							'fields' => array(
								'id', 'ciclo', 'costo_ccm', 'precio_venta', 'precio_publico_default', 'iva', 'articulo_id'
							)
						));

						//Significa que no existe y se dara de alta
						if (!$precio)
						{
							//Si tiene los campos obligatorios llenos
							if (!empty($celdas[1]) &&
								!empty($celdas[2]) &&
								!empty($celdas[3]) &&
								!empty($celdas[4]) &&
								!empty($celdas[5]))
							{
								$agregarFila = $this->agregarFila($celdas, "nuevo", $articulo["Articulo"]["id"]);
								if ($agregarFila == 1)
									$preciosAgregados++;
								else
									$errores_filas[$fila] = $agregarFila;
							}
							else
								$errores_filas[$fila] = "Necesita los campos obligatorios.";
						}
						else
						{	//Significa que ya existe y se actualizara
							$agregarFila = $this->agregarFila($celdas, $precio['ArticulosPrecio'], $articulo["Articulo"]["id"]);
							if ($agregarFila == 1)
								$preciosActualizados++;
							else
								$errores_filas[$fila] = $agregarFila;
						}
					}
					else
						$errores_filas[$fila] = "Ese artículo no existe.";
				}
				else
					$errores_filas[$fila] = "No hay identificador o ciclo.";
			}
			
			$errores_filas = base64_encode(json_encode($errores_filas));

			$fila = $fila - 2;
			$this->redirect("/dashboard/resultados/$fila/$preciosAgregados/$preciosActualizados/$errores_filas");
		}
	}


	function agregarFila($celdas, $precio, $articulo_id)
    {
		$accion = "agregar";

		if ($precio != "nuevo")
		{
			$datos_precio = $precio;
			$accion = "editar";
		}

		//Datos del precio
		if (!empty($celdas[2]))
			$datos_precio['ciclo'] = $celdas[2];
		
		if (!empty($celdas[3]))
			$datos_precio['costo_ccm'] = $celdas[3];
		
		if (!empty($celdas[4]))
			$datos_precio['precio_venta'] = $celdas[4];
		
		if (!empty($celdas[5]))
			$datos_precio['precio_publico'] = $celdas[5];
		
		if (!empty($celdas[6]))
			$datos_precio['iva'] = $celdas[6];

		$datos_precio['articulo_id'] = $articulo_id;

		$agregado = $this->ArticulosPrecio->guardarEnBDD($datos_precio, $accion);
		
		return $agregado;
    }

}
