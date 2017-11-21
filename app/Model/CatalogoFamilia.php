<?php
App::uses('AppModel', 'Model');
/**
 * CatalogoFamilia Model
 *
 */
class CatalogoFamilia extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Articulo' => array(
			'className' => 'Articulo',
			'foreignKey' => 'cat_fam_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function todasFamilias()
	{
		$familias = $this->find('all', array(
			'fields' => array(
				'id', 'nombre'
			),
			'order' => array('id' => 'asc')
		));

		return $familias;
	}
	

//=========================================================================


	public function familiaEspecifica($id)
	{
		$familia = $this->find('first', array(
			'conditions' => array('id' => $id),
			'fields' => array(
				'id', 'nombre'
			),
			'order' => array('id' => 'asc')
		));

		return $familia;
	}
	

//=========================================================================


	public function traerFamilia($condiciones)
	{
		$familia = $this->find('first', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'nombre'
			),
			'order' => array('id' => 'asc')
		));

		return $familia;
	}

}
