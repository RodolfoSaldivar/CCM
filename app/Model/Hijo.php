<?php
App::uses('AppModel', 'Model');
App::uses('CicloHijo', 'Model');
App::uses('Colegio', 'Model');
App::uses('Nivele', 'Model');
App::uses('Grado', 'Model');
/**
 * Hijo Model
 *
 */
class Hijo extends AppModel {

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
		'CicloHijo' => array(
			'className' => 'CicloHijo',
			'foreignKey' => 'hijo_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function traerHijos($condiciones)
	{
		$CicloHijo = new CicloHijo();
		$Colegio = new Colegio();
		$Nivele = new Nivele();
		$Grado = new Grado();

		$hijos = $CicloHijo->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'Hijo.id', 'Hijo.nombre', 'Hijo.a_paterno', 'Hijo.a_materno', 'CicloHijo.id', 'CicloHijo.colegio_id', 'CicloHijo.nivele_id', 'CicloHijo.grado_id'
			),
			'order' => array('Hijo.nombre' => 'asc')
		));

		foreach ($hijos as $key => $hijo)
		{
			$colegio_nombre = $Colegio->find('first', array(
				'conditions' => array('id' => $hijo["CicloHijo"]["colegio_id"]),
				'fields' => array('nombre')
			));
			$colegio_nombre = $colegio_nombre["Colegio"]["nombre"];
			$hijos[$key]["Hijo"]["colegio"] = $colegio_nombre;

			$nivel_nombre = $Nivele->find('first', array(
				'recursive' => 0,
				'conditions' => array('Nivele.id' => $hijo["CicloHijo"]["nivele_id"]),
				'fields' => array('CatalogoNivele.nombre')
			));
			$nivel_nombre = $nivel_nombre["CatalogoNivele"]["nombre"];
			$hijos[$key]["Hijo"]["nivel"] = $nivel_nombre;

			$grado_nombre = $Grado->find('first', array(
				'recursive' => 0,
				'conditions' => array('Grado.id' => $hijo["CicloHijo"]["grado_id"]),
				'fields' => array('CatalogoGrado.nombre')
			));
			$grado_nombre = $grado_nombre["CatalogoGrado"]["nombre"];
			$hijos[$key]["Hijo"]["grado"] = $grado_nombre;
		}

		return $hijos;
	}
	

//=========================================================================


	public function guardarEnBDD($data, $accion)
	{
		$valido = $this->validarInputs($data["Hijo"]);

		if ($valido != 1)
			return $valido;
		else
		{
			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.hijos
						(nombre, a_paterno, a_materno, asociado_id)
					VALUES (
						'".$data["Hijo"]['nombre']."',
						'".$data["Hijo"]['a_paterno']."',
						'".$data["Hijo"]['a_materno']."',
						".$data["Hijo"]['asociado_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.hijos
					SET nombre = '".$data["Hijo"]['nombre']."',
						a_paterno = '".$data["Hijo"]['a_paterno']."',
						a_materno = '".$data["Hijo"]['a_materno']."'
					WHERE id = ".$data["Hijo"]['id']."
				");
			}

			if ($queryExitosa)
			{
				$CicloHijo = new CicloHijo();

				if (empty($data["Hijo"]["id"]))
				{
					$hijo_creado = $this->find("first", array(
						'conditions' => array(
							'nombre' => $data['Hijo']['nombre'],
							'a_paterno' => $data['Hijo']['a_paterno'],
							'a_materno' => $data['Hijo']['a_materno'],
							'asociado_id' => $data["Hijo"]["asociado_id"]
						),
						'order' => array('id' => "DESC"),
						'fields' => array('id')
					));
					$hijo_creado = $hijo_creado["Hijo"]["id"];
				}
				else
					$hijo_creado = $data["Hijo"]["id"];


				$data["CicloHijo"]["hijo_id"] = $hijo_creado;

				$guardado = $CicloHijo->guardarEnBDD($data["CicloHijo"]);

				if ($guardado != 1)
					return $guardado;
				else
					return 1;
			}
			else
				return 'No se pudo guardar.';
		}
	}


}
