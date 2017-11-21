<?php
App::uses('AppController', 'Controller');
/**
 * Paquetes Controller
 *
 */
class PaquetesController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Padres
		if (in_array($this->action, array('seleccionar')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Padre")))
    		{
    			return true;
    		}
        }

		//Acceso para Cajeros
		if (in_array($this->action, array('mostrar_paquetes', 'sintesis', 'sintesis_actualizar', 'detalle', 'detalle_actualizar')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Cajero")))
    		{
    			return true;
    		}
        }

	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");

		$colegios = $this->Colegio->todosColegios(array());

		$this->set("colegios", $colegios);
	}
	

//=========================================================================


	public function activo_actualizar()
	{
		$this->layout='ajax';

		$paquete_id = $this->request->data["paquete_id"];
		$activo = $this->request->data["activo"];

		if ($activo == 1)
			$activo = 0;
		else
			$activo = 1;

		$this->Paquete->query("
			UPDATE ccm.paquetes
			SET estatus = $activo
			WHERE id = $paquete_id
		");

		$paquete["Paquete"]["id"] = $paquete_id;
		$paquete["Paquete"]["estatus"] = $activo;
		$this->set("paquete", $paquete);
	}
	

//=========================================================================


	public function index_mostrar_paquetes()
	{
		$this->layout = 'ajax';

		$this->loadModel("ArticulosPaquete");

		$colegio_id = $this->request->data["colegio_id"];
		$nivele_id = $this->request->data["nivele_id"];
		$grado_id = $this->request->data["grado_id"];

		$condiciones = array(
			'colegio_id' => array("0", $colegio_id),
			'nivele_id' => array("0", $nivele_id),
			'grado_id' => array("0", $grado_id),
			'ciclo' => $this->cicloActual()
		);
		
		if ($nivele_id == "todos")
		{
			unset($condiciones["nivele_id"]);
			unset($condiciones["grado_id"]);
		}

		if ($grado_id == "todos")
			unset($condiciones["grado_id"]);

		$paquetes = $this->Paquete->traerPaquetes($condiciones);

		foreach ($paquetes as $key => $paquete)
		{
			$condiciones = array('Paquete.id' => $paquete["Paquete"]["id"]);
			$precios = $this->ArticulosPaquete->precios($condiciones);
			$paquetes[$key]["Precios"] = $precios;

			$paquetes[$key]["Paquete"] = $this->Paquete->traerNombresCNG($paquete["Paquete"]);
		}

		$this->set("paquetes", $paquetes);
	}
	

//=========================================================================


	public function sintesis()
	{
	    $this->layout = 'admin';

	    $this->loadModel("Colegio");

	    $colegios = $this->Colegio->todosColegios(array());
	    $ciclo_actual = $this->cicloActual();

	    $ciclos = array($ciclo_actual, $ciclo_actual-1, $ciclo_actual-2);

	    $user_tipo = $this->Session->read("Auth.User.tipo");

		$this->set("user_tipo", $user_tipo);
	    $this->set("colegios", $colegios);
	    $this->set("ciclos", $ciclos);
	}
	

//=========================================================================


	public function sintesis_actualizar()
	{
	    $this->layout = 'ajax';

	    $ciclo = $this->request->data("ciclo");
	    $colegio_id = $this->request->data("colegio_id");

	    $paquetes = $this->Paquete->traerSintesis($ciclo, $colegio_id);

	    $this->set("paquetes", $paquetes);
	}
	

//=========================================================================


	public function detalle()
	{
	    $this->layout = 'admin';

	    $this->loadModel("Colegio");

	    $colegios = $this->Colegio->todosColegios(array());
	    $ciclo_actual = $this->cicloActual();

	    $ciclos = array($ciclo_actual, $ciclo_actual-1, $ciclo_actual-2);

	    $user_tipo = $this->Session->read("Auth.User.tipo");

		$this->set("user_tipo", $user_tipo);
	    $this->set("colegios", $colegios);
	    $this->set("ciclos", $ciclos);
	}
	

//=========================================================================


	public function detalle_actualizar()
	{
	    $this->layout = 'ajax';

	    $ciclo = $this->request->data("ciclo");
	    $colegio_id = $this->request->data("colegio_id");
	    $estatus = $this->request->data("estatus");

	    $paquetes = $this->Paquete->traerDetalle($ciclo, $colegio_id, $estatus);

	    $this->set("paquetes", $paquetes);
	}
	

//=========================================================================


	public function ver($id = null)
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");
		$this->loadModel("ArticulosPaquete");

		$paquete = $this->Paquete->traerPaquetes(array('id' => $id));

		$condiciones = array('Paquete.id' => $paquete[0]["Paquete"]["id"]);
		$precios = $this->ArticulosPaquete->precios($condiciones);
		$paquete[0]["Precios"] = $precios;

		$paquete[0]["Paquete"] = $this->Paquete->traerNombresCNG($paquete[0]["Paquete"]);
		
		$colegios = $this->Colegio->todosColegios(array());

		$art_paq = $this->ArticulosPaquete->traerArticulosEnPaquete($condiciones);

		$this->set("colegios", $colegios);
		$this->set("art_paq", $art_paq);
		$this->set("paquete", $paquete[0]);
	}
	

//=========================================================================


	public function agregar()
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$image_data = @file_get_contents($data["Imagen"]["tmp_name"]);
			$imagen = base64_encode($image_data);
			$data["Paquete"]["imagen"] = $imagen;
			$data["Paquete"]["ciclo"] = $this->cicloActual();

			if (strlen($imagen) > 1000000)
			{
				$this->Session->setFlash('Imagen demasiado pesada, intentar con otra.');
			}
			else
			{
				$guardado = $this->Paquete->guardarEnBDD($data, "agregar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Paquete agregado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
		}
		
		$colegios = $this->Colegio->todosColegios(array());

		$this->set("colegios", $colegios);
	}
	

//=========================================================================


	public function editar($id = null)
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");
		$this->loadModel("ArticulosPaquete");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;
			if ($data["Paquete"]["colegio_id"] == "nada")
				$data["Paquete"]["colegio_id"] = 0;

			if (!empty($data["Imagen"]["tmp_name"]))
			{
				$image_data = @file_get_contents($data["Imagen"]["tmp_name"]);
				$imagen = base64_encode($image_data);
				$data["Paquete"]["imagen"] = $imagen;
			}

			if (@strlen($data["Paquete"]["imagen"]) > 1000000)
			{
				$this->Session->setFlash('Imagen demasiado pesada, intentar con otra.');
			}
			else
			{
				$guardado = $this->Paquete->guardarEnBDD($data, "editar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Paquete guardado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
		}

		$paquete = $this->Paquete->traerPaquetes(array('id' => $id));

		$condiciones = array('Paquete.id' => $paquete[0]["Paquete"]["id"]);
		$precios = $this->ArticulosPaquete->precios($condiciones);
		$paquete[0]["Precios"] = $precios;

		$paquete[0]["Paquete"] = $this->Paquete->traerNombresCNG($paquete[0]["Paquete"]);
		
		$colegios = $this->Colegio->todosColegios(array());

		$art_paq = $this->ArticulosPaquete->traerArticulosEnPaquete($condiciones);

		$this->set("colegios", $colegios);
		$this->set("art_paq", $art_paq);
		$this->set("paquete", $paquete[0]);
		$this->set("colegio_id", $paquete[0]["Paquete"]["colegio_id"]);
		$this->set("nivele_id", $paquete[0]["Paquete"]["nivele_id"]);
		$this->set("grado_id", $paquete[0]["Paquete"]["grado_id"]);
	}
	

//=========================================================================


	public function agregar_articulo()
	{
		$this->layout = 'ajax';
		
		$this->set("cont_articulo", $this->request->data("cont_articulo"));
	}
	

//=========================================================================


	public function revisar_identificador()
	{
		$this->layout='ajax';

		$nuevo_id = $this->request->data["nuevo_id"];
		
		$repetido = $this->Paquete->find('count', array(
			'conditions' => array(
				'identificador' => $nuevo_id,
				'ciclo' => $this->cicloActual()
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


	public function seleccionar($hijo_id = null)
	{
		if ($hijo_id != $this->Session->read("hijo_id"))
			$this->Session->delete("Carrito");

		$this->loadModel("Hijo");
		$this->loadModel("Colegio");
		$this->loadModel("ArticulosPaquete");

		$this->Session->write("hijo_id", $hijo_id);
		
		$condiciones = array(
			'Hijo.id' => $hijo_id,
			'CicloHijo.ciclo' => $this->cicloActual()
		);
		$hijo = $this->Hijo->traerHijos($condiciones);

		$condiciones = array('id' => $hijo[0]["CicloHijo"]["colegio_id"]);
		$colegio = $this->Colegio->todosColegios($condiciones);

		$condiciones = array(
			'colegio_id' => array("0", $hijo[0]["CicloHijo"]["colegio_id"]),
			'nivele_id' => array("0", $hijo[0]["CicloHijo"]["nivele_id"]),
			'grado_id' => array("0", $hijo[0]["CicloHijo"]["grado_id"]),
			'ciclo' => $this->cicloActual(),
			'estatus' => 1
		);
		$paquetes = $this->Paquete->traerPaquetes($condiciones);
		foreach ($paquetes as $key => $paquete)
		{
			$condiciones = array('Paquete.id' => $paquete["Paquete"]["id"]);
			$precios = $this->ArticulosPaquete->precios($condiciones);
			$paquetes[$key]["Precios"] = $precios;
		}
		
		$this->set("hijo", $hijo[0]);
		$this->set("colegio", $colegio[0]);
		$this->set("paquetes", $paquetes);
	}
	

//=========================================================================


	public function mostrar_paquetes()
	{
		$this->layout = 'ajax';

		$this->loadModel("ArticulosPaquete");

		$nivele_id = $this->request->data["nivele_id"];
		$grado_id = $this->request->data["grado_id"];
		
		$condiciones = array(
			'colegio_id' => array("0", $this->Session->read("Auth.User.colegio_id")),
			'nivele_id' => array("0", $nivele_id),
			'grado_id' => array("0", $grado_id),
			'ciclo' => $this->cicloActual()
		);

		$paquetes = $this->Paquete->traerPaquetes($condiciones);
		foreach ($paquetes as $key => $paquete)
		{
			$condiciones = array('Paquete.id' => $paquete["Paquete"]["id"]);
			$precios = $this->ArticulosPaquete->precios($condiciones);
			$paquetes[$key]["Precios"] = $precios;
		}

		$this->set("paquetes", $paquetes);
	}
	

//=========================================================================


	public function descargar_excel($nombre = null)
    {
    	if (!in_array($nombre, array("plantillaPaquetes")))
    		$this->redirect("/paquetes/subir_excel");

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
        		$this->redirect("/paquetes/subir_excel");
        	}

        	if (substr($name[0], 0, 17) != "plantillaPaquetes") 
        	{
        		$this->Session->setFlash('Elija el mismo archivo descargado.');
        		$this->redirect("/paquetes/subir_excel");
        	}
        	
			require_once 'php/reader.php';
			$arch_excel = new Spreadsheet_Excel_Reader();
			$arch_excel->setOutputEncoding('iso-8859-1');
			$arch_excel->read($data['tmp_name']);
			error_reporting(E_ALL ^ E_NOTICE);

			$paquetesAgregados = 0;
			$paquetesActualizados = 0;
			$errores_filas = array();

			//Por la cantidad de filas que tenga el archivo
			//La fila 1 son los títulos, por lo que la info empieza en la 2
			for ($fila = 2; $fila <= $arch_excel->sheets[0]['numRows']; $fila++)
			{
				@$celdas = array_map("trim", $arch_excel->sheets[0]['cells'][$fila]);

				//Siempre debe estar el identificador
				if (!empty($celdas[1]) && !empty($celdas[6]))
				{
					$paquete = $this->Paquete->find('first', array(
						'conditions' => array(
							'identificador' => $celdas[1],
							'ciclo' => $celdas[6]
						),
						'fields' => array(
							'id', 'identificador', 'descripcion', 'imagen', 'colegio_id', 'nivele_id', 'grado_id', 'ciclo'
						)
					));

					//Significa que no existe y se dara de alta
					if (empty($paquete['Paquete']['id']))
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
								$paquetesAgregados++;
							else
								$errores_filas[$fila] = $agregarFila;
						}
						else
							$errores_filas[$fila] = "Necesita los campos obligatorios.";
					}
					else
					{	//Significa que ya existe y se actualizara
						$agregarFila = $this->agregarFila($celdas, $paquete['Paquete']);
						if ($agregarFila == 1)
							$paquetesActualizados++;
						else
							$errores_filas[$fila] = $agregarFila;
					}
				}
				else
					$errores_filas[$fila] = "No hay identificador de paquete o ciclo.";
			}
			
			$errores_filas = base64_encode(json_encode($errores_filas));

			$fila = $fila - 2;
			$this->redirect("/dashboard/resultados/$fila/$paquetesAgregados/$paquetesActualizados/$errores_filas");
		}
	}


	function agregarFila($celdas, $paquete)
    {
    	$this->loadModel("Colegio");
    	$this->loadModel("Nivele");
    	$this->loadModel("Grado");
    	$this->loadModel("CatalogoFamilia");

		$accion = "agregar";

		if ($paquete != "nuevo")
		{
			$datos_paquete = $paquete;
			$accion = "editar";
		}

		//Datos del paquete
		if (!empty($celdas[1]))
			$datos_paquete['identificador'] = $celdas[1];

		if (!empty($celdas[2]))
			$datos_paquete['descripcion'] = $celdas[2];

		if (!empty($celdas[3]))
		{
			if ($celdas[3] == "Todos")
				$col_iden = 0;
			else
				$col_iden = $celdas[3];

			$colegio_id = $this->Colegio->todosColegios(array('identificador' => $col_iden));
			if ($colegio_id)
			{
				$colegio_id = $colegio_id[0]["Colegio"]["id"];
				$datos_paquete['colegio_id'] = $colegio_id;
			}
			else
				$datos_paquete['colegio_id'] = 0;
		}

		if (!empty($celdas[4]))
		{
			$condiciones = array(
				'Nivele.colegio_id' => $datos_paquete['colegio_id'],
				'CatalogoNivele.nombre' => $celdas[4]
			);
			$nivele = $this->Nivele->traerNivel($condiciones);
			if ($nivele)
			{
				$nivele_id = $nivele["Nivele"]["id"];
				$datos_paquete['nivele_id'] = $nivele_id;
			}
			else
				$datos_paquete['nivele_id'] = 0;
		}

		if (!empty($celdas[5]))
		{
			$condiciones = array(
				'Grado.nivele_id' => $datos_paquete['nivele_id'],
				'CatalogoGrado.nombre' => $celdas[5]
			);
			$grado = $this->Grado->traerGrado($condiciones);
			if ($grado)
			{
				$grado_id = $grado["Grado"]["id"];
				$datos_paquete['grado_id'] = $grado_id;
			}
			else
				$datos_paquete['grado_id'] = 0;
		}

		if (!empty($celdas[6]))
			$datos_paquete['ciclo'] = $celdas[6];

		$data["Paquete"] = $datos_paquete;
		$agregado = $this->Paquete->guardarEnBDD($data, $accion);	
		
		return $agregado;
    }

}
