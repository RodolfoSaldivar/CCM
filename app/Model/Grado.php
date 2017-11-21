<?php
App::uses('AppModel', 'Model');
/**
 * Grado Model
 *
 */
class Grado extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/*
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Nivele' => array(
			'className' => 'Nivele',
			'foreignKey' => 'nivele_id'
		),
		'CatalogoGrado' => array(
			'className' => 'CatalogoGrado',
			'foreignKey' => 'cat_gra_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Articulos' => array(
			'className' => 'Articulos',
			'foreignKey' => 'grado_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function todosGrados($nivele_id)
	{
		$grados = $this->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				'Grado.nivele_id' => $nivele_id
			),
			'fields' => array(
				'Grado.id', 'CatalogoGrado.id', 'CatalogoGrado.nombre'
			)
		));

		return $grados;
	}
	

//=========================================================================


	public function gradoEspecifico($id)
	{
		$grado = $this->find('first', array(
			'recursive' => 0,
			'conditions' => array(
				'Grado.id' => $id
			),
			'fields' => array(
				'Grado.id', 'CatalogoGrado.id', 'CatalogoGrado.nombre'
			)
		));

		return $grado;
	}
	

//=========================================================================


	public function traerGrado($condiciones)
	{
		$grado = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'Grado.id', 'CatalogoGrado.id', 'CatalogoGrado.nombre'
			)
		));

		return $grado;
	}
	

//=========================================================================


	public function guardarEnBDD($atributos)
	{
		$valido = $this->validarInputs($atributos);

		if ($valido != 1)
			return $valido;
		else
		{	
			$queryExitosa = $this->query("
				INSERT INTO CCM.grados
					(nivele_id, cat_gra_id)
				VALUES (
					".$atributos['nivele_id'].",
					".$atributos['cat_gra_id']."
				)
			");

			if ($queryExitosa)
				return 1;
			else
				return 'No se pudo guardar.';
		}
	}

}
