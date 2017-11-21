<?php
App::uses('AppModel', 'Model');
App::uses('Nivele', 'Model');
App::uses('Grado', 'Model');
App::uses('Articulo', 'Model');
App::uses('Paquete', 'Model');
App::uses('CicloHijo', 'Model');
App::uses('Pedido', 'Model');
/**
 * Colegio Model
 *
 */
class Colegio extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/*
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Asociado' => array(
			'className' => 'Asociado',
			'foreignKey' => 'asociado_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Asociado' => array(
			'className' => 'Asociado',
			'foreignKey' => 'colegio_id',
			'dependent' => false
		),
		'Articulos' => array(
			'className' => 'Articulos',
			'foreignKey' => 'colegio_id',
			'dependent' => false
		),
		'OrdenCompra' => array(
			'className' => 'OrdenCompra',
			'foreignKey' => 'colegio_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["identificador"])) $atributos["identificador"] = "NULL";
		if (empty($atributos["nombre"])) $atributos["nombre"] = "NULL";
		if (empty($atributos["nombre_corto"])) $atributos["nombre_corto"] = "NULL";
		if (empty($atributos["logo"])) $atributos["logo"] = "NULL";
		if (empty($atributos["asociado_id"])) $atributos["asociado_id"] = "NULL";
		if (empty($atributos["activo"])) $atributos["activo"] = "1";
		if (empty($atributos["mensaje"])) $atributos["mensaje"] = "NULL";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["identificador"] == "NULL") $atributos["identificador"] = "";
		if ($atributos["nombre"] == "NULL") $atributos["nombre"] = "";
		if ($atributos["nombre_corto"] == "NULL") $atributos["nombre_corto"] = "";
		if ($atributos["logo"] == "NULL") $atributos["logo"] = "";
		if ($atributos["asociado_id"] == "NULL") $atributos["asociado_id"] = "";
		if ($atributos["activo"] == "cero") $atributos["activo"] = "0";
		if ($atributos["mensaje"] == "NULL") $atributos["mensaje"] = "";

		return $atributos;
	}
	

//=========================================================================


	public function todosColegios($condiciones)
	{
		$colegios = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'identificador', 'nombre', 'nombre_corto', 'logo', 'asociado_id', 'activo', 'mensaje'
			),
			'order' => array(
				'nombre' => 'asc'
			)
		));

		return $colegios;
	}
	

//=========================================================================


	public function guardarEnBDD($data, $accion)
	{
		$data["Colegio"] = $this->llenarAtributosVacios($data["Colegio"]);

		$valido = $this->validarInputs($data["Colegio"]);

		if ($valido != 1)
			return $valido;
		else
		{	
			$data["Colegio"] = $this->quitarNulos($data["Colegio"]);

			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.colegios
						(identificador, nombre, nombre_corto, logo, asociado_id, activo, mensaje)
					VALUES (
						'".$data["Colegio"]['identificador']."',
						'".$data["Colegio"]['nombre']."',
						'".$data["Colegio"]['nombre_corto']."',
						'".$data["Colegio"]['logo']."',
						".$data["Colegio"]['asociado_id'].",
						".$data["Colegio"]['activo'].",
						'".$data["Colegio"]['mensaje']."'
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.colegios
					SET identificador = '".$data["Colegio"]['identificador']."',
						nombre = '".$data["Colegio"]['nombre']."',
						nombre_corto = '".$data["Colegio"]['nombre_corto']."',
						logo = '".$data["Colegio"]['logo']."',
						mensaje = '".$data["Colegio"]['mensaje']."',
						asociado_id = ".$data["Colegio"]['asociado_id']."
					WHERE id = ".$data["Colegio"]['id']."
				");

				$resultados = @$this->borrarHijos($data["Colegio"]['id'], $data["Niveles"]);
				$data["Niveles"] = $resultados[1];
			}

			if ($queryExitosa)
			{
				$Nivele = new Nivele();
				$Grado = new Grado();

				if (empty($data["Colegio"]["id"]))
				{
					$colegio_creado = $this->find("first", array(
						'conditions' => array(
							'identificador' => $data["Colegio"]['identificador'],
							'nombre' => $data["Colegio"]['nombre']
						),
						'fields' => array('id')
					));
					$colegio_creado = $colegio_creado["Colegio"]["id"];
				}
				else
					$colegio_creado = $data["Colegio"]["id"];

				$todo_correcto = 1;
				if (!empty($data["Niveles"]))
				foreach ($data["Niveles"] as $cat_niv_id => $grados)
				{
					$nivele_existente = $Nivele->find('first', array(
						'conditions' => array(
							'colegio_id' => $colegio_creado,
							'cat_niv_id' => $cat_niv_id
						),
						'fields' => array('id')
					));

					if ($nivele_existente)
					{
						$nivele_creado = $nivele_existente["Nivele"]["id"];
						$nivele_guardado = 1;
					}
					else
					{
						$datos_nivele["colegio_id"] = $colegio_creado;
						$datos_nivele["cat_niv_id"] = $cat_niv_id;

						$nivele_guardado = $Nivele->guardarEnBDD($datos_nivele);
					}
						
					if ($nivele_guardado != 1)
						return $nivele_guardado;	
					else
					{
						if (!$nivele_existente)
						{
							$nivele_creado = $Nivele->find("first", array(
								'conditions' => array(
									'colegio_id' => strval($datos_nivele['colegio_id']),
									'cat_niv_id' => $datos_nivele['cat_niv_id']
								),
								'order' => array('id' => 'DESC'),
								'fields' => array('id')
							));
							$nivele_creado = $nivele_creado["Nivele"]["id"];
						}


						foreach ($grados as $keyG => $cat_gra_id)
						{
							$datos_grado["nivele_id"] = $nivele_creado;
							$datos_grado["cat_gra_id"] = $cat_gra_id;

							$grado_guardado = $Grado->guardarEnBDD($datos_grado);

							if ($grado_guardado != 1)
								$todo_correcto = 0;
						}

						if (!$todo_correcto)
							return "Algo salio mal.";
					}
				}

				if ($todo_correcto)
					return 1;
				else
					return "Algo salio mal.";
			}
			else
				return 'No se pudo guardar.';
		}
	}
	

//=========================================================================


	private function borrarHijos($colegio_id, $data_niveles)
	{
		$Nivele = new Nivele();
		$Grado = new Grado();
		$Articulo = new Articulo();
		$CicloHijo = new CicloHijo();
		$Paquete = new Paquete();
		$Pedido = new Pedido();

		$mensaje = 0;

		$niveles = $Nivele->find("list", array(
			'conditions' => array('colegio_id' => $colegio_id),
			'fields' => array('id', 'cat_niv_id')
		));

		foreach ($niveles as $nivele_id => $cat_niv_id)
		{
			$grados = $Grado->find("list", array(
				'conditions' => array('nivele_id' => $nivele_id),
				'fields' => array('id', 'cat_gra_id')
			));

			foreach ($grados as $grado_id => $cat_gra_id)
			{
				$articulos = $Articulo->find("list", array(
					'conditions' => array('grado_id' => $grado_id),
					'fields' => array('id', 'id')
				));
				$ciclo_hijos = $CicloHijo->find("list", array(
					'conditions' => array('grado_id' => $grado_id),
					'fields' => array('id', 'id')
				));
				$paquetes = $Paquete->find("list", array(
					'conditions' => array('grado_id' => $grado_id),
					'fields' => array('id', 'id')
				));
				$pedidos = $Pedido->find("list", array(
					'recursive' => 0,
					'conditions' => array('CicloHijo.grado_id' => $grado_id),
					'fields' => array('Pedido.id', 'Pedido.id')
				));

				if (empty($articulos) &&
					empty($ciclo_hijos) &&
					empty($paquetes) &&
					empty($pedidos))
				{
					$Grado->query("
						DELETE FROM CCM.grados
						WHERE id = $grado_id
					");
				}
				else
				{
					$mensaje = "Ciertos grados tienen información vinculada, no pueden eliminarse.";
					unset($data_niveles[$cat_niv_id][$cat_gra_id]);
				}
			}

			$articulos = $Articulo->find("list", array(
				'conditions' => array('nivele_id' => $nivele_id),
				'fields' => array('id', 'id')
			));
			$ciclo_hijos = $CicloHijo->find("list", array(
				'conditions' => array('nivele_id' => $nivele_id),
				'fields' => array('id', 'id')
			));
			$paquetes = $Paquete->find("list", array(
				'conditions' => array('nivele_id' => $nivele_id),
				'fields' => array('id', 'id')
			));
			$pedidos = $Pedido->find("list", array(
				'recursive' => 0,
				'conditions' => array('CicloHijo.nivele_id' => $nivele_id),
				'fields' => array('Pedido.id', 'Pedido.id')
			));

			if (empty($articulos) &&
				empty($ciclo_hijos) &&
				empty($paquetes) &&
				empty($pedidos))
			{
				$Nivele->query("
					DELETE FROM CCM.niveles
					WHERE id = $nivele_id
				");
			}
		}

		return array($mensaje, $data_niveles);
	}

}
