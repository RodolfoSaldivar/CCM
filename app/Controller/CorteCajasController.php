<?php
App::uses('AppController', 'Controller');
/**
 * CorteCajas Controller
 *
 */
class CorteCajasController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Cajeros
		if (isset($user['tipo']) && in_array($user['tipo'], array("Cajero", "Director")))
		{
			return true;
		}

		return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");

		$user_tipo = $this->Session->read("Auth.User.tipo");
		$colegios = $this->Colegio->todosColegios(array('id >' => '0'));

		$this->set("colegios", $colegios);
		$this->set("user_tipo", $user_tipo);
	}


//=========================================================================


	public function filtrar_cortes()
	{
		$this->layout='ajax';

		$colegio_id = $this->request->data["colegio"];
		$cajero_id = $this->request->data["cajero_id"];

		if (in_array($this->Session->read("Auth.User.tipo"), array("CCM", "Director")))
		{
			$condiciones = array(
				'Asociado.colegio_id' => $colegio_id
			);
		}
		else
		{
			$condiciones = array(
				'CorteCaja.cajero_id' => $cajero_id
			);
		}

		$cortes = $this->CorteCaja->traerCortes($condiciones);

		$this->set("cortes", $cortes);
	}
	

//=========================================================================


	public function ver($corte_id)
	{
		$this->layout = 'admin';
		$this->loadModel("CicloHijo");
		$this->loadModel("Nivele");
		$this->loadModel("Grado");
		$this->loadModel("Hijo");
		$this->loadModel("Pago");

		$corte = $this->CorteCaja->traerUnico(array("CorteCaja.id" => $corte_id));
		$asociado_nombre =$corte["Asociado"]["nombre"]." ".$corte["Asociado"]["a_paterno"]." ".$corte["Asociado"]["a_materno"];

		$pagos = $this->Pago->traerPagos(array('Pago.corte_caja_id' => $corte_id));

		$sintesis = array(
			'Efectivo' => array('f' => 0, 'nf' => 0),
			'Cheque'   => array('f' => 0, 'nf' => 0),
			'Deposito' => array('f' => 0, 'nf' => 0),
			'Tarjeta'  => array('f' => 0, 'nf' => 0),
			'TOTAL'    => array('f' => 0, 'nf' => 0)
		);

		foreach ($pagos as $key => $pago)
		{
			if (!$pago["Pedido"]["fecha_cancelado"])
			{
				switch ($pago["Pago"]["forma_pago"])
				{
					case 'efectivo':
						if ($pago["Pedido"]["fecha_facturado"])
						{
							$sintesis["Efectivo"]["f"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["f"]+= $pago["Pago"]["importe"];
						}
						else
						{
							$sintesis["Efectivo"]["nf"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["nf"]+= $pago["Pago"]["importe"];
						}
						break;
						
					case 'cheque':
						if ($pago["Pedido"]["fecha_facturado"])
						{
							$sintesis["Cheque"]["f"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["f"]+= $pago["Pago"]["importe"];
						}
						else
						{
							$sintesis["Cheque"]["nf"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["nf"]+= $pago["Pago"]["importe"];
						}
						break;
						
					case 'banco':
						if ($pago["Pedido"]["fecha_facturado"])
						{
							$sintesis["Deposito"]["f"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["f"]+= $pago["Pago"]["importe"];
						}
						else
						{
							$sintesis["Deposito"]["nf"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["nf"]+= $pago["Pago"]["importe"];
						}
						break;
						
					case 'tarjeta':
						if ($pago["Pedido"]["fecha_facturado"])
						{
							$sintesis["Tarjeta"]["f"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["f"]+= $pago["Pago"]["importe"];
						}
						else
						{
							$sintesis["Tarjeta"]["nf"]+= $pago["Pago"]["importe"];
							$sintesis["TOTAL"]["nf"]+= $pago["Pago"]["importe"];
						}
						break;
				}

				$ciclo = $this->CicloHijo->find('first', array(
					'conditions' => array('id' => $pago["Pedido"]["ciclo_hijo_id"]),
					'fields' => array('id', 'hijo_id', 'ciclo', 'colegio_id', 'nivele_id', 'grado_id')
				));
				$nivele = $this->Nivele->nivelEspecifico($ciclo["CicloHijo"]["nivele_id"]);
				$grado = $this->Grado->gradoEspecifico($ciclo["CicloHijo"]["grado_id"]);
				$hijo = $this->Hijo->find('first', array(
					'conditions' => array('id' => $ciclo["CicloHijo"]["hijo_id"]),
					'fields' => array('nombre', 'a_paterno', 'a_materno')
				));

				$pagos[$key]["Ciclo"]['nivel'] = $nivele["CatalogoNivele"]["nombre"];
				$pagos[$key]["Ciclo"]['grado'] = $grado["CatalogoGrado"]["nombre"];
				$pagos[$key]["Ciclo"]['alumno'] = $hijo["Hijo"]["nombre"]." ".$hijo["Hijo"]["a_paterno"]." ".$hijo["Hijo"]["a_materno"];
			}
		}

		$this->set("asociado_nombre", $asociado_nombre);
		$this->set("sintesis", $sintesis);
		$this->set("corte", $corte);
		$this->set("pagos", $pagos);
	}

}