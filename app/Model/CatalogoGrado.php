<?php
App::uses('AppModel', 'Model');
/**
 * CatalogoGrado Model
 *
 */
class CatalogoGrado extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
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
			'foreignKey' => 'cat_gra_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function todosGrados()
	{
		$niv_gra = $this->find('all', array(
			'fields' => array(
				'CatalogoGrado.id', 'CatalogoGrado.nombre', 'CatalogoGrado.cat_niv_id'
			),
			'order' => array(
				'cat_niv_id' => 'asc'
			)
		));

		return $niv_gra;
	}

}
