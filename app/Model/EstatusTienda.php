<?php
App::uses('AppModel', 'Model');
/**
 * EstatusTienda Model
 *
 */
class EstatusTienda extends AppModel {
	

//=========================================================================


	public function valor()
	{
		$activo = $this->find('first', array(
			'conditions' => array('id' => 1),
			'fields' => array('activo')
		));

		return $activo["EstatusTienda"]["activo"];
	}
	

//=========================================================================


	public function cambiarActivo($activo)
	{
		$queryExitosa = $this->query("
			UPDATE CCM.estatus_tiendas
			SET activo = $activo
			WHERE id = 1
		");

		if ($queryExitosa)
			return 1;
		else
			return 'No se pudo guardar.';
	}

}
