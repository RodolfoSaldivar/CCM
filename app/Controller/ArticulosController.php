<?php
App::uses('AppController', 'Controller');
/**
 * Articulos Controller
 *
 */
class ArticulosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Cajeros
		if (isset($user['tipo']) && in_array($user['tipo'], array("Director")))
		{
			return true;
		}

	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("CatalogoFamilia");

		$catalogo_familias = $this->CatalogoFamilia->todasFamilias();

		$articulos = $this->Articulo->todosArticulos(array());
		
		$this->set("catalogo_familias", $catalogo_familias);
		$this->set("articulos", $articulos);
	}
	

//=========================================================================


	public function ver($id = null)
	{
		$this->layout = 'admin';
		$this->loadModel("CatalogoFamilia");
		$this->loadModel("ArticulosPrecio");
		$this->loadModel("Colegio");
		$this->loadModel("Nivele");
		$this->loadModel("Grado");

		$condiciones = array('Articulo.id' => $id);
		$articulo = $this->Articulo->todosArticulos($condiciones);

		$familia = $this->CatalogoFamilia->familiaEspecifica($articulo[0]["Articulo"]["cat_fam_id"]);
		$articulo[0]["Articulo"]["familia"] = $familia["CatalogoFamilia"]["nombre"];

		$condiciones = array('articulo_id' => $articulo[0]["Articulo"]["id"]);
		$precios = $this->ArticulosPrecio->todosArticulosPrecios($condiciones);
		$articulo[0]["Articulo"]["Precios"] = $precios;

		$condiciones = array('id' => $articulo[0]["Articulo"]["colegio_id"]);
		$colegio = $this->Colegio->todosColegios($condiciones);
		@$articulo[0]["Articulo"]["colegio"] = $colegio[0]["Colegio"]["nombre"];

		$nivel = $this->Nivele->nivelEspecifico($articulo[0]["Articulo"]["nivele_id"]);
		@$articulo[0]["Articulo"]["nivel"] = $nivel["CatalogoNivele"]["nombre"];

		$grao = $this->Grado->gradoEspecifico($articulo[0]["Articulo"]["grado_id"]);
		@$articulo[0]["Articulo"]["grado"] = $grao["CatalogoGrado"]["nombre"];

		$articulo = $articulo[0]["Articulo"];

		$this->set("articulo", $articulo);
	}
	

//=========================================================================


	public function agregar()
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");
		$this->loadModel("CatalogoFamilia");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$image_data = @file_get_contents($data["Imagen"]["tmp_name"]);
			$imagen = base64_encode($image_data);
			$data["Articulo"]["imagen"] = $imagen;

			if (strlen($imagen) > 100000)
			{
				$this->Session->setFlash('Imagen demasiado pesada, intentar con otra.');
			}
			else
			{
				$guardado = $this->Articulo->guardarEnBDD($data, "agregar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Artículo agregado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
		}

		$colegios = $this->Colegio->todosColegios(array());
		$catalogo_familias = $this->CatalogoFamilia->todasFamilias();

		$this->set("colegios", $colegios);
		$this->set("catalogo_familias", $catalogo_familias);
		$this->set("cicloActual", $this->cicloActual());
	}
	

