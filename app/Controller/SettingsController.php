<?php
App::uses('AppController', 'Controller');
/**
 * Settings Controller
 *
 */
class SettingsController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Todos
		if (in_array($this->action, array('tienda_cerrada')))
			return true;

		return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('tienda_cerrada');
	}
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("EstatusTienda");

		$activo = $this->EstatusTienda->valor();
		$this->set("activo", $activo);
	}
	

//=========================================================================


	public function tienda_activo()
	{
		$this->layout='ajax';
		$this->loadModel("EstatusTienda");
		$activo = $this->request->data["activo"];

		if ($activo == 1)
			$activo = 0;
		else
			$activo = 1;

		$this->EstatusTienda->cambiarActivo($activo);
		$this->set("activo", $activo);
	}
	

//=========================================================================


	public function tienda_cerrada()
	{
		$this->layout = 'vacio';
	}

}
