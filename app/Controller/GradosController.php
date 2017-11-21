<?php
App::uses('AppController', 'Controller');
/**
 * Grados Controller
 *
 */
class GradosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Todos
		return true;
    }
	

//=========================================================================


	public function dropdown_grados()
	{
		$this->layout='ajax';

		$nivele_id = $this->request->data["nivel"];
		$tabla = $this->request->data["tabla"];
		$grado_id = $this->request->data["grado_id"];

		$grados = $this->Grado->todosGrados($nivele_id);

		$this->set('grados', $grados);
		$this->set('tabla', $tabla);
		$this->set('grado_id', $grado_id);
	}
	

//=========================================================================


	public function dropdown_grados_con_todos()
	{
		$this->layout='ajax';

		$nivele_id = $this->request->data["nivel"];

		if ($nivele_id == "todos")
			$grados = $this->Grado->todosGrados(0);
		else
			$grados = $this->Grado->todosGrados($nivele_id);

		$this->set('nivele_id', $nivele_id);
		$this->set('grados', $grados);
	}

}
