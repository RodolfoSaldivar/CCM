<?php
App::uses('AppModel', 'Model');
/**
 * CatalogoNivele Model
 *
 */
class CatalogoNivele extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Nivele' => array(
			'className' => 'Nivele',
			'foreignKey' => 'cat_niv_id',
			'dependent' => false
		),
		'CatalogoGrado' => array(
			'className' => 'CatalogoGrado',
			'foreignKey' => 'cat_niv_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function todosNiveles()
	{
		$niveles = $this->find('all', array(
			'fields' => array(
				'id', 'nombre'
			)
		));

		return $niveles;
	}

}
