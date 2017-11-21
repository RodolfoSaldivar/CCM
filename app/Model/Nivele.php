<?php
App::uses('AppModel', 'Model');
/**
 * Nivele Model
 *
 */
class Nivele extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/*
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'colegio_id'
		),
		'CatalogoNivele' => array(
			'className' => 'CatalogoNivele',
			'foreignKey' => 'cat_niv_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Grado' => array(
			'className' => 'Grado',
			'foreignKey' => 'nivele_id',
			'dependent' => false
		),
		'Articulos' => array(
			'className' => 'Articulos',
			'foreignKey' => 'nivele_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function todosNiveles($colegio_id)
	{
		$niveles = $this->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				'Nivele.colegio_id' => $colegio_id
			),
			'fields' => array(
				'Nivele.id', 'CatalogoNivele.id', 'CatalogoNivele.nombre'
			)
		));

		return $niveles;
	}
	

//=========================================================================


	public function nivelEspecifico($id)
	{
		$nivel = $this->find('first', array(
			'recursive' => 0,
			'conditions' => array(
				'Nivele.id' => $id
			),
			'fields' => array(
				'Nivele.id', 'CatalogoNivele.id', 'CatalogoNivele.nombre'
			)
		));

		return $nivel;
	}
	

//=========================================================================


	public function traerNivel($condiciones)
	{
		$nivel = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'Nivele.id', 'CatalogoNivele.id', 'CatalogoNivele.nombre'
			)
		));

		return $nivel;
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
				INSERT INTO CCM.niveles
					(colegio_id, cat_niv_id)
				VALUES (
					".$atributos['colegio_id'].",
					".$atributos['cat_niv_id']."
				)
			");

			if ($queryExitosa)
				return 1;
			else
				return 'No se pudo guardar.';
		}
	}

}
