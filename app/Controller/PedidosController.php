<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('File', 'Utility');
/**
 * Pedidos Controller
 *
 */
class PedidosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Padres y Cajeros
		if (in_array($this->action, array('comprobante', 'ver_pdf', 'finalizar')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Padre", "Cajero")))
    		{
    			return true;
    		}
        }

		//Acceso para Cajeros
		if (in_array($this->action, array('web_service_facturar', 'opcion_facturar', 'opcion_cancelar_pedido', 'opcion_cancelar_factura', 'sintesis', 'sintesis_actualizar', 'detalle', 'detalle_actualizar')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Cajero")))
    		{
    			return true;
    		}
        }

		return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('comprobante');
	}
	

//=========================================================================


	function web_service_facturar($pedido_id, $clave_pago)
	{
	    App::import('Controller', 'Cajas');
		$cajas_controller = new CajasController;
		
		$cajas_controller->facturarMizar($pedido_id, $clave_pago);
	}
	

//=========================================================================


	public function opcion_facturar($pedido_id = 0, $forma_pago = 0)
	{
		if ($pedido_id && $forma_pago)
		{
			if (strpos($forma_pago, ","))
				$clave_pago = "99";
			else
			{
				switch ($forma_pago)
				{
					case 'efectivo':
						$clave_pago = "01";
						break;

					case 'banco':
						$clave_pago = "99";
						break;

					case 'cheque':
						$clave_pago = "02";
						break;

					case 'tarjeta':
						$clave_pago = "04";
						break;

					case 'en línea':
						$clave_pago = "04";
						break;
				}
			}

	    	$this->web_service_facturar($pedido_id, $clave_pago);
		}

		$this->Session->setFlash('Si no se factura, envía un correo a:&nbsp;<u>fallas-fac@tiendaccm.mx</u>');
	    $this->redirect("/pedidos/detalle");
	}
	

//=========================================================================


	public function opcion_cancelar_pedido($pedido_id = 0)
	{
		$queryExitosa = $this->Pedido->query("
			UPDATE CCM.pedidos
			SET	fecha_cancelado = '".$this->fechaHoy()."'
			WHERE id = $pedido_id
		");

		if ($queryExitosa)
		{
			$this->Session->setFlash("Pedido $pedido_id cancelado exitosamente.");
			$this->redirect("/pedidos/detalle");
		}
	}
	

//=========================================================================


	public function opcion_cancelar_factura($pedido_id = 0)
	{
		try {
			@$client = new SoapClient("http://209.15.226.227:8080/WsFactExternos/Comprobantes.asmx?WSDL");
		}
		catch (SoapFault $E)
		{
			$this->Session->setFlash("No se puede cancelar la factura en estos momentos, intente más tarde.");
			$this->redirect("/pedidos/detalle");
		}

		if (@$client)
		{
			$pedido = $this->Pedido->find('first', array(
				'conditions' => array('id' => $pedido_id),
				'fields' => array('uuid')
			));
			
			$params = array(
				'UUID' => $pedido["Pedido"]["uuid"],
				'MotivoCancelacion' => "Se debe hacer con otros datos de facturación."
			);

			$result = $client->CancelarComprobante($params)->CancelarComprobanteResult;
			$result = json_decode($result, true);

			$mensaje_mizar = trim(preg_replace('/\s+/', ' ', $result["Table1"][0]["Mensajes"]));
			// var_dump($result);

			if (!$result["Table1"][0]["Estatus"])
			{
				// $this->Session->setFlash($mensaje_mizar);
				$Email = new CakeEmail();
				$Email->template('default', 'no_facturado');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to(array("fallas-fac@tiendaccm.mx", "rodolfo.saldivar@udem.edu"));
				$Email->subject("Pruebas, Error en Cancelación del pedido $pedido_id");
				$Email->viewVars(array(
					'pedido_id' => $pedido_id,
					'error' => $mensaje_mizar,
					'info_paquetes' => 0
				));
				$Email->send();

				$this->Session->setFlash("Factura del pedido $pedido_id no cancelada.");
			}
			else
			{
				$queryExitosa = $this->Pedido->query("
					UPDATE CCM.pedidos
					SET	fecha_facturado = N''
					WHERE id = $pedido_id
				");

				if ($queryExitosa)
					$this->Session->setFlash("Factura del pedido $pedido_id cancelada exitosamente.");	
			}

			$this->redirect("/pedidos/detalle");
		}
	}
	

//=========================================================================


	public function sintesis()
	{
	    $this->layout = 'admin';

	    $this->loadModel("Colegio");

	    $colegios = $this->Colegio->todosColegios(array());
	    $ciclo_actual = $this->cicloActual();

	    $ciclos = array($ciclo_actual, $ciclo_actual-1, $ciclo_actual-2);

	    $user_tipo = $this->Session->read("Auth.User.tipo");

		$this->set("user_tipo", $user_tipo);
	    $this->set("colegios", $colegios);
	    $this->set("ciclos", $ciclos);
	}
	

//=========================================================================


	public function sintesis_actualizar()
	{
	    $this->layout = 'ajax';

	    $ciclo = $this->request->data("ciclo");
	    $colegio_id = $this->request->data("colegio_id");

	    $pedidos = $this->Pedido->traerSintesis($ciclo, $colegio_id);

	    $this->set("pedidos", $pedidos);
	}
	

//=========================================================================


	public function detalle()
	{
	    $this->layout = 'admin';

	    $this->loadModel("Colegio");

	    $colegios = $this->Colegio->todosColegios(array());
	    $ciclo_actual = $this->cicloActual();

	    $ciclos = array($ciclo_actual, $ciclo_actual-1, $ciclo_actual-2);

	    $user_tipo = $this->Session->read("Auth.User.tipo");
	    
		$this->set("user_tipo", $user_tipo);
	    $this->set("colegios", $colegios);
	    $this->set("ciclos", $ciclos);
	}
	

//=========================================================================


	public function detalle_actualizar()
	{
	    $this->layout = 'ajax';

	    $ciclo = $this->request->data("ciclo");
	    $colegio_id = $this->request->data("colegio_id");
	    $estatus = $this->request->data("estatus");

	    $pedidos = $this->Pedido->traerDetalle($ciclo, $colegio_id, $estatus);

	    $this->set("pedidos", $pedidos);
	}
	

//=========================================================================


	public function facturas()
	{
	    $this->layout = 'admin';

	    $this->loadModel("Colegio");

	    $colegios = $this->Colegio->todosColegios(array());
	    $ciclo1 = $this->cicloActual();
	    $ciclo2 = $this->cicloActual() + 1;
	    $ciclo = "$ciclo1 - $ciclo2";

	    $user_tipo = $this->Session->read("Auth.User.tipo");
	    
		$this->set("user_tipo", $user_tipo);
	    $this->set("colegios", $colegios);
	    $this->set("ciclo", $ciclo);
	    $this->set("ciclo_actual", $this->cicloActual());
	}
	

//=========================================================================


	public function facturas_actualizar()
	{
	    $this->layout = 'ajax';
	    $this->loadModel("Colegio");

	    $ciclo = $this->cicloActual();
	    $colegio_id = $this->request->data("colegio_id");

	    if ($colegio_id == "todos")
	    {
	    	$colegios = $this->Colegio->todosColegios(array('id >' => 0));
	    	$facturas = array();
	    	foreach ($colegios as $key => $colegio)
	    	{
	    		$fac = $this->Pedido->traerFacturas($ciclo, $colegio["Colegio"]["id"]);
	    		$facturas = array_merge($facturas, $fac);
	    	}
	    }
	    else
			$facturas = $this->Pedido->traerFacturas($ciclo, $colegio_id);

	    $this->set("facturas", $facturas);
	}
	

//=========================================================================


	public function comprobante($pedido_id = null)
	{
		$this->layout = 'vacio';

		$codigo_html = $this->Session->read("codigo_html");

		$this->set("codigo_html", $codigo_html);
		$this->set("pedido_id", $pedido_id);
	}
	

//=========================================================================


	public function ver_pdf($pedido_id = null, $cobrado = 0)
	{
		if ($cobrado)
		{
			$this->Session->write("Pago.ya_pagado", 1);
			$this->Session->write("pdf_creado", 1);
			$this->redirect("/carrito/terminar_pedido/$pedido_id");
		}
		else
		{
			$pedido = $this->Pedido->traerPedidos(array('id' => $pedido_id));
			$pdf = base64_decode($pedido[0]["Pedido"]["pdf_pedido"]);
			header('Content-Type: application/pdf');
			if ($pdf)
				echo $pdf;
			else
			{
				$this->Session->write("Pago.ya_pagado", 1);
				$this->Session->write("pdf_creado", 1);
				$this->redirect("/carrito/terminar_pedido/$pedido_id");
			}
		}
	}
	

//=========================================================================


	public function finalizar($pedido_id = null)
	{
		$this->loadModel("Pago");

		$info_padre = $this->Pedido->find('first', array(
			'recursive' => 0,
			'conditions' => array('Pedido.id' => $pedido_id),
			'fields' => array('Asociado.mail', 'Asociado.nombre', 'Asociado.a_paterno', 'Asociado.a_materno')
		));

		$mail = $info_padre["Asociado"]["mail"];
		$nombre = $info_padre["Asociado"]["nombre"];
		$a_paterno = $info_padre["Asociado"]["a_paterno"];
		$a_materno = $info_padre["Asociado"]["a_materno"];
		@$ya_pagado = $this->Session->read("Pago.ya_pagado");
		@$importe_total = $this->Session->read("Pago.importe_total");
		@$habilita_comprobante = $this->Session->read("habilita_comprobante");

		$this->Session->delete("Carrito");
		$this->Session->delete("Pago");
		$this->Session->delete("habilita_comprobante");
		
		$archivo = WWW_ROOT."/pdf/Pedido_$pedido_id.pdf";

		if (file_exists($archivo))
		{
			$Email = new CakeEmail();
			$Email->template('default', 'pedido');
			$Email->emailFormat('html');
			$Email->config('smtp');
			$Email->to($mail);
			$Email->subject("Pedido $pedido_id");
			$Email->attachments("pdf/Pedido_$pedido_id.pdf");
			$Email->viewVars(array(
				'nombre' => $nombre,
				'a_paterno' => $a_paterno,
				'a_materno' => $a_materno
			));
			$Email->send();

			$pdf_pedido = file_get_contents($archivo);
			$pdf_pedido = base64_encode($pdf_pedido);

			$pedido = $this->Pedido->traerPedidos(array('id' => $pedido_id));
			$pedido[0]["Pedido"]["pdf_pedido"] = $pdf_pedido;
			$this->Pedido->guardarEnBDD($pedido[0]["Pedido"], "editar");

			$file = new File($archivo);
			$file->delete();
		}

		// Cuando un cajero hace un pedido dentro de la caja, lo redirecciona a pagar ese pedido
		$vamos_a = $this->Session->read("vamos_a");
		if ($vamos_a)
		{
			$this->Session->delete("vamos_a");
			$this->redirect($vamos_a.$pedido_id);
		}

		// Cuando no se mando un pdf y se vuelve a crear
		$pdf_creado = $this->Session->read("pdf_creado");
		if ($pdf_creado)
		{
			$this->Session->delete("pdf_creado");
			$this->redirect("/pedidos/ver_pdf/$pedido_id");
		}

		// Cuando un padre ya pago su pedido y guardo sus datos de facturacion
		if ($ya_pagado && $habilita_comprobante)
		{
			// Se le manda 04 porque pago en linea y esa es la clave
			$this->web_service_facturar($pedido_id, "04");
		}
	}

}
