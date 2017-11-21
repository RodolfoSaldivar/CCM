<?php
App::uses('AppModel', 'Model');
/**
 * CicloHijo Model
 *
 */
class CicloHijo extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/*
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Hijo' => array(
			'className' => 'Hijo',
			'foreignKey' => 'hijo_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'ciclo_hijo_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function traerInfo($condiciones)
	{
		$info = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'hijo_id', 'ciclo', 'colegio_id', 'nivele_id', 'grado_id'
			)
		));

		return $info;
	}
	

//=========================================================================


	public function guardarEnBDD($atributos)
	{
		$valido = $this->validarInputs($atributos);

		if ($valido != 1)
			return $valido;
		else
		{
			$mismo_ciclo = $this->find('first', array(
				'conditions' => array(
					'ciclo' => $atributos['ciclo'],
					'hijo_id' => $atributos['hijo_id']
				),
				'fields' => array('id')
			));

			if (!$mismo_ciclo)
			{	
				$queryExitosa = $this->query("
					INSERT INTO CCM.ciclo_hijos
						(ciclo, hijo_id, colegio_id, nivele_id, grado_id, estatus)
					VALUES (
						'".$atributos['ciclo']."',
						".$atributos['hijo_id'].",
						".$atributos['colegio_id'].",
						".$atributos['nivele_id'].",
						".$atributos['grado_id'].",
						1
					)
				");
			}
			else
			{
				$queryExitosa = $this->query("
					UPDATE CCM.ciclo_hijos
					SET colegio_id = ".$atributos['colegio_id'].",
						nivele_id = ".$atributos['nivele_id'].",
						grado_id = ".$atributos['grado_id']."
					WHERE id = ".$mismo_ciclo["CicloHijo"]["id"]."
				");
			}

			if ($queryExitosa)
			{
				return 1;
			}
			else
				return 'No se pudo guardar.';
		}
	}
	

//=========================================================================


	public function eliminar($ciclo_id)
	{
		return $this->query("
			UPDATE ccm.ciclo_hijos
			SET estatus = 0
			WHERE id = $ciclo_id
		");
	}

}