//=========================================================================


	public function editar($id = null)
	{
		$this->layout = 'admin';
		$this->loadModel("CatalogoFamilia");
		$this->loadModel("ArticulosPrecio");
		$this->loadModel("Colegio");
		$this->loadModel("Nivele");
		$this->loadModel("Grado");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;
			if ($data["Articulo"]["colegio_id"] == "nada")
				$data["Articulo"]["colegio_id"] = 0;

			if (!empty($data["Imagen"]["tmp_name"]))
			{
				$image_data = @file_get_contents($data["Imagen"]["tmp_name"]);
				$imagen = base64_encode($image_data);
				$data["Articulo"]["imagen"] = $imagen;
			}
			
			if (strlen($data["Articulo"]["imagen"]) > 100000)
			{
				$this->Session->setFlash('Imagen demasiado pesada, intentar con otra.');
			}
			else
			{
				$guardado = $this->Articulo->guardarEnBDD($data, "editar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Artículo guardado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
		}

		$condiciones = array('Articulo.id' => $id);
		$articulo = $this->Articulo->todosArticulos($condiciones);

		$familia = $this->CatalogoFamilia->familiaEspecifica($articulo[0]["Articulo"]["cat_fam_id"]);
		$articulo[0]["Articulo"]["familia"] = $familia["CatalogoFamilia"]["nombre"];

		$condiciones = array('articulo_id' => $articulo[0]["Articulo"]["id"]);
		$precios = $this->ArticulosPrecio->todosArticulosPrecios($condiciones);
		$articulo[0]["Articulo"]["Precios"] = $precios;

		$condiciones = array('articulo_id' => $articulo[0]["Articulo"]["id"], 'ciclo' => $this->cicloActual());
		$precios = $this->ArticulosPrecio->todosArticulosPrecios($condiciones);
		@$articulo[0]["Articulo"]["Actual"] = $precios[0]["ArticulosPrecio"];

		$condiciones = array('id' => $articulo[0]["Articulo"]["colegio_id"]);
		$colegio = $this->Colegio->todosColegios($condiciones);
		@$articulo[0]["Articulo"]["colegio"] = $colegio[0]["Colegio"]["nombre"];
		$colegio_id = $articulo[0]["Articulo"]["colegio_id"];

		$nivel = $this->Nivele->nivelEspecifico($articulo[0]["Articulo"]["nivele_id"]);
		@$articulo[0]["Articulo"]["nivel"] = $nivel["CatalogoNivele"]["nombre"];
		$nivele_id = $articulo[0]["Articulo"]["nivele_id"];

		$grao = $this->Grado->gradoEspecifico($articulo[0]["Articulo"]["grado_id"]);
		@$articulo[0]["Articulo"]["grado"] = $grao["CatalogoGrado"]["nombre"];
		$grado_id = $articulo[0]["Articulo"]["grado_id"];

		$articulo = $articulo[0]["Articulo"];

		$colegios = $this->Colegio->todosColegios(array());
		$catalogo_familias = $this->CatalogoFamilia->todasFamilias();

		$this->set("articulo", $articulo);
		$this->set("colegios", $colegios);
		$this->set("catalogo_familias", $catalogo_familias);
		$this->set("cicloActual", $this->cicloActual());
		$this->set("colegio_id", $colegio_id);
		$this->set("nivele_id", $nivele_id);
		$this->set("grado_id", $grado_id);
	}
	

//=========================================================================


	public function revisar_identificador()
	{
		$this->layout='ajax';

		$nuevo_id = $this->request->data["nuevo_id"];
		
		$repetido = $this->Articulo->find('count', array(
			'conditions' => array(
				'identificador' => $nuevo_id
			)
		));

		if (!empty($this->request->data["actual_id"]))
		{
			$actual_id = $this->request->data["actual_id"];

			if ($nuevo_id == $actual_id)
				$repetido--;
		}

		if ($repetido > 0)
		{
			$this->set('identificador', '');
			$this->set('placeholder', 'Ese ID ya esta en uso.');
		}
		else
		{
			$this->set('identificador', $nuevo_id);
			$this->set('placeholder', '');
		}
	}
	

//=========================================================================


	public function traer_info()
	{
		$this->layout='ajax';
		$this->loadModel("CatalogoFamilia");
		$this->loadModel("ArticulosPrecio");

		$data = $this->request->data;

		$valido = $this->Articulo->validarBuscador($data);

		if (empty($data["colegio_id"])) $data["colegio_id"] = "0";
		if (empty($data["nivele_id"])) $data["nivele_id"] = "0";
		if (empty($data["grado_id"])) $data["grado_id"] = "0";

		$identificador = $data["identificador"];
		$cont_articulo = $data["cont_articulo"];

		if (!$valido)
			$this->set("articulo", "");
		else
		{
			$condiciones = array(
				'identificador' => $identificador,
				'colegio_id' => array("0", $data["colegio_id"]),
				'nivele_id' => array("0", $data["nivele_id"]),
				'grado_id' => array("0", $data["grado_id"])
			);

			$articulo = $this->Articulo->todosArticulos($condiciones);

			$condiciones = array(
				'articulo_id' => @$articulo[0]["Articulo"]["id"],
				'ciclo' => $this->cicloActual()
			);

			@$precios = $this->ArticulosPrecio->todosArticulosPrecios($condiciones);
			@$iva = $precios[0]["ArticulosPrecio"]["iva"];
			@$precio_venta = $precios[0]["ArticulosPrecio"]["precio_venta"];
			@$precio_publico_default = $precios[0]["ArticulosPrecio"]["precio_publico_default"];

			@$familia = $this->CatalogoFamilia->familiaEspecifica($articulo[0]["Articulo"]["cat_fam_id"]);
			@$familia_nombre = $familia["CatalogoFamilia"]["nombre"];

			$this->set('articulo', @$articulo[0]["Articulo"]);
		}

		$this->set('identificador', $identificador);
		$this->set('cont_articulo', $cont_articulo);
		@$this->set('familia_nombre', $familia_nombre);
		@$this->set('iva', $iva);
		@$this->set('precio_venta', $precio_venta);
		@$this->set('precio_publico_default', $precio_publico_default);
	}
	

//=========================================================================


	public function descargar_excel($nombre = null)
    {
    	if (!in_array($nombre, array("plantillaCatalogoArticulos")))
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

        	if (substr($name[0], 0, 26) != "plantillaCatalogoArticulos") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/articulos/subir_excel");
        	}

			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$articulosAgregados = 0;
			$articulosActualizados = 0;
			$errores_filas = array();

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador
				if (!empty($celdas[1]))
				{
					$articulo = $this->Articulo->find('first', array(
						'conditions' => array(
							'identificador' => $celdas[1]
						),
						'fields' => array(
							'id', 'identificador', 'descripcion', 'imagen', 'colegio_id', 'nivele_id', 'grado_id', 'cat_fam_id'
						)
					));

					//Significa que no existe y se dara de alta
					if (empty($articulo['Articulo']['id']))
					{
						//Si tiene los campos obligatorios llenos
						if (!empty($celdas[1]) &&
							!empty($celdas[2]) &&
							!empty($celdas[3]) &&
							!empty($celdas[4]) &&
							!empty($celdas[5]) &&
							!empty($celdas[6]))
						{
							$agregarFila = $this->agregarFila($celdas, "nuevo");
							if ($agregarFila == 1)
								$articulosAgregados++;
							else
								$errores_filas[$fila] = $agregarFila;
						}
						else
							$errores_filas[$fila] = "Necesita los campos obligatorios.";
					}
					else
					{	//Significa que ya existe y se actualizara
						$agregarFila = $this->agregarFila($celdas, $articulo['Articulo']);
						if ($agregarFila == 1)
							$articulosActualizados++;
						else
							$errores_filas[$fila] = $agregarFila;
					}
				}
				else
					$errores_filas[$fila] = "No hay identificador.";
			}
			
			$errores_filas = base64_encode(json_encode($errores_filas));

			$fila = $fila - 2;
			$this->redirect("/dashboard/resultados/$fila/$articulosAgregados/$articulosActualizados/$errores_filas");
		}
	}


	function agregarFila($celdas, $articulo)
    {
    	$this->loadModel("Colegio");
    	$this->loadModel("Nivele");
    	$this->loadModel("Grado");
    	$this->loadModel("CatalogoFamilia");

		$accion = "agregar";

		if ($articulo != "nuevo")
		{
			$datos_articulo = $articulo;
			$accion = "editar";
		}

		//Datos del articulo
		if (!empty($celdas[1]))
			$datos_articulo['identificador'] = $celdas[1];

		if (!empty($celdas[2]))
			$datos_articulo['descripcion'] = $celdas[2];

		if (!empty($celdas[3]))
		{
			if ($celdas[3] == "Todos")
				$col_iden = 0;
			else
				$col_iden = $celdas[3];

			if ($col_iden)
			{
				$colegio_id = $this->Colegio->todosColegios(array('identificador' => $col_iden));
				$colegio_id = $colegio_id[0]["Colegio"]["id"];
				$datos_articulo['colegio_id'] = $colegio_id;
			}
			else
				$datos_articulo['colegio_id'] = 0;
		}

		if (!empty($celdas[4]))
		{
			$condiciones = array(
				'Nivele.colegio_id' => $datos_articulo['colegio_id'],
				'CatalogoNivele.nombre' => $celdas[4]
			);
			$nivele = $this->Nivele->traerNivel($condiciones);
			if ($nivele)
			{
				$nivele_id = $nivele["Nivele"]["id"];
				$datos_articulo['nivele_id'] = $nivele_id;
			}
			else
				$datos_articulo['nivele_id'] = 0;
		}

		if (!empty($celdas[5]))
		{
			$condiciones = array(
				'Grado.nivele_id' => $datos_articulo['nivele_id'],
				'CatalogoGrado.nombre' => $celdas[5]
			);
			$grado = $this->Grado->traerGrado($condiciones);
			if ($grado)
			{
				$grado_id = $grado["Grado"]["id"];
				$datos_articulo['grado_id'] = $grado_id;
			}
			else
				$datos_articulo['grado_id'] = 0;
		}

		if (!empty($celdas[6]))
		{
			$condiciones = array(
				'nombre' => $celdas[6]
			);
			$familia = $this->CatalogoFamilia->traerFamilia($condiciones);
			$cat_fam_id = $familia["CatalogoFamilia"]["id"];
			$datos_articulo['cat_fam_id'] = $cat_fam_id;
		}

		$data["Articulo"] = $datos_articulo;
		$agregado = $this->Articulo->guardarEnBDD($data, $accion);	
		
		return $agregado;
    }

}
