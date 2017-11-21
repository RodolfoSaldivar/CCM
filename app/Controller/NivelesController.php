<?php
App::uses('AppController', 'Controller');
/**
 * Niveles Controller
 *
 */
class NivelesController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Todos
		return true;
    }
	

//=========================================================================


	public function dropdown_niveles()
	{
		$this->layout='ajax';

		$colegio_id = $this->request->data["colegio"];
		$tabla = $this->request->data["tabla"];
		$nivele_id = $this->request->data["nivele_id"];

		if ($colegio_id == "nada")
			$colegio_id = 0;

		$niveles = $this->Nivele->todosNiveles($colegio_id);

		$this->set('niveles', $niveles);
		$this->set('tabla', $tabla);
		$this->set('nivele_id', $nivele_id);
		$this->set('colegio_id', $colegio_id);
	}
	

//=========================================================================


	public function dropdown_niveles_con_todos()
	{
		$this->layout='ajax';

		$colegio_id = $this->request->data["colegio"];

		$niveles = $this->Nivele->todosNiveles($colegio_id);

		$this->set('niveles', $niveles);
	}

}
