<?php
App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * Colegios Controller
 *
 */
class ColegiosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("Nivele");
		$this->loadModel("CatalogoNivele");

		$catalogo_niveles = $this->CatalogoNivele->todosNiveles();

		$colegios = $this->Colegio->todosColegios(array());

		foreach ($colegios as $keyC => $colegio)
		{
			$niveles = $this->Nivele->todosNiveles($colegio["Colegio"]["id"]);
			foreach ($niveles as $keyN => $nivel)
			{
				$colegios[$keyC]["Niveles"][$keyN] = $nivel["CatalogoNivele"]["id"];
			}
		}
		
		$this->set("catalogo_niveles", $catalogo_niveles);
		$this->set("colegios", $colegios);
	}
	

//=========================================================================


	public function agregar()
	{
		$this->layout = 'admin';
		$this->loadModel("Asociado");
		$this->loadModel("CatalogoNivele");
		$this->loadModel("CatalogoGrado");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$image_data = @file_get_contents($data["Logo"]["tmp_name"]);
			$logo = base64_encode($image_data);
			$data["Colegio"]["logo"] = $logo;

			if (strlen($logo) > 1000000)
			{
				$this->Session->setFlash('Imagen demasiado pesada, intentar con otra.');
			}
			else
			{
				$guardado = $this->Colegio->guardarEnBDD($data, "agregar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Colegio agregado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
		}

		$condiciones = array(
			'tipo' => 'Director'
		);

		$asociados = $this->Asociado->traerAsociados($condiciones);
		$catalogo_niveles = $this->CatalogoNivele->todosNiveles();
		$niv_gra = $this->CatalogoGrado->todosGrados();

		$this->set("asociados", $asociados);
		$this->set("catalogo_niveles", $catalogo_niveles);
		$this->set("niv_gra", $niv_gra);
	}
	

//=========================================================================


	public function editar($id = null)
	{
		$this->layout = 'admin';
		$this->loadModel("Asociado");
		$this->loadModel("Nivele");
		$this->loadModel("Grado");
		$this->loadModel("CatalogoNivele");
		$this->loadModel("CatalogoGrado");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			if (!empty($data["Logo"]["tmp_name"]))
			{
				$image_data = @file_get_contents($data["Logo"]["tmp_name"]);
				$logo = base64_encode($image_data);
				$data["Colegio"]["logo"] = $logo;
			}		

			if (@strlen($data["Colegio"]["logo"]) > 1000000)
			{
				$this->Session->setFlash('Imagen demasiado pesada, intentar con otra.');
			}
			else
			{
				$guardado = $this->Colegio->guardarEnBDD($data, "editar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Colegio guardado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
		}

		$condiciones = array(
			'id' => $id
		);

		$colegio = $this->Colegio->todosColegios($condiciones);

		$niveles = $this->Nivele->todosNiveles($colegio[0]["Colegio"]["id"]);
		foreach ($niveles as $key => $nivel)
		{
			$grados = $this->Grado->todosGrados($nivel["Nivele"]["id"]);
			$niveles[$key]["Grados"] = $grados;
		}
		$colegio[0]["Niveles"] = $niveles;

		$condiciones = array(
			'tipo' => 'Director'
		);

		$asociados = $this->Asociado->traerAsociados($condiciones);
		$catalogo_niveles = $this->CatalogoNivele->todosNiveles();
		$niv_gra = $this->CatalogoGrado->todosGrados();

		$this->set("asociados", $asociados);
		$this->set("catalogo_niveles", $catalogo_niveles);
		$this->set("niv_gra", $niv_gra);
		$this->set("colegio", $colegio);
	}
	

//=========================================================================


	public function revisar_identificador()
	{
		$this->layout='ajax';

		$nuevo_id = $this->request->data["nuevo_id"];
		
		$repetido = $this->Colegio->find('count', array(
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


	public function activo_actualizar()
	{
		$this->layout='ajax';

		$colegio_id = $this->request->data["colegio_id"];
		$activo = $this->request->data["activo"];

		if ($activo == 1)
			$activo = 0;
		else
			$activo = 1;

		$this->Colegio->query("
			UPDATE ccm.colegios
			SET activo = $activo
			WHERE id = $colegio_id
		");

		$colegio["Colegio"]["id"] = $colegio_id;
		$colegio["Colegio"]["activo"] = $activo;
		$this->set("colegio", $colegio);
	}
	

//=========================================================================


	public function descargar_excel($nombre = null)
    {
    	if (!in_array($nombre, array("plantillaColegios")))
    		$this->redirect("/colegios/subir_excel");

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
        		$this->redirect("/colegios/subir_excel");
        	}

        	if (substr($name[0], 0, 17) != "plantillaColegios") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/colegios/subir_excel");
        	}

			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$colegiosAgregados = 0;
			$colegiosActualizados = 0;
			$errores_filas = array();

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador
				if (!empty($celdas[1]))
				{
					$colegio = $this->Colegio->find('first', array(
						'conditions' => array(
							'identificador' => $celdas[1]
						),
						'fields' => array(
							'id', 'identificador', 'nombre', 'nombre_corto', 'logo', 'asociado_id'
						)
					));

					//Significa que no existe y se dara de alta
					if (empty($colegio['Colegio']['id']))
					{
						//Si tiene los campos obligatorios llenos
						if (!empty($celdas[1]) &&
							!empty($celdas[2]) &&
							!empty($celdas[3]) &&
							!empty($celdas[4]) &&
							!empty($celdas[5]) &&
							!empty($celdas[6]) &&
							!empty($celdas[7]) &&
							!empty($celdas[8]) &&
							!empty($celdas[9]))
						{
							$agregarFila = $this->agregarFila($celdas, "nuevo");
							if ($agregarFila == 1)
								$colegiosAgregados++;
							else
								$errores_filas[$fila] = $agregarFila;
						}
						else
							$errores_filas[$fila] = "Necesita los campos obligatorios.";
					}
					else
					{	//Significa que ya existe y se actualizara
						$agregarFila = $this->agregarFila($celdas, $colegio['Colegio']);
						if ($agregarFila == 1)
							$colegiosActualizados++;
						else
							$errores_filas[$fila] = $agregarFila;
					}
				}
				else
					$errores_filas[$fila] = "No hay identificador.";
			}
			
			$errores_filas = base64_encode(json_encode($errores_filas));

			$fila = $fila - 2;
			$this->redirect("/dashboard/resultados/$fila/$colegiosAgregados/$colegiosActualizados/$errores_filas");
		}
	}


	function agregarFila($celdas, $colegio)
    {
    	$this->loadModel("Asociado");

		$accion_colegio = "agregar";

		if ($colegio != "nuevo")
		{
			$datos_colegio = $colegio;
			$accion_colegio = "editar";
		}

		$accion_asociado = $accion_colegio;

		//Datos del Asociado
		if (!empty($celdas[4]) &&
			!empty($celdas[5]) &&
			!empty($celdas[6]) &&
			!empty($celdas[7]) &&
			!empty($celdas[8]) &&
			!empty($celdas[9]))
		{
			$asociado = $this->Asociado->traerAsociados(array('mail' => $celdas[7]));
			if ($asociado)
			{
				$datos_asociado = $asociado[0]["Asociado"];
				$accion_asociado = "editar";
			}
			else
			{
				$datos_asociado['mail'] = $tipo = $celdas[7];
				$accion_asociado = "agregar";
			}

			$datos_asociado['nombre'] = $celdas[4];

			$datos_asociado['a_paterno'] = $celdas[5];

			$datos_asociado['a_materno'] = $tipo = $celdas[6];

			$blowF = new BlowfishPasswordHasher();
			$contra_encr = $blowF->hash($celdas[8]);
			$datos_asociado['password'] = $contra_encr;

			$datos_asociado['celular'] = $celdas[9];

			$datos_asociado['token'] = $this->Asociado->token();

			$datos_asociado['tipo'] = "Director";

			$guardado = $this->Asociado->guardarEnBDD($datos_asociado, $accion_asociado);

			if ($guardado != 1)
				return $guardado;
		}

		//Datos del colegio
		$asociado_id = @$this->Asociado->traerAsociados(array('mail' => $datos_asociado['mail']));
		if ($asociado_id)
			$datos_colegio['asociado_id'] = $asociado_id[0]["Asociado"]["id"];

		if (!empty($celdas[1]))
			$datos_colegio['identificador'] = $celdas[1];

		if (!empty($celdas[2]))
			$datos_colegio['nombre_corto'] = $celdas[2];

		if (!empty($celdas[3]))
			$datos_colegio['nombre'] = $celdas[3];

		$data["Colegio"] = $datos_colegio;
		$agregado = $this->Colegio->guardarEnBDD($data, $accion_colegio);	
		
		return $agregado;
    }

}
