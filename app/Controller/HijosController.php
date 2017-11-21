<?php
App::uses('AppController', 'Controller');
/**
 * Hijos Controller
 *
 */
class HijosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Padres
		if (in_array($this->action, array('seleccionar_hijo', 'agregar', 'editar')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Padre")))
    		{
    			return true;
    		}
        }

	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function seleccionar_hijo()
	{
		$this->loadModel("Colegio");

		$asociado_id = $this->Session->read("Auth.User.id");

		$condiciones = array(
			'Hijo.asociado_id' => $asociado_id,
			'CicloHijo.ciclo' => $this->cicloActual(),
			'estatus' => 1
		);
		$hijos = $this->Hijo->traerHijos($condiciones);
		$this->set("hijos", $hijos);

		$id_colegios = array();
		foreach ($hijos as $key => $hijo)
			array_push($id_colegios, $hijo["CicloHijo"]["colegio_id"]);

		$colegios = $this->Colegio->todosColegios(array('id' => $id_colegios));

		$ciclo_1 = $this->cicloActual();
		$ciclo_2 = $ciclo_1+1;
		$this->set("ciclo_actual", $ciclo_1."-".$ciclo_2);
		$this->set("colegios", $colegios);
	}
	

//=========================================================================


	public function agregar()
	{
		$this->loadModel("Colegio");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;
			$data["Hijo"]["asociado_id"] = $this->Session->read("Auth.User.id");
			$data["CicloHijo"]["ciclo"] = $this->cicloActual();

			$guardado = $this->Hijo->guardarEnBDD($data, "agregar");

			if ($guardado != 1)
				$this->Session->setFlash($guardado);
			else
			{
		    	$this->Session->setFlash('Hijo agregado exitosamente.');
		    	$this->redirect(array('action' => 'seleccionar_hijo'));
			}
		}

		$colegios = $this->Colegio->todosColegios(array());

		$this->set("colegios", $colegios);
	}
	

//=========================================================================


	public function editar($id = null)
	{
		$this->loadModel("Colegio");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;
			$data["Hijo"]["asociado_id"] = $this->Session->read("Auth.User.id");
			$data["CicloHijo"]["ciclo"] = $this->cicloActual();

			$guardado = $this->Hijo->guardarEnBDD($data, "editar");

			if ($guardado != 1)
				$this->Session->setFlash($guardado);
			else
			{
		    	$this->Session->setFlash('Hijo editado exitosamente.');
		    	$this->redirect(array('action' => 'seleccionar_hijo'));
			}
		}

		$condiciones = array('Hijo.id' => $id);
		$hijo = $this->Hijo->traerHijos($condiciones);

		$colegios = $this->Colegio->todosColegios(array());

		$colegio_id = $hijo[0]["CicloHijo"]["colegio_id"];
		$nivele_id = $hijo[0]["CicloHijo"]["nivele_id"];
		$grado_id = $hijo[0]["CicloHijo"]["grado_id"];

		$this->set("hijo", $hijo);
		$this->set("colegios", $colegios);
		$this->set("colegio_id", $colegio_id);
		$this->set("nivele_id", $nivele_id);
		$this->set("grado_id", $grado_id);
	}
	

//=========================================================================


	public function eliminar($ciclo_id = null)
	{
		$this->loadModel("CicloHijo");
		
		$this->CicloHijo->eliminar($ciclo_id);

		$this->redirect('/hijos/seleccionar_hijo');
	}

}
